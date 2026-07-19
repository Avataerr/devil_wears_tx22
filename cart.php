<?php
require_once __DIR__ . "/config/functions.php";
require_login();

$page_title = "Cart";
$uid = (int)$_SESSION["user_id"];

if (isset($_POST["add_to_cart"])) {
    $pid = (int)$_POST["product_id"];

    $check = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $check->bind_param("ii", $uid, $pid);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $qty = (int)$row["quantity"] + 1;
        $upd = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $upd->bind_param("iii", $qty, $uid, $pid);
        $upd->execute();
        $upd->close();
    } else {
        $ins = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $ins->bind_param("ii", $uid, $pid);
        $ins->execute();
        $ins->close();
    }

    $check->close();
    $get = $conn->prepare("
    SELECT name
    FROM products
    WHERE id=?
    ");

    $get->bind_param("i",$pid);
    $get->execute();

    $product = $get->get_result()->fetch_assoc();

    $get->close();

    log_action(
        $conn,
        "Added '".$product["name"]."' to cart"
    );
    redirect("cart.php");
}

if (isset($_GET["remove"])) {
    $pid = (int)$_GET["remove"];
    $del = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $del->bind_param("ii", $uid, $pid);
    $del->execute();
    $get = $conn->prepare("
        SELECT name
        FROM products
        WHERE id=?
        ");

        $get->bind_param("i",$pid);
        $get->execute();

        $product = $get->get_result()->fetch_assoc();

        $get->close();

    log_action(
        $conn,
        "Removed '".$product["name"]."' from cart"
    );
    redirect("cart.php");
}

$cart = $conn->prepare("
    SELECT cart.product_id, cart.quantity, products.name, products.price, products.stock, products.image
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$cart->bind_param("i", $uid);
$cart->execute();
$result = $cart->get_result();
$total = 0;

require_once __DIR__ . "/header.php";
?>
<div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
    <div>
        <h1 class="page-heading mb-1">My Cart</h1>
        <div class="muted">Review the items you want to buy.</div>
    </div>
    <a href="index.php" class="btn btn-outline-dark">Continue Shopping</a>
</div>

<div class="page-card p-3 p-md-4">
    <?php if ($result->num_rows === 0): ?>
        <p class="mb-0">Your cart is empty.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): 
                    $sub = $row["price"] * $row["quantity"];
                    $total += $sub;
                ?>
                    <tr>
                        <td class="product-cell">
                            <div class="d-flex align-items-center gap-3">
                                 <div class="product-thumb-wrapper">
                                    <?php if (!empty($row["image"])): ?>
                                        <img
                                            src="uploads/products/<?= h($row["image"]) ?>"
                                            class="product-thumb"
                                            alt="<?= h($row["name"]) ?>">
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <strong><?= h($row["name"]) ?></strong>
                                </div>
                            </div>
                        </td>
                        <td>₱<?= number_format((float)$row["price"], 2) ?></td>
                        <td><?= (int)$row["quantity"] ?></td>
                        <td>₱<?= number_format((float)$sub, 2) ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-danger" href="cart.php?remove=<?= (int)$row["product_id"] ?>">Remove</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
            <strong>Total: ₱<?= number_format((float)$total, 2) ?></strong>
            <a class="btn btn-dark" href="checkout.php">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . "/footer.php"; ?>

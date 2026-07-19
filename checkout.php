<?php
require_once __DIR__ . "/config/functions.php";
require_login();

$page_title = "Checkout";
$uid = (int)$_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $payment_method = trim($_POST["payment_method"] ?? "Cash on Delivery");

    $cart = $conn->prepare("
        SELECT cart.product_id, cart.quantity, products.name, products.price, products.stock, products.image
        FROM cart
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id = ?
    ");
    $cart->bind_param("i", $uid);
    $cart->execute();
    $items = $cart->get_result();

    if ($items->num_rows === 0) {
        redirect("cart.php");
    }

    $total = 0;
    $rows = [];

    while ($r = $items->fetch_assoc()) {
        if ((int)$r["quantity"] > (int)$r["stock"]) {
            die("Not enough stock for one of the items.");
        }
        $rows[] = $r;
        $total += $r["price"] * $r["quantity"];
    }

    $order = $conn->prepare("INSERT INTO orders (user_id, total, status, payment_method) VALUES (?, ?, 'Pending Payment', ?)");
    $order->bind_param("ids", $uid, $total, $payment_method);
    $order->execute();
    $order_id = $conn->insert_id;
    $order->close();

    foreach ($rows as $r) {
        $oi = $conn->prepare("INSERT INTO order_items (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)");
        $oi->bind_param("iidi", $order_id, $r["product_id"], $r["price"], $r["quantity"]);
        $oi->execute();
        $oi->close();

        $upd = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $upd->bind_param("ii", $r["quantity"], $r["product_id"]);
        $upd->execute();
        $upd->close();
    }

    $clear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear->bind_param("i", $uid);
    $clear->execute();
    $clear->close();

    $productNames = [];

    foreach ($rows as $r) {

        $productNames[] =
            $r["name"] .
            " x" .
            $r["quantity"];

    }

    log_action(
        $conn,
        "Placed order containing: " .
        implode(", ", $productNames)
    );

    redirect("payment.php?order_id=" . $order_id);
}

$cart = $conn->prepare("
    SELECT cart.quantity, products.name, products.price, products.image
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$cart->bind_param("i", $uid);
$cart->execute();
$result = $cart->get_result();

$total = 0;
while ($row = $result->fetch_assoc()) {
    $total += $row["price"] * $row["quantity"];
}
$result->data_seek(0);

require_once __DIR__ . "/header.php";
?>
<div class="row g-3">
    <div class="col-lg-7">
        <div class="page-card p-4">
            <h1 class="page-heading mb-2">Checkout</h1>
            <p class="muted">Choose a payment method. This project does not use a real payment API yet.</p>

            <form method="post" class="mt-3">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select mb-3">
                    <option>Cash on Delivery</option>
                    <option>Bank Transfer</option>
                    <option>GCash (manual)</option>
                </select>
                <button class="btn btn-dark">Place Order</button>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="page-card p-4">
            <div class="section-title">Order Summary</div>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <?php if (!empty($row["image"])): ?>
                            <img
                            src="uploads/products/<?= h($row["image"]) ?>"
                            class="product-thumb-small"
                            alt="<?= h($row["name"]) ?>"
                            >
                        <?php endif; ?>
                    <div>
                        <strong><?= h($row["name"]) ?></strong><br>
                        Qty: <?= (int)$row["quantity"] ?>
                     </div>
                </div>
                    <strong>
                        ₱<?= number_format($row["price"] * $row["quantity"],2) ?>
                    </strong>
            </div>
            <?php endwhile; ?>
            <hr>
            <strong>Total: ₱<?= number_format((float)$total, 2) ?></strong>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/footer.php"; ?>

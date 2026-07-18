<?php
require_once __DIR__ . "/../config/functions.php";
require_admin();

$page_title = "Manage Products";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_product"])) {
    $id = (int)($_POST["id"] ?? 0);
    $category_id = (int)$_POST["category_id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = (float)$_POST["price"];
    $stock = (int)$_POST["stock"];

    $imageName = null;

    if (!empty($_FILES["product_image"]["name"])) {
        $uploadDir = __DIR__ . "/../uploads/products/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . "_" . basename($_FILES["product_image"]["name"]);
        $targetFile = $uploadDir . $imageName;

        move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile);
    }

    $stmt = $conn->prepare("
    INSERT INTO products
        (category_id, name, description, price, stock, image)
    VALUES
        (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "issdis", $category_id, $name, $description, $price, $stock, $imageName);

    $stmt->execute();
    $stmt->close();

    log_action($conn, "Added product: " . $name);
    redirect("products.php");
}

if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    log_action($conn, "Deleted product #$id");
    redirect("products.php");
}

$categories = $conn->query("SELECT * FROM categories ORDER BY name");
$products = $conn->query("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
?>

<?php require_once __DIR__ . "/../header.php"; ?>



<h2 class="h4 mb-3">Manage Products / Stocks / Prices</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data" class="row g-2">

            <div class="col-md-2">
                <select name="category_id" class="form-select" required>
                    <?php
                    $categories->data_seek(0);
                    while ($c = $categories->fetch_assoc()):
                    ?>
                        <option value="<?= (int)$c["id"] ?>"><?= h($c["name"]) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-2">
                <input name="name" class="form-control" placeholder="Product name" required>
            </div>

            <div class="col-md-3">
                <textarea
                    name="description"
                    class="form-control"
                    rows="2"
                    placeholder="Product description">
                </textarea>
            </div>

            <div class="col-md-1">
                <input name="price" type="number" step="0.01" class="form-control" placeholder="Price" required>
            </div>

            <div class="col-md-1">
                <input name="stock" type="number" class="form-control" placeholder="Stock" required>
            </div>

            <div class="col-md-2">
                <input type="file" name="product_image" class="form-control">
            </div>

            <div class="col-md-1">
                <button class="btn btn-dark w-100" name="save_product">Add</button>
            </div>
        </form>
    </div>
</div>

<table class="table table-striped bg-white shadow-sm align-middle">
    <thead>
        <tr>
            <th>Image</th>
            <th>ID</th>
            <th>Category</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($p = $products->fetch_assoc()): ?>
        <tr>
            <td style="width:90px;">
                <?php if (!empty($p["image"])): ?>
                    <img
                        src="../uploads/products/<?= h($p["image"]) ?>"
                        class="product-thumb"
                        alt="<?= h($p["name"]) ?>"
                    >
                <?php else: ?>
                    <span class="text-muted">No image</span>
                <?php endif; ?>
            </td>
            <td><?= (int)$p["id"] ?></td>
            <td><?= h($p["category_name"]) ?></td>
            <td><?= h($p["name"]) ?></td>
            <td>₱<?= number_format((float)$p["price"], 2) ?></td>
            <td><?= (int)$p["stock"] ?></td>
            <td>
                <a class="btn btn-sm btn-warning"
                href="edit_product.php?id=<?= (int)$p["id"] ?>">
                    Edit
                </a>

                <a class="btn btn-sm btn-outline-danger"
                href="products.php?delete=<?= (int)$p["id"] ?>"
                onclick="return confirm('Delete this product?')">
                Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require_once __DIR__ . "/../footer.php"; ?>
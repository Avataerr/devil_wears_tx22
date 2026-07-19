<?php
require_once __DIR__ . "/../config/functions.php";
require_admin();

$page_title = "Edit Product";

if (!isset($_GET["id"])) {
    redirect("products.php");
}

$id = (int)$_GET["id"];

// Get product
$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    redirect("products.php");
}

// Save changes
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_id = (int)$_POST["category_id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = (float)$_POST["price"];
    $stock = (int)$_POST["stock"];

    // Keep old image by default
    $imageName = $product["image"];

    // Replace image if a new one is uploaded
    if (!empty($_FILES["product_image"]["name"])) {
        $uploadDir = __DIR__ . "/../uploads/products/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageName = time() . "_" . basename($_FILES["product_image"]["name"]);
        move_uploaded_file($_FILES["product_image"]["tmp_name"], $uploadDir . $imageName);
    }

    $stmt = $conn->prepare("
        UPDATE products
        SET category_id = ?, name = ?, description = ?, price = ?, stock = ?, image = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "issdisi",
        $category_id,
        $name,
        $description,
        $price,
        $stock,
        $imageName,
        $id
    );

    $stmt->execute();
    $stmt->close();

    log_action(
        $conn,
        "Updated product '" . $name .
        "' (Price: ₱" . number_format($price,2) .
        ", Stock: " . $stock . ")"
    );
    redirect("products.php");
}

// Categories
$categories = $conn->query("
    SELECT *
    FROM categories
    ORDER BY name
");

require_once __DIR__ . "/../header.php";
?>

<h1 class="page-heading mb-4">Edit Product</h1>

<div class="page-card p-4">
    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    <?php while($c = $categories->fetch_assoc()): ?>
                        <option value="<?= $c["id"] ?>" <?= $product["category_id"] == $c["id"] ? "selected" : "" ?>>
                            <?= h($c["name"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= h($product["name"]) ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-control" required><?= h($product["description"]) ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= $product["price"] ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="<?= $product["stock"] ?>" required>
            </div>
        </div>

        <hr>

        <h5 class="mb-3">Current Product Image</h5>
        <?php if(!empty($product["image"])): ?>
            <img src="../uploads/products/<?= h($product["image"]) ?>" class="edit-product-image mb-3" alt="<?= h($product["name"]) ?>">
        <?php else: ?>
            <p class="text-muted">No image uploaded.</p>
        <?php endif; ?>

        <div class="mb-4">
            <label class="form-label">Replace Image</label>
            <input type="file" name="product_image" class="form-control">
            <small class="text-muted">Leave blank if you don't want to replace the image.</small>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-dark">Save Changes</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . "/../footer.php"; ?>
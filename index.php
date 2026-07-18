<?php
$page_title = "Store";
require_once __DIR__ . "/header.php";

$products = $conn->query("
    SELECT p.id, p.name, p.description, p.price, p.stock, p.image, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    ORDER BY c.name, p.name
");
?>

<div class="hero-panel mb-4">
    <div class="hero-kicker">TX22 Bag Collection</div>
    <h1 class="hero-title">Find the right bag for class, travel, and everyday use.</h1>
    <p class="hero-text">
        Browse our bag categories, add items to your cart, and proceed to checkout.
        This project is built for educational purposes only.
    </p>
</div>

<div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
    <div>
        <div class="section-title mb-1">Featured Products</div>
        <div class="muted">All products are grouped by category.</div>
    </div>
    <span class="pill">Secure checkout demo</span>
</div>

<div class="row g-3">
<?php if ($products->num_rows === 0): ?>
    <div class="col-12">
        <div class="page-card p-4 text-center">
            <h2 class="h5 mb-2">No products yet</h2>
            <p class="muted mb-0">The admin can add bags, set prices, and manage stock from the seller side.</p>
        </div>
    </div>
<?php else: ?>
    <?php while ($p = $products->fetch_assoc()): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
            <?php if (!empty($p["image"])): ?>
                <img src="uploads/products/<?= h($p["image"]) ?>"
                    class="card-img-top product-card-image"
                    alt="<?= h($p["name"]) ?>">
            <?php endif; ?>

            <div class="card-body">
                <span class="badge bg-secondary mb-2"><?= h($p["category_name"]) ?></span>
                <h5 class="card-title"><?= h($p["name"]) ?></h5>
                <p class="card-text"><?= h($p["description"]) ?></p>
                <p class="fw-bold">₱<?= number_format((float)$p["price"], 2) ?></p>
                <p class="text-muted mb-3">Stock: <?= (int)$p["stock"] ?></p>

                <?php if (is_logged_in()): ?>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="<?= (int)$p["id"] ?>">
                    <button class="btn btn-dark btn-sm" name="add_to_cart">Add to Cart</button>
                </form>
                <?php else: ?>
                <a class="btn btn-outline-dark btn-sm" href="login.php">Login to buy</a>
                <?php endif; ?>
            </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
</div>

<?php require_once __DIR__ . "/footer.php"; ?>

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

<section class="hero-panel mb-5">
    <div class="hero-kicker">The TX22 Collection</div>
    <h1 class="hero-title">
        The Devil<br>
        Wears <span class="accent-word">Bags.</span>
    </h1>
    <p class="hero-text">
        Statement bags for class, travel, work, and everyday life. Explore our latest collection and carry your style with confidence.
    </p>
</section>

<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
        <div class="section-title mb-1">Featured Collection</div>
        <div class="muted">Browse bags arranged by category.</div>
    </div>
    <span class="pill">Educational Store Demo</span>
</div>

<?php
$currentCategory = "";

while ($p = $products->fetch_assoc()):
    if ($currentCategory != $p["category_name"]):
        if ($currentCategory != "") {
            echo "</div>"; // Closes the previous .row
        }
        $currentCategory = $p["category_name"];
?>

        <h2 class="section-title mt-5 mb-4"><?= h($currentCategory) ?></h2>
        <div class="row g-4">

<?php 
    endif; 
?>

    <div class="col-sm-6 col-lg-4">
        <article class="card h-100">
            <?php if (!empty($p["image"])): ?>
                <img src="uploads/products/<?= h($p["image"]) ?>" class="card-img-top product-card-image" alt="<?= h($p["name"]) ?>">
            <?php endif; ?>

            <div class="card-body">
                <h3 class="card-title h4"><?= h($p["name"]) ?></h3>
                <p class="card-text"><?= h($p["description"]) ?></p>
                <p class="product-price">₱<?= number_format($p["price"], 2) ?></p>
                <p class="product-stock">Stock: <?= $p["stock"] ?></p>

                <?php if (is_logged_in()): ?>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?= $p["id"] ?>">
                        <button class="btn btn-dark w-100" name="add_to_cart" <?= $p["stock"] <= 0 ? "disabled" : "" ?>>
                            <?= $p["stock"] <= 0 ? "Out of Stock" : "Add to Cart" ?>
                        </button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-outline-dark w-100" href="login.php">Login to Buy</a>
                <?php endif; ?>
            </div>
        </article>
    </div>

<?php 
endwhile; 

if ($currentCategory != "") {
    echo "</div>";
}
?>

<?php require_once __DIR__ . "/footer.php"; ?>

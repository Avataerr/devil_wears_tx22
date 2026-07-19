<?php
$page_title = "Store";
require_once __DIR__ . "/header.php";

$products = $conn->query("
    SELECT p.id, p.name, p.description, p.price, p.stock, p.image, c.name AS category_name
    FROM products p
    JOIN categories c ON p.category_id = c.id
    ORDER BY c.name, p.name
");

$groupedProducts = [];
if ($products) {
    while ($row = $products->fetch_assoc()) {
        $groupedProducts[$row["category_name"]][] = $row;
    }
}
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
        <div class="muted">Swipe or scroll sideways to browse the items.</div>
    </div>
    <span class="pill">Educational Store Demo</span>
</div>

<?php if (!empty($groupedProducts)): ?>
    <?php foreach ($groupedProducts as $categoryName => $items): ?>
        <h2 class="section-title mt-5 mb-3"><?= h($categoryName) ?></h2>

        <div class="product-strip">
            <?php foreach ($items as $p): ?>
                <article class="card product-card h-100">
                    <button
                        type="button"
                        class="product-trigger"
                        data-bs-toggle="modal"
                        data-bs-target="#productModal"
                        data-name="<?= h($p["name"]) ?>"
                        data-category="<?= h($categoryName) ?>"
                        data-price="₱<?= number_format($p["price"], 2) ?>"
                        data-stock="<?= (int)$p["stock"] ?>"
                        data-description="<?= h($p["description"]) ?>"
                        data-image="<?= h(!empty($p["image"]) ? "uploads/products/" . $p["image"] : "") ?>"
                        aria-label="View details for <?= h($p["name"]) ?>"
                    >
                        <?php if (!empty($p["image"])): ?>
                            <img
                                src="uploads/products/<?= h($p["image"]) ?>"
                                class="card-img-top product-card-image"
                                alt="<?= h($p["name"]) ?>"
                                loading="lazy"
                            >
                        <?php endif; ?>

                        <div class="card-body product-card-body">
                            <div class="product-card-category"><?= h($categoryName) ?></div>
                            <h3 class="card-title h5 mb-2"><?= h($p["name"]) ?></h3>
                            <p class="product-price mb-2">₱<?= number_format($p["price"], 2) ?></p>
                            <p class="product-stock mb-0">Stock: <?= (int)$p["stock"] ?></p>
                            <span class="product-view-hint">Tap to view details</span>
                        </div>
                    </button>

                    <div class="card-body pt-0">
                        <?php if (is_logged_in()): ?>
                            <form method="post" action="cart.php" class="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?= (int)$p["id"] ?>">
                                <button
                                    class="btn btn-dark w-100"
                                    name="add_to_cart"
                                    <?= ((int)$p["stock"] <= 0) ? "disabled" : "" ?>
                                >
                                    <?= ((int)$p["stock"] <= 0) ? "Out of Stock" : "Add to Cart" ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <a class="btn btn-outline-dark w-100" href="login.php">Login to Buy</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-dark">No products found.</div>
<?php endif; ?>

<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content product-modal">
            <div class="modal-header border-0">
                <div>
                    <div class="modal-kicker">Product Details</div>
                    <h5 class="modal-title" id="productModalLabel">Product Name</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-4 align-items-center">
                    <div class="col-md-5" id="productModalImageWrap">
                        <img id="productModalImage" src="" alt="" class="img-fluid product-modal-image">
                    </div>

                    <div class="col-md-7">
                        <div class="product-modal-meta mb-4">
                            <div class="meta-row">
                                <span class="meta-label">Name</span>
                                <span class="meta-value" id="productModalName"></span>
                            </div>

                            <div class="meta-row">
                                <span class="meta-label">Category</span>
                                <span class="meta-value" id="productModalCategory"></span>
                            </div>

                            <div class="meta-row">
                                <span class="meta-label">Price</span>
                                <span class="meta-value" id="productModalPrice"></span>
                            </div>

                            <div class="meta-row">
                                <span class="meta-label">Stock</span>
                                <span class="meta-value" id="productModalStock"></span>
                            </div>
                        </div>

                        <p class="product-modal-description mb-0" id="productModalDescription"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("productModal");
    if (!modal) return;

    const modalImageWrap = document.getElementById("productModalImageWrap");
    const modalImage = document.getElementById("productModalImage");
    const modalTitle = document.getElementById("productModalLabel");
    const modalName = document.getElementById("productModalName");
    const modalCategory = document.getElementById("productModalCategory");
    const modalPrice = document.getElementById("productModalPrice");
    const modalStock = document.getElementById("productModalStock");
    const modalDescription = document.getElementById("productModalDescription");

    modal.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget;

        modalTitle.textContent = button.getAttribute("data-name");
        modalName.textContent = button.getAttribute("data-name");
        modalCategory.textContent = button.getAttribute("data-category");
        modalPrice.textContent = button.getAttribute("data-price");
        modalStock.textContent = button.getAttribute("data-stock");
        modalDescription.textContent = button.getAttribute("data-description");

        const image = button.getAttribute("data-image");

        if (image) {
            modalImage.src = image;
            modalImage.alt = button.getAttribute("data-name");
            modalImageWrap.classList.remove("d-none");
        } else {
            modalImageWrap.classList.add("d-none");
        }
    });

    document.querySelectorAll(".add-to-cart-form").forEach(function (form) {

        form.addEventListener("submit", async function (e) {

            e.preventDefault();

            const button = form.querySelector("button");
            const originalText = button.innerHTML;

            const formData = new FormData(form);
            formData.append("add_to_cart", "1");
            formData.append("ajax", "1");

            try {

                const response = await fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                const data = await response.json();

                if (data.success) {
                    button.disabled = true;
                    button.innerHTML = "✓ Added";
                    button.className = "btn btn-light w-100";

                    setTimeout(function () {
                        button.innerHTML = originalText;
                        button.className = "btn btn-dark w-100";
                        button.disabled = false;
                    }, 1500);
                } else {
                    button.innerHTML = "Try Again";

                    setTimeout(function () {
                        button.innerHTML = originalText;
                    }, 1500);
                }
            } catch {
                form.submit();
            }
        });
    });
});
</script>

<?php require_once __DIR__ . "/footer.php"; ?>
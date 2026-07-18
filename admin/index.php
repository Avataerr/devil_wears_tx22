<?php
require_once __DIR__ . "/../config/functions.php";
require_admin();

$page_title = "Admin Dashboard";

$users = $conn->query("SELECT COUNT(*) AS cnt FROM users")->fetch_assoc()["cnt"];
$products = $conn->query("SELECT COUNT(*) AS cnt FROM products")->fetch_assoc()["cnt"];
$orders = $conn->query("SELECT COUNT(*) AS cnt FROM orders")->fetch_assoc()["cnt"];
$logs = $conn->query("SELECT COUNT(*) AS cnt FROM audit_log")->fetch_assoc()["cnt"];

require_once __DIR__ . "/../header.php";
?>
<div class="hero-panel mb-4">
    <div class="hero-kicker">Seller / Admin Panel</div>
    <h1 class="hero-title">Manage users, products, inventory, and reports.</h1>
    <p class="hero-text">This is the seller side of the system. In this project, sellers are treated as administrators.</p>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="page-card p-3">
            <div class="muted">Users</div>
            <div class="h2 mb-0"><?= (int)$users ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="page-card p-3">
            <div class="muted">Products</div>
            <div class="h2 mb-0"><?= (int)$products ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="page-card p-3">
            <div class="muted">Orders</div>
            <div class="h2 mb-0"><?= (int)$orders ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="page-card p-3">
            <div class="muted">Audit Logs</div>
            <div class="h2 mb-0"><?= (int)$logs ?></div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-2 flex-wrap">
    <a class="btn btn-dark" href="users.php">Manage Users</a>
    <a class="btn btn-dark" href="products.php">Manage Products</a>
    <a class="btn btn-dark" href="reports.php">Reports</a>
    <a class="btn btn-dark" href="audit_log.php">Audit Log</a>
</div>
<?php require_once __DIR__ . "/../footer.php"; ?>

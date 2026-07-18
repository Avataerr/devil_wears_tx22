<?php
require_once __DIR__ . "/../config/functions.php";
require_admin();

$page_title = "Reports";

$inventory = $conn->query("
    SELECT p.name, c.name AS category_name, p.stock, p.price
    FROM products p
    JOIN categories c ON p.category_id = c.id
    ORDER BY c.name, p.name
");

$uid = (int)$_SESSION["user_id"];
$logs = $conn->prepare("
    SELECT a.created_at, a.action, u.first_name, u.middle_name, u.surname
    FROM audit_log a
    JOIN users u ON a.user_id = u.id
    WHERE a.user_id = ?
    ORDER BY a.id DESC
");
$logs->bind_param("i", $uid);
$logs->execute();
$log_rows = $logs->get_result();

require_once __DIR__ . "/../header.php";
?>
<div class="hero-panel mb-4">
    <div class="hero-kicker">Reports</div>
    <h1 class="hero-title">Inventory and audit log report</h1>
    <p class="hero-text">The inventory report shows remaining stock, while the audit log shows the activities of the currently logged-in admin account.</p>
</div>

<div class="page-card p-3 p-md-4 mb-4">
    <div class="section-title">Remaining Inventory</div>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Remaining Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($i = $inventory->fetch_assoc()): ?>
                    <tr>
                        <td><?= h($i["category_name"]) ?></td>
                        <td><?= h($i["name"]) ?></td>
                        <td>₱<?= number_format((float)$i["price"], 2) ?></td>
                        <td><?= (int)$i["stock"] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="page-card p-3 p-md-4">
    <div class="section-title">Audit Log Report</div>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($l = $log_rows->fetch_assoc()): ?>
                    <tr>
                        <td><?= h($l["created_at"]) ?></td>
                        <td><?= h(trim($l["first_name"] . " " . ($l["middle_name"] ? $l["middle_name"] . " " : "") . $l["surname"])) ?></td>
                        <td><?= h($l["action"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . "/../footer.php"; ?>

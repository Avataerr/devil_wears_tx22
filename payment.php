<?php
require_once __DIR__ . "/config/functions.php";
require_login();

$page_title = "Payment";
$order_id = (int)($_GET["order_id"] ?? 0);

$order = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$order->bind_param("ii", $order_id, $_SESSION["user_id"]);
$order->execute();
$res = $order->get_result();
$data = $res->fetch_assoc();

require_once __DIR__ . "/header.php";
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="page-card p-4">
            <h1 class="page-heading mb-2">Order received</h1>
            <?php if (!$data): ?>
                <div class="alert alert-danger">Order not found.</div>
            <?php else: ?>
                <p><strong>Order ID:</strong> #<?= (int)$data["id"] ?></p>
                <p><strong>Total:</strong> ₱<?= number_format((float)$data["total"], 2) ?></p>
                <p><strong>Status:</strong> <?= h($data["status"]) ?></p>
                <p class="mb-0">This is only a manual payment page. No payment API is connected yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/footer.php"; ?>

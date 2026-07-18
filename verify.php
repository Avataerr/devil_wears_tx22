<?php
require_once __DIR__ . "/config/functions.php";

$page_title = "Verify";
$msg = "";
$error = "";

$token = $_GET["token"] ?? "";

if ($token !== "") {
    $stmt = $conn->prepare("SELECT id FROM users WHERE verify_token = ? AND status = 'pending'");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $uid = (int)$row["id"];

        $upd = $conn->prepare("UPDATE users SET status = 'active', verify_token = NULL WHERE id = ?");
        $upd->bind_param("i", $uid);
        $upd->execute();
        $upd->close();

        $msg = "Email confirmed. You can now log in.";
    } else {
        $error = "Invalid or expired verification link.";
    }

    $stmt->close();
} else {
    $error = "No token provided.";
}

require_once __DIR__ . "/header.php";
?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="page-card p-4">
            <h1 class="page-heading mb-3">Email Verification</h1>
            <div class="alert <?= $msg ? 'alert-success' : 'alert-danger' ?>">
                <?= h($msg ?: $error) ?>
            </div>
            <a href="login.php" class="btn btn-dark">Go to Login</a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/footer.php"; ?>

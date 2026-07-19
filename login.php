<?php
require_once __DIR__ . "/config/functions.php";

$page_title = "Login";
$error = "";
$msg = "";
    if (isset($_GET["registered"])) {
    $msg = "Registration successful! Please log in.";
    }

if (is_logged_in()) {
    redirect(is_admin() ? "admin/index.php" : "index.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT id, first_name, middle_name, surname, email, password, role, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if ($user["status"] !== "active") {
            $error = "Please confirm your email first.";
        } elseif ($password === $user["password"]) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["name"] = $user["first_name"];

            log_action($conn, "Logged into the system");

            if ($user["role"] === "admin") {
                redirect("admin/index.php");
            }

            redirect("index.php");
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
}

require_once __DIR__ . "/header.php";
?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="auth-card">
            <div class="card-body">
                <h1 class="page-heading mb-2">Login</h1>
                <p class="muted mb-3">Enter your email and password to continue.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= h($error) ?></div>
                <?php endif; ?>

                <?php if ($msg): ?>
                    <div class="alert alert-success">
                        <?= h($msg) ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">E-mail Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-dark w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/footer.php"; ?>

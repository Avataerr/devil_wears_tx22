<?php
require_once __DIR__ . "/config/functions.php";

$page_title = "Register";
$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST["first_name"] ?? "");
    $middle_name = trim($_POST["middle_name"] ?? "");
    $surname = trim($_POST["surname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";
    $address = trim($_POST["address"] ?? "");
    $contact = trim($_POST["contact_number"] ?? "");

    if (
        $first_name === "" ||
        $surname === "" ||
        $email === "" ||
        $password === "" ||
        $address === "" ||
        $contact === ""
    ) {
        $error = "Please complete all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {

            $error = "Email already exists.";

        } else {

            $stmt = $conn->prepare("
                INSERT INTO users
                (
                    first_name,
                    middle_name,
                    surname,
                    email,
                    password,
                    address,
                    contact_number,
                    role,
                    status
                )
                VALUES
                (?, ?, ?, ?, ?, ?, ?, 'buyer', 'active')
            ");

            $stmt->bind_param(
                "sssssss",
                $first_name,
                $middle_name,
                $surname,
                $email,
                $password,
                $address,
                $contact
            );

            if ($stmt->execute()) {
          
            header("Location: login.php?registered=1");
			exit;
            } else {
                $error = "Registration failed.";
            }
            $stmt->close();
        }
        $check->close();
    }
}

require_once __DIR__ . "/header.php";
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="auth-card">
            <div class="card-body">

                <h1 class="page-heading mb-2">Register</h1>
                <p class="muted mb-3">
                    Create a buyer account to start shopping.
                </p>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <?= h($error) ?>
                    </div>
                <?php endif; ?>

                <?php if ($msg): ?>
                    <div class="alert alert-success">
                        <?= h($msg) ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input
                                type="text"
                                class="form-control"
                                name="first_name"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input
                                type="text"
                                class="form-control"
                                name="middle_name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Surname</label>
                            <input
                                type="text"
                                class="form-control"
                                name="surname"
                                required>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label">E-mail Address</label>
                            <input
                                type="email"
                                class="form-control"
                                name="email"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input
                                type="text"
                                class="form-control"
                                name="contact_number"
                                required>
                        </div>
                    </div>
                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                name="password"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                class="form-control"
                                name="confirm_password"
                                required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Complete Address</label>
                        <textarea
                            class="form-control"
                            name="address"
                            rows="3"
                            required></textarea>
                    </div>
                    <div class="mt-4">
                        <button class="btn btn-dark w-100">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/footer.php"; ?>
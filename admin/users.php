<?php
require_once __DIR__ . "/../config/functions.php";
require_admin();

$page_title = "Manage Users";
$error = "";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_user"])) {
    $first_name = trim($_POST["first_name"] ?? "");
    $middle_name = trim($_POST["middle_name"] ?? "");
    $surname = trim($_POST["surname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "buyer";
    $status = $_POST["status"] ?? "active";

    if ($first_name === "" || $surname === "" || $email === "" || $password === "") {
        $error = "Please complete all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!in_array($role, ["admin", "buyer"], true)) {
        $error = "Invalid role selected.";
    } elseif (!in_array($status, ["pending", "active", "disabled"], true)) {
        $error = "Invalid status selected.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("
                INSERT INTO users (first_name, middle_name, surname, email, password, address, contact_number, role, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $default_address = "N/A";
            $default_contact = "N/A";
            $stmt->bind_param("sssssssss", $first_name, $middle_name, $surname, $email, $hash, $default_address, $default_contact, $role, $status);

            if ($stmt->execute()) {
                $msg = "User created successfully.";
                log_action($conn, "Created user " . $email . " as " . $role);
            } else {
                $error = "Failed to create user.";
            }

            $stmt->close();
        }

        $check->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_user"])) {
    $id = (int)$_POST["id"];
    $role = $_POST["role"];
    $status = $_POST["status"];

    if (!in_array($role, ["admin", "buyer"], true) || !in_array($status, ["pending", "active", "disabled"], true)) {
        $error = "Invalid update.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET role = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $role, $status, $id);
        $stmt->execute();
        $stmt->close();
        $msg = "User updated successfully.";
        log_action($conn, "Updated user #$id");
    }
}

$users = $conn->query("SELECT id, first_name, middle_name, surname, email, role, status, created_at FROM users ORDER BY id DESC");

require_once __DIR__ . "/../header.php";
?>
<div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
    <div>
        <h1 class="page-heading mb-1">Manage Users</h1>
        <div class="muted">Add users and change their admin access.</div>
    </div>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= h($error) ?></div>
<?php endif; ?>
<?php if ($msg): ?>
    <div class="alert alert-success"><?= h($msg) ?></div>
<?php endif; ?>

<div class="page-card p-4 mb-4">
    <div class="section-title">Add User</div>
    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Middle Name</label>
            <input type="text" name="middle_name" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Surname</label>
            <input type="text" name="surname" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="buyer">buyer</option>
                <option value="admin">admin</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active">active</option>
                <option value="pending">pending</option>
                <option value="disabled">disabled</option>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-dark" name="create_user">Create User</button>
        </div>
    </form>
</div>

<div class="page-card p-3 p-md-4">
    <div class="section-title">Existing Users</div>
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($u = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$u["id"] ?></td>
                    <td><?= h(trim($u["first_name"] . " " . ($u["middle_name"] ? $u["middle_name"] . " " : "") . $u["surname"])) ?></td>
                    <td><?= h($u["email"]) ?></td>
                    <td><?= h($u["role"]) ?></td>
                    <td><?= h($u["status"]) ?></td>
                    <td>
                        <form method="post" class="d-flex gap-2 flex-wrap">
                            <input type="hidden" name="id" value="<?= (int)$u["id"] ?>">
                            <select name="role" class="form-select form-select-sm" style="max-width: 120px;">
                                <option value="buyer" <?= $u["role"] === "buyer" ? "selected" : "" ?>>buyer</option>
                                <option value="admin" <?= $u["role"] === "admin" ? "selected" : "" ?>>admin</option>
                            </select>
                            <select name="status" class="form-select form-select-sm" style="max-width: 120px;">
                                <option value="pending" <?= $u["status"] === "pending" ? "selected" : "" ?>>pending</option>
                                <option value="active" <?= $u["status"] === "active" ? "selected" : "" ?>>active</option>
                                <option value="disabled" <?= $u["status"] === "disabled" ? "selected" : "" ?>>disabled</option>
                            </select>
                            <button class="btn btn-sm btn-dark" name="save_user">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . "/../footer.php"; ?>

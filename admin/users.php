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
    $password = trim($_POST["password"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $contact_number = trim($_POST["contact_number"] ?? "");
    $role = $_POST["role"] ?? "buyer";
    $status = $_POST["status"] ?? "active";

    if ($first_name === "" || $surname === "" || $email === "" || $password === "" || $address === "" || $contact_number === "") {
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
            $stmt = $conn->prepare("
                INSERT INTO users
                (first_name, middle_name, surname, email, password, address, contact_number, role, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param(
                "sssssssss",
                $first_name,
                $middle_name,
                $surname,
                $email,
                $password,
                $address,
                $contact_number,
                $role,
                $status
            );

            if ($stmt->execute()) {
                $msg = "User created successfully.";
                log_action(
                    $conn,
                    "Created buyer account for " .$first_name." ".$surname
                    );
            } else {
                $error = "Failed to create user.";
            }

            $stmt->close();
        }

        $check->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["save_user"])) {
    $id = (int)($_POST["id"] ?? 0);
    $first_name = trim($_POST["first_name"] ?? "");
    $middle_name = trim($_POST["middle_name"] ?? "");
    $surname = trim($_POST["surname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $contact_number = trim($_POST["contact_number"] ?? "");
    $role = $_POST["role"] ?? "buyer";
    $status = $_POST["status"] ?? "active";

    if ($id <= 0) {
        $error = "Invalid user ID.";
    } elseif ($first_name === "" || $surname === "" || $email === "" || $password === "" || $address === "" || $contact_number === "") {
        $error = "Please complete all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!in_array($role, ["admin", "buyer"], true)) {
        $error = "Invalid role selected.";
    } elseif (!in_array($status, ["pending", "active", "disabled"], true)) {
        $error = "Invalid status selected.";
    } else {
        $stmt = $conn->prepare("
            UPDATE users
            SET first_name = ?, middle_name = ?, surname = ?, email = ?, password = ?, address = ?, contact_number = ?, role = ?, status = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "sssssssssi",
            $first_name,
            $middle_name,
            $surname,
            $email,
            $password,
            $address,
            $contact_number,
            $role,
            $status,
            $id
        );

        if ($stmt->execute()) {
            $msg = "User updated successfully.";
            log_action($conn, "Updated account of ".$first_name." ".$surname);
        } else {
            $error = "Failed to update user.";
        }

        $stmt->close();
    }
}

$users = $conn->query("
    SELECT id, first_name, middle_name, surname, email, password, address, contact_number, role, status, created_at
    FROM users
    ORDER BY id DESC
");

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
            <input type="text" name="password" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
                <option value="buyer">buyer</option>
                <option value="admin">admin</option>
            </select>
        </div>
        <div class="col-md-3">
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
                    <th>Password</th>
                    <th>Address</th>
                    <th>Contact</th>
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
                        <td><?= h($u["password"]) ?></td>
                        <td><?= h($u["address"]) ?></td>
                        <td><?= h($u["contact_number"]) ?></td>
                        <td><?= h($u["role"]) ?></td>
                        <td><?= h($u["status"]) ?></td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-sm btn-warning edit-user-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#editUserModal"
                                data-id="<?= (int)$u["id"] ?>"
                                data-first-name="<?= h($u["first_name"]) ?>"
                                data-middle-name="<?= h($u["middle_name"]) ?>"
                                data-surname="<?= h($u["surname"]) ?>"
                                data-email="<?= h($u["email"]) ?>"
                                data-password="<?= h($u["password"]) ?>"
                                data-address="<?= h($u["address"]) ?>"
                                data-contact-number="<?= h($u["contact_number"]) ?>"
                                data-role="<?= h($u["role"]) ?>"
                                data-status="<?= h($u["status"]) ?>"
                            >
                                Edit
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white border border-secondary">
            <form method="post">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" id="edit_first_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" id="edit_middle_name" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Surname</label>
                            <input type="text" name="surname" id="edit_surname" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="text" name="password" id="edit_password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" id="edit_address" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" id="edit_contact_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" id="edit_role" class="form-select">
                                <option value="buyer">buyer</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="pending">pending</option>
                                <option value="active">active</option>
                                <option value="disabled">disabled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark" name="save_user">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("editUserModal");

    modal.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget;

        document.getElementById("edit_id").value = button.getAttribute("data-id") || "";
        document.getElementById("edit_first_name").value = button.getAttribute("data-first-name") || "";
        document.getElementById("edit_middle_name").value = button.getAttribute("data-middle-name") || "";
        document.getElementById("edit_surname").value = button.getAttribute("data-surname") || "";
        document.getElementById("edit_email").value = button.getAttribute("data-email") || "";
        document.getElementById("edit_password").value = button.getAttribute("data-password") || "";
        document.getElementById("edit_address").value = button.getAttribute("data-address") || "";
        document.getElementById("edit_contact_number").value = button.getAttribute("data-contact-number") || "";
        document.getElementById("edit_role").value = button.getAttribute("data-role") || "buyer";
        document.getElementById("edit_status").value = button.getAttribute("data-status") || "active";
    });
});
</script>

<?php require_once __DIR__ . "/../footer.php"; ?>
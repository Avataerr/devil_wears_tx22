<?php
require_once __DIR__ . "/../config/functions.php";
require_admin();

$page_title = "Audit Log";

$logs = $conn->query("
    SELECT a.created_at, a.action, a.ip_address, u.first_name, u.middle_name, u.surname
    FROM audit_log a
    JOIN users u ON a.user_id = u.id
    ORDER BY a.id DESC
");

require_once __DIR__ . "/../header.php";
?>
<div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
    <div>
        <h1 class="page-heading mb-1">Audit Log</h1>
        <div class="muted">All activities recorded by the system.</div>
    </div>
</div>

<div class="page-card p-3 p-md-4">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($l = $logs->fetch_assoc()): ?>
                    <tr>
                        <td><?= h($l["created_at"]) ?></td>
                        <td><?= h(trim($l["first_name"] . " " . ($l["middle_name"] ? $l["middle_name"] . " " : "") . $l["surname"])) ?></td>
                        <td><?= h($l["action"]) ?></td>
                        <td><?= h($l["ip_address"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once __DIR__ . "/../footer.php"; ?>

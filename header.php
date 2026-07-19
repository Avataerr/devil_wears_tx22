<?php
require_once __DIR__ . "/config/functions.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($page_title ?? "Devil Wears TX22") ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= h(site_url('css/style.css')) ?>?v=<?= filemtime(__DIR__ . '/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="<?= h(site_url('index.php')) ?>">
            <span class="brand-mark">DT</span>
            <span class="brand-text">Devil Wears<br>TX22</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <a class="nav-link" href="<?= h(site_url('index.php')) ?>">Store</a>
                <a class="nav-link" href="<?= h(site_url('cart.php')) ?>">Cart</a>
                <a class="nav-link" href="<?= h(site_url('about.php')) ?>">About</a>

                <?php if (is_logged_in()): ?>
                    <span class="nav-link disabled">Hello, <?= h(current_user_name()) ?></span>
                    <?php if (is_admin()): ?>
                        <a class="nav-link" href="<?= h(site_url('admin/index.php')) ?>">Admin</a>
                    <?php endif; ?>
                    <a class="nav-link" href="<?= h(site_url('logout.php')) ?>">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="<?= h(site_url('login.php')) ?>">Login</a>
                    <a class="nav-link" href="<?= h(site_url('register.php')) ?>">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<main class="container site-shell">

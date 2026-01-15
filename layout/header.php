<?php require_once __DIR__ . '/../config/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'ClassiNCM' ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS base -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/layout/assets/css/layout.css">

</head>
<body>

<header class="topbar">
    <button class="menu-toggle" onclick="toggleMenu()">
        <i class="bi bi-list"></i>
    </button>

    <span class="topbar-title">ClassiNCM</span>
</header>


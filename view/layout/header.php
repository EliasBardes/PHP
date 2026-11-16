<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Mon Compte';
$currentAction = $_GET['action'] ?? 'index';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'AUTO-MATCH — Votre prochain véhicule') ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-container">
        <a href="index.php" class="logo-brand">AUTO-MATCH</a>

        <nav class="header-nav">
            <?php if ($currentAction !== 'index'): ?>
                <a href="index.php" class="header-nav-link">Catalogue</a>
            <?php endif; ?>

            <?php if ($isLoggedIn): ?>
                <a href="index.php?action=account" class="btn-account">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span><?= htmlspecialchars($userName) ?></span>
                </a>
                <a href="index.php?action=logout" class="header-nav-link" style="font-size: 13px; opacity: 0.85;">Déconnexion</a>
            <?php else: ?>
                <a href="index.php?action=login" class="btn-account">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Mon Compte</span>
                </a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="main-content">

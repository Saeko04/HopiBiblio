<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>La Plume Digitale</title>
    <link rel="stylesheet" href="css/style.css">
    <?php if (!empty($css)): ?>
        <link rel="stylesheet" href="css/<?= htmlspecialchars($css) ?>">
    <?php endif; ?>
</head>
<body>
<header>
    <h1>La Plume Digitale</h1>
    <nav class="navigation">
    <a href="index.php?action=accueil">Accueil</a>
    <a href="index.php?action=chercher">Livres</a>
    
    <?php if (!empty($_SESSION['connected']) && $_SESSION['connected'] === true): ?>
        <a href="index.php?action=compteClient">Mon Compte</a>
        <?php if (in_array($_SESSION['role'], ['admin','bibliothecaire'])): ?>
            <a href="index.php?action=dashboardAdmin">Dashboard Admin</a>
        <?php else: ?>
            <a href="index.php?action=dashboardClient">Dashboard Client</a>
        <?php endif; ?>

        <a href="index.php?action=logout">Déconnexion (<?= htmlspecialchars($_SESSION['login']) ?>)</a>
    <?php else: ?>
        <a href="index.php?action=connexion">Connexion</a>
    <?php endif; ?>
    </nav>

    </div>
</header>


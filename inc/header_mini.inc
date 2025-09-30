<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
<?php if (!empty($css)): ?>
    <link rel="stylesheet" href="css/<?= htmlspecialchars($css) ?>">
<?php endif; ?>
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>La Plume Digitale - Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <?php if (!empty($css)): ?>
        <link rel="stylesheet" href="/css/<?= htmlspecialchars($css) ?>">
    <?php endif; ?>
</head>
<body>

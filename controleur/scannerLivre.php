<?php
$root = dirname(__DIR__,1);
require_once $root . '/modele/mesFonctionsAccesBDD.php';
require_once $root . '/autre/mesFonctionsDivers.php';
$pdo = connect();

// 1) Valider l’ID
if (empty($_GET['id']) || !is_numeric($_GET['id'])) die("ID invalide");
$id = (int)$_GET['id'];

// 2) Charger le titre du livre
$stmt = $pdo->prepare("SELECT titre FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC) ?: die("Livre introuvable");

// 3) Charger les scans
$scans = getScansByLivre($pdo, $id);

// 4) Afficher la vue (sans header/footer)
include $root . '/vue/vueScannerLivre.php';
?>

<link rel="stylesheet" href="../css/scannerLivre.css">

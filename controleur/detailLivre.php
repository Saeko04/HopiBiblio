<?php
$rootPath = dirname(__DIR__, 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once $rootPath . '/modele/mesFonctionsAccesBDD.php';

if (!function_exists('connect')) {
    die('ERREUR: Fonctions de base de données non chargées');
}

$pdo = connect();
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de livre invalide.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT id, titre, auteur, date_sortie, resume, image FROM livres WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livre) {
    die("Livre non trouvé.");
}
$css = 'detailLivre.css'; // CSS spécifique pour la page détail-livre
include __DIR__ . '/../vue/vueDetailLivre.php';
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

$retour = $_GET['retour'] ?? 'liste';

switch($retour) {
    case 'mes-emprunts':
        $retourUrl = 'index.php?action=dashboardClient';
        $retourTexte = 'Retour à mes emprunts';
        break;
    default:
        $retourUrl = 'index.php?action=chercher';
        $retourTexte = 'Retour à la liste';
        break;
}

$css = 'detailLivre.css'; // CSS spécifique pour la page détail-livre
// Vérifier si le livre est déjà emprunté (date_retour IS NULL ou date_retour > NOW())
$isEmprunte = false;
$dateRetourPrevue = null;
$check = $pdo->prepare('SELECT date_retour FROM emprunts WHERE id_livre = ? AND (date_retour IS NULL OR date_retour > NOW()) ORDER BY date_emprunt DESC LIMIT 1');
$check->execute([$id]);
$rowEmprunt = $check->fetch(PDO::FETCH_ASSOC);
if ($rowEmprunt) {
    $isEmprunte = true;
    $dateRetourPrevue = $rowEmprunt['date_retour'];
}
include __DIR__ . '/../vue/vueDetailLivre.php';
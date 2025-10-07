<?php
$rootPath = dirname(__DIR__, 1);
require_once $rootPath . '/modele/mesFonctionsAccesBDD.php';

session_start();

if (empty($_SESSION['connected']) || $_SESSION['connected'] !== true) {
    header('Location: index.php?action=connexion');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_emprunt'])) {
    header('Location: index.php?action=dashboardClient');
    exit;
}

$idEmprunt = (int) $_POST['id_emprunt'];
$idUtilisateur = (int) $_SESSION['id'];

$pdo = connect();
$succes = supprimerEmpruntById($pdo, $idEmprunt, $idUtilisateur);

if ($succes) {
    $_SESSION['flash_success'] = 'Livre retourné avec succès.';
} else {
    $_SESSION['flash_error'] = "Impossible d'enregistrer le retour.";
}

header('Location: index.php?action=dashboardClient');
exit;

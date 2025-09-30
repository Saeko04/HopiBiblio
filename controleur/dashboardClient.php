<?php
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';

// Vérifier si l'utilisateur est connecté et est un client
if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true || $_SESSION['role'] !== 'client') {
    header('Location: ../index.php?action=connexion');
    exit;
}

$pdo = connect();

$stmt = $pdo->prepare("
    SELECT l.titre, l.auteur, l.date_sortie 
    FROM livres l
    JOIN emprunts e ON l.id = e.id_livre
    WHERE e.id_utilisateur = ?
    ORDER BY e.date_emprunt DESC
");
$stmt->execute([$_SESSION['id']]);
$livresEmpruntes = $stmt->fetchAll(PDO::FETCH_ASSOC);


disconnect($pdo);

$css = 'dashboardClient.css';
//include_once __DIR__ . '/../inc/header.inc'; // header minimal
include  __DIR__ . '/../vue/vueDashboardClient.php';
?>

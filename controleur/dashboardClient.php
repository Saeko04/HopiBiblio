<?php
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';

// Vérifier si l'utilisateur est connecté et est un client
if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true || $_SESSION['role'] !== 'client') {
    header('Location: ../index.php?action=connexion');
    exit;
}

$pdo = connect();

$sql = "SELECT e.id AS id_emprunt, l.id AS id_livre, l.titre, l.auteur, l.date_sortie, l.image, e.date_emprunt, e.date_retour
    FROM livres l
    JOIN emprunts e ON l.id = e.id_livre
    WHERE e.id_utilisateur = ? AND (e.date_retour IS NULL OR e.date_retour > NOW())
    ORDER BY e.date_emprunt DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['id']]);
$livresEmpruntes = $stmt->fetchAll(PDO::FETCH_ASSOC);

disconnect($pdo);

$css = 'dashboardClient.css';
include  __DIR__ . '/../vue/vueDashboardClient.php';

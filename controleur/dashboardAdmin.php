<?php
session_start();
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';

// Vérifier que l'utilisateur est admin ou bibliothécaire
if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true || !in_array($_SESSION['role'], ['bibliothecaire','admin'])) {
    header('Location: index.php?action=connexion');
    exit;
}

$pdo = connect();

//include 'chercher.php';
// --- Ajouter un livre ---
if (isset($_POST['ajouter'])) {
    $titre = htmlspecialchars($_POST['titre']);
    $auteur = htmlspecialchars($_POST['auteur']);
    $date_sortie = $_POST['date_sortie'];
    $resume = htmlspecialchars($_POST['resume']);
    $cotation = $_POST['cotation'] ?? '';

    ajouterLivre($pdo, $titre, $auteur, $date_sortie, $resume, $cotation);
    header('Location: index.php?action=dashboardAdmin');
    exit;
}

// --- Supprimer un livre ---
if (isset($_POST['supprimer'])) {
    $id = intval($_POST['supprimer']);
    supprimerLivre($pdo, $id);
    header('Location: index.php?action=dashboardAdmin');
    exit;
}

// Récupérer tous les livres
$livres = getTousLesLivres($pdo);

// Définir le CSS spécifique
$css = 'dashboardAdmin.css';

// Inclure la vue
include __DIR__ . '/../vue/vueDashboardAdmin.php';

disconnect($pdo);
?>

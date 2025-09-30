<?php
// Pas de session_start ici si déjà dans header.inc
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';

// Vérifier que l'utilisateur est connecté et est admin ou bibliothécaire
if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true || !in_array($_SESSION['role'], ['bibliothecaire', 'admin'])) {
    header('Location: index.php?action=login');
    exit;
}

// Connexion à la BDD
$pdo = connect();

// --- AJOUTER UN LIVRE ---
if (isset($_POST['ajouter'])) {
    $titre = htmlspecialchars($_POST['titre']);
    $auteur = htmlspecialchars($_POST['auteur']);
    $date_sortie = $_POST['date_sortie'];
    $resume = htmlspecialchars($_POST['resume']);
    $cotation = $_POST['cotation'] ?? '';
    $image = null; // à gérer plus tard si tu veux ajouter l'image

    ajouterLivre($pdo, $titre, $auteur, $date_sortie, $resume, $cotation, $image);

    header('Location: index.php?action=dashboardAdmin');
    exit;
}

// --- SUPPRIMER UN LIVRE ---
if (isset($_POST['supprimer'])) {
    $id = intval($_POST['supprimer']);
    supprimerLivre($pdo, $id);

    header('Location: index.php?action=dashboardAdmin');
    exit;
}

// --- RÉCUPÉRER TOUS LES LIVRES ---
$livres = getTousLesLivres($pdo);

// Déconnexion de la BDD
disconnect($pdo);

// Définir le CSS spécifique
$css = 'dashboardAdmin.css';

// Inclure la vue
require __DIR__ . '/../vue/vueDashboardAdmin.php';
?>

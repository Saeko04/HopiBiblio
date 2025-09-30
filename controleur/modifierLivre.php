<?php
// Ne pas refaire session_start() si déjà fait dans header.inc
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';

// Vérifier que l'utilisateur est admin ou bibliothécaire
if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true || !in_array($_SESSION['role'], ['bibliothecaire','admin'])) {
    header('Location: index.php?action=connexion');
    exit;
}

$pdo = connect();

// Récupérer l'ID du livre à modifier
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("ID du livre non valide.");
}

// Récupérer les infos du livre
$livre = getLivreDetails($pdo, (int)$id);
if (!$livre) {
    die("Livre introuvable.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $auteur = htmlspecialchars($_POST['auteur']);
    $date_sortie = $_POST['date_sortie'];
    $resume = htmlspecialchars($_POST['resume']);
    $cotation = $_POST['cotation'] ?? '';

    mettreAJourLivre($pdo, (int)$id, $titre, $auteur, $date_sortie, $resume, $cotation);

    // Redirection vers le dashboard admin
    header('Location: index.php?action=dashboardAdmin');
    exit;
}

// Définir le CSS spécifique à cette page
$css = 'modifierLivre.css';

// Inclure la vue
include __DIR__ . '/../vue/vueModifierLivre.php';

disconnect($pdo);

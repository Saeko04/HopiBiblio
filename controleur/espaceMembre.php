<?php
// ne pas mettre session_start() ici, il est déjà dans header.inc

require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
    header('Location: index.php?action=login');
    exit;
}

// Connexion à la BDD
$conn = connect();

// Ajouter un livre
if (isset($_POST['ajouter'])) {
    $titre = htmlspecialchars($_POST['titre']);
    $auteur = htmlspecialchars($_POST['auteur']);
    $date_sortie = $_POST['date_sortie'];
    $resume = htmlspecialchars($_POST['resume']);

    $stmt = $conn->prepare('INSERT INTO livres (titre, auteur, date_sortie, resume) VALUES (?, ?, ?, ?)');
    $stmt->execute([$titre, $auteur, $date_sortie, $resume]);

    header('Location: index.php?action=espace-membre');
    exit;
}

// Supprimer un livre
if (isset($_POST['supprimer'])) {
    $id = intval($_POST['supprimer']);

    $stmt = $conn->prepare('DELETE FROM livres WHERE id = ?');
    $stmt->execute([$id]);

    header('Location: index.php?action=espace-membre');
    exit;
}

// Récupérer tous les livres
$stmt = $conn->query('SELECT * FROM livres ORDER BY id DESC');
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

disconnect($conn);

// Définir le CSS spécifique à cette page
$css = 'admin.css';

// Inclure la vue
require __DIR__ . '/../vue/vueEspaceMembre.php';

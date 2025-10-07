<?php
$rootPath = dirname(__DIR__, 1);
require_once $rootPath . '/modele/mesFonctionsAccesBDD.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['connected']) || $_SESSION['connected'] !== true) {
    // Non connecté -> redirection vers la page de connexion
    header('Location: index.php?action=connexion&message=connectez-vous-pour-emprunter');
    exit;
}
// Prefer POST data (from the form). Fallback to GET if necessary.
$idLivre = null;
$titre = null;
$auteur = null;
$from = 'chercher';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idLivre = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $titre = $_POST['titre'] ?? null;
    $auteur = $_POST['auteur'] ?? null;
    $from = $_POST['from'] ?? 'chercher';
} else {
    $idLivre = isset($_GET['id']) ? (int) $_GET['id'] : null;
    $from = $_GET['from'] ?? 'chercher';
}

if (empty($idLivre) || !is_numeric($idLivre)) {
    die('ID de livre invalide.');
}

$idUtilisateur = (int) $_SESSION['id'];

$pdo = connect();

// Vérifier que le livre existe (et récupérer titre/auteur si manquant)
$stmt = $pdo->prepare('SELECT id, titre, auteur FROM livres WHERE id = ?');
$stmt->execute([$idLivre]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$livre) {
    die('Livre non trouvé.');
}

if (empty($titre)) {
    $titre = $livre['titre'];
}
if (empty($auteur)) {
    $auteur = $livre['auteur'];
}

$check = $pdo->prepare('SELECT COUNT(*) FROM emprunts WHERE id_livre = ? AND (date_retour IS NULL OR date_retour > NOW())');
$check->execute([$idLivre]);
$count = (int) $check->fetchColumn();
if ($count > 0) {
    $_SESSION['flash_error'] = 'Ce livre est déjà emprunté par un autre utilisateur.';
    if ($from === 'mes-emprunts') {
        header('Location: index.php?action=dashboardClient');
    } else {
        header('Location: index.php?action=chercher');
    }
    exit;
}

// Ajouter l'emprunt
$succes = ajouterEmprunt($pdo, $idUtilisateur, $idLivre);

// Préparer message flash
if ($succes) {
    $_SESSION['flash_success'] = "Emprunt enregistré : {$titre} (ID livre: {$idLivre}) pour l'utilisateur ID {$idUtilisateur}.";
} else {
    $_SESSION['flash_error'] = "Impossible d'enregistrer l'emprunt.";
}

// Redirection après emprunt
if ($from === 'mes-emprunts') {
    header('Location: index.php?action=dashboardClient');
} else {
    header('Location: index.php?action=chercher');
}
exit;

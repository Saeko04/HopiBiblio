<?php
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';

// Vérifier que l'utilisateur est connecté et est admin ou bibliothécaire
if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true || !in_array($_SESSION['role'], ['bibliothecaire', 'admin'])) {
    header('Location: index.php?action=login');
    exit;
}

// Connexion à la BDD
$pdo = connect();

// Récupérer les filtres depuis GET
$titre    = trim($_GET['titre'] ?? '');
$auteur   = trim($_GET['auteur'] ?? '');
$cotation = trim($_GET['cotation'] ?? '');
$annee    = trim($_GET['annee'] ?? '');

// Charger la liste des cotations pour le <select>
$cotationsDisponibles = $pdo->query('SELECT DISTINCT cotation FROM livres ORDER BY cotation')->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les livres selon les filtres
if ($titre !== '' || $auteur !== '' || $cotation !== '' || $annee !== '') {
    $livres = chercherLivres($pdo, $titre, $auteur, $cotation, $annee);
} else {
    $livres = getTousLesLivres($pdo);
}



// --- AJOUTER UN LIVRE ---
if (isset($_POST['ajouter'])) {
    $titre = htmlspecialchars($_POST['titre']);
    $auteur = htmlspecialchars($_POST['auteur']);
    $date_sortie = $_POST['date_sortie'];
    $resume = htmlspecialchars($_POST['resume']);
    $cotation = $_POST['cotation'] ?? '';

    // Gestion du fichier image
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $message = "❌ Veuillez sélectionner une image.";
    }

    if ($image !== null) {
        ajouterLivre($pdo, $titre, $auteur, $date_sortie, $resume, $cotation, $image);
        header('Location: index.php?action=dashboardAdmin');
        exit;
    }
}

// --- SUPPRIMER UN LIVRE ---
if (isset($_POST['supprimer'])) {
    $idLivre = intval($_POST['supprimer']);
    supprimerLivre($pdo, $idLivre);
    header('Location: index.php?action=dashboardAdmin');
    exit;
}

// --- LIVRES NON RENDUS ---
$livresNonRendus = getLivresNonRendus($pdo);

//creation d'une sauvegarde

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sauvegarde'])) {
    // Infos base de données (à adapter)
    $host = 'localhost';
    $db = 'dblogin4222';
    $user = 'login4222';
    $pass = 'ydLugQuPXmChIwb';

    $sauvegarde = getSauvegarde($host, $db, $user, $pass);
}


// Déconnexion
disconnect($pdo);

// CSS spécifique
$css = 'dashboardAdmin.css';

// Vue
require __DIR__ . '/../vue/vueDashboardAdmin.php';
?>

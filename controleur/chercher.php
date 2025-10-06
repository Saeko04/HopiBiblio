<?php
$rootPath = dirname(__DIR__, 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once $rootPath . '/modele/mesFonctionsAccesBDD.php';

$pdo = connect();

// Récupérer les filtres depuis GET
$titre    = trim($_GET['titre'] ?? '');
$auteur   = trim($_GET['auteur'] ?? '');
$genreSelectionne = trim($_GET['genre'] ?? '');
$annee    = trim($_GET['date_sortie'] ?? '');
$cotationSelectionnee = trim($_GET['cotation'] ?? '');
$id       = trim($_GET['id'] ?? '');

// Charger les listes pour les <select>
$genresDisponibles = $pdo->query('SELECT id_cotation, nom FROM genres')->fetchAll(PDO::FETCH_ASSOC);
$cotationsDisponibles = $pdo->query('SELECT DISTINCT cotation FROM livres ORDER BY cotation')->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les livres selon les filtres
if ($titre !== '' || $auteur !== '' || $genreSelectionne !== '' || $annee !== '' || $cotationSelectionnee !== '' || $id !== '') {
    $livres = chercherLivres($pdo, $titre, $auteur, $cotationSelectionnee, $annee, $id, $genreSelectionne);
} else {
    $livres = getTousLesLivres($pdo);
}

include 'vue/vueChercher.php';

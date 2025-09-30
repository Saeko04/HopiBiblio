<?php
$rootPath = dirname(__DIR__, 1);
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once $rootPath . '/modele/mesFonctionsAccesBDD.php';

if (!function_exists('connect')) {
    die('ERREUR: Fonctions de base de données non chargées');
}

$pdo = connect();

$titre = $_GET['titre'] ?? '';
$auteur = $_GET['auteur'] ?? '';
$genreSelectionne = $_GET['genre'] ?? '';
$dateSortie = $_GET['date_sortie'] ?? '';
$cotationSelectionnee = $_GET['cotation'] ?? '';
$id = $_GET['id'] ?? '';

$livres = chercherLivres($pdo, $titre, $auteur, $genreSelectionne, $dateSortie, $cotationSelectionnee);

// Ces données sont utilisées pour les listes déroulantes dans le formulaire
$genresDisponibles = $pdo->query('SELECT id_cotation, nom FROM genres')->fetchAll();
$cotationsDisponibles = $pdo->query('SELECT DISTINCT cotation FROM livres ORDER BY cotation')->fetchAll();
$anneesDisponibles = $pdo->query('SELECT DISTINCT YEAR(date_sortie) as annee FROM livres ORDER BY annee DESC')->fetchAll();
$livres = chercherLivres($pdo, $titre, $auteur, $genreSelectionne, $dateSortie, $cotationSelectionnee, $id);

include 'vue/vueChercher.php';

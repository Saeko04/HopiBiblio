<?php
include_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';
$pdo = connect();

// Récupère le critère de tri passé en GET, sinon 'cotation'
$tri = $_GET['tri'] ?? 'cotation';
$livres = getTousLesLivres($pdo, $tri);

require __DIR__ . '/../vue/vuelivres.php';
?>

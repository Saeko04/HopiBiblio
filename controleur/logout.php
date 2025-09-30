<?php
session_start();       // Démarre la session si ce n’est pas déjà fait
$_SESSION = [];         // Vide toutes les variables de session
session_destroy();      // Détruit la session

// Redirige vers la page d'accueil
header('Location: index.php?action=accueil');
exit;

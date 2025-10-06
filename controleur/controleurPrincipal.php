<?php

function controleurPrincipal($action)
{
    $lesActions = array();
    $lesActions['defaut'] = 'accueil.php';
    $lesActions['chercher'] = 'chercher.php';
    $lesActions['accès'] = 'acces.php';
    $lesActions['contact'] = 'contact.php';
    $lesActions['connexion'] = 'connexion.php';
    $lesActions['logout'] = 'logout.php';
    
    $lesActions['detail-livre'] = 'detailLivre.php';
    $lesActions['modifier-livre'] = 'modifierLivre.php';
    $lesActions['scanner'] = 'scannerLivre.php';
    $lesActions['dashboardClient'] = 'dashboardClient.php';
    $lesActions['dashboardAdmin'] = 'dashboardAdmin.php';
    $lesActions['compteClient'] = 'compteClient.php';




    if (array_key_exists($action, $lesActions)) {
        return $lesActions[$action];
    } else {
        return $lesActions['defaut'];
    }
}

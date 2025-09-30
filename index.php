<?php
include "controleur/controleurPrincipal.php";

$action = $_GET['action'] ?? 'defaut';
$fichier = controleurPrincipal($action);

// définir $css selon l'action (ou laisser vide)
$css = '';
switch ($action) {
    case 'modifier-livre':
        $css = 'modifierLivre.css';
        break;
    case 'detail-livre':
        $css = 'detailLivre.css';
        break;
    case 'chercher':
        $css = 'chercher.css';
        break;
    case 'livres':
        $css = 'livres.css';
        break;
    case 'contact':
        $css = 'contact.css';
        break;
    case 'login':
        $css = 'login.css';
        break;
    case 'dashboardClient':
        $css = 'dashboardClient.css';
        break;
    case 'dashboardAdmin':
        $css = 'dashboardAdmin.css';
        break;
}




// maintenant inclure header qui utilisera la variable $css
include "inc/header.inc";

// inclure le contrôleur (qui inclura la vue)
include "controleur/$fichier";

include "inc/footer.inc";

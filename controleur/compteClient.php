
<?php
// ... Affiche les informations du client connecté
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';

// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = connect();

echo '<h1>Vos informations :</h1>';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true || empty($_SESSION['id'])) {
    // L'utilisateur n'est pas connecté
    echo '<p>Vous n\'êtes pas connecté.</p>';
    disconnect($pdo);
    return;
}

// Récupérer les informations de l'utilisateur connecté
$userId = (int) $_SESSION['id'];
$user = getUserById($pdo, $userId);

if (!$user) {
    echo '<p>Utilisateur non trouvé.</p>';
    disconnect($pdo);
    return;
}

// Si formulaire de modification soumis
// Si formulaire de modification soumis (formulaire global)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['prenom'], $_POST['login'])) {
    $newNom = trim($_POST['nom']);
    $newPrenom = trim($_POST['prenom']);
    $newLogin = trim($_POST['login']);

    $errors = [];
    $successes = [];

    // validation basique
    if ($newNom === '') {
        $errors[] = 'Le nom ne peut pas être vide.';
    }
    if ($newPrenom === '') {
        $errors[] = 'Le prénom ne peut pas être vide.';
    }
    if ($newLogin === '') {
        $errors[] = 'Le login ne peut pas être vide.';
    }

    // Vérifier le login si pas d'erreur de champ
    if (empty($errors)) {
        if ($newLogin !== $user['login']) {
            // login modifié -> vérifier s'il est déjà pris par un autre utilisateur
            $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE login = ?');
            $stmt->execute([$newLogin]);
            $row = $stmt->fetch();
            if ($row && (int)$row['id'] !== $userId) {
                $errors[] = 'Ce login est déjà utilisé par un autre utilisateur.';
            }
        }
    }

    // Si pas d'erreurs, effectuer les mises à jour nécessaires
    if (empty($errors)) {
        // Mettre à jour chaque champ qui a changé
        $fieldsToUpdate = [];
        if ($newNom !== $user['nom']) {
            if (updateUserField($pdo, $userId, 'nom', $newNom)) {
                $successes[] = 'Nom mis à jour.';
            } else {
                $errors[] = 'Erreur lors de la mise à jour du nom.';
            }
        }
        if ($newPrenom !== $user['prenom']) {
            if (updateUserField($pdo, $userId, 'prenom', $newPrenom)) {
                $successes[] = 'Prénom mis à jour.';
            } else {
                $errors[] = 'Erreur lors de la mise à jour du prénom.';
            }
        }
        if ($newLogin !== $user['login']) {
            if (updateUserField($pdo, $userId, 'login', $newLogin)) {
                $successes[] = 'Login mis à jour.';
            } else {
                $errors[] = 'Erreur lors de la mise à jour du login.';
            }
        }

        // rafraîchir les données utilisateur après mise à jour
        if (!empty($successes)) {
            $user = getUserById($pdo, $userId);
        }
    }

    // Afficher messages
    foreach ($errors as $err) {
        echo '<p style="color:red;">' . htmlspecialchars($err) . '</p>';
    }
    foreach ($successes as $s) {
        echo '<p style="color:green;">' . htmlspecialchars($s) . '</p>';
    }
}


// Gestion export JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exportDonnees'])) {
    $donnees = [
        'id' => $user['id'],
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
        'login' => $user['login'],
        'email' => $user['email'] ?? '',
    ];
    $json = json_encode($donnees, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $filename = 'donnees_utilisateur_' . $user['id'] . '.json';
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo $json;
    disconnect($pdo);
    exit;
}

// Formulaire principal
echo '<form method="post">';
echo '<p><label><strong>Nom :</strong><br><input type="text" name="nom" value="' . htmlspecialchars($user['nom']) . '"></label></p>';
echo '<p><label><strong>Prénom :</strong><br><input type="text" name="prenom" value="' . htmlspecialchars($user['prenom']) . '"></label></p>';
echo '<p><label><strong>Login :</strong><br><input type="text" name="login" value="' . htmlspecialchars($user['login']) . '"></label></p>';
echo '<p><button type="submit">Valider</button></p>';
echo '</form>';

// Formulaire export JSON
echo '<form method="post" style="margin-top:0;">';
echo '<input type="hidden" name="exportDonnees" value="1">';
echo '<p><button type="submit" name="exportDonneesBtn">Récupérer mes données</button></p>';
echo '</form>';

disconnect($pdo);

?>






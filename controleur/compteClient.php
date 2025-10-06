
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['field'], $_POST['value'])) {
    $field = $_POST['field'];
    $value = trim($_POST['value']);

    // validation basique
    if ($value === '') {
        echo '<p style="color:red;">La valeur ne peut pas être vide.</p>';
    } else {
        // si on modifie le login, vérifier qu'il n'existe pas déjà pour un autre utilisateur
        if ($field === 'login') {
            // vérifier existence
            if (loginExiste($pdo, $value)) {
                // si le login appartient au même utilisateur, autoriser
                $existing = $pdo->prepare('SELECT id FROM utilisateurs WHERE login = ?');
                $existing->execute([$value]);
                $row = $existing->fetch();
                if ($row && (int)$row['id'] !== $userId) {
                    echo '<p style="color:red;">Ce login est déjà utilisé.</p>';
                } else {
                    if (updateUserField($pdo, $userId, $field, $value)) {
                        echo '<p style="color:green;">Mis à jour avec succès.</p>';
                        // refresh user
                        $user = getUserById($pdo, $userId);
                    } else {
                        echo '<p style="color:red;">Erreur lors de la mise à jour.</p>';
                    }
                }
            } else {
                if (updateUserField($pdo, $userId, $field, $value)) {
                    echo '<p style="color:green;">Mis à jour avec succès.</p>';
                    $user = getUserById($pdo, $userId);
                } else {
                    echo '<p style="color:red;">Erreur lors de la mise à jour.</p>';
                }
            }
        } else {
            // nom ou prenom
            if (updateUserField($pdo, $userId, $field, $value)) {
                echo '<p style="color:green;">Mis à jour avec succès.</p>';
                $user = getUserById($pdo, $userId);
            } else {
                echo '<p style="color:red;">Erreur lors de la mise à jour.</p>';
            }
        }
    }
}

// affichage des données de l'utilisateur avec boutons de modification
echo '<form method="post" style="display:inline">';
echo '<p><strong>Nom :</strong> ' . htmlspecialchars($user['nom']) . ' <button type="button" onclick="document.getElementById(\'edit-nom\').style.display=\'inline-block\'">Modifier</button></p>';
echo '</form>';

echo '<div id="edit-nom" style="display:none;margin-bottom:8px">';
echo '<form method="post">';
echo '<input type="hidden" name="field" value="nom">';
echo '<input type="text" name="value" value="' . htmlspecialchars($user['nom']) . '">';
echo '<button type="submit">Enregistrer</button> ';
echo '<button type="button" onclick="this.parentNode.parentNode.style.display=\'none\'">Annuler</button>';
echo '</form>';
echo '</div>';

echo '<p><strong>Prénom :</strong> ' . htmlspecialchars($user['prenom']) . ' <button type="button" onclick="document.getElementById(\'edit-prenom\').style.display=\'inline-block\'">Modifier</button></p>';
echo '<div id="edit-prenom" style="display:none;margin-bottom:8px">';
echo '<form method="post">';
echo '<input type="hidden" name="field" value="prenom">';
echo '<input type="text" name="value" value="' . htmlspecialchars($user['prenom']) . '">';
echo '<button type="submit">Enregistrer</button> ';
echo '<button type="button" onclick="this.parentNode.parentNode.style.display=\'none\'">Annuler</button>';
echo '</form>';
echo '</div>';

echo '<p><strong>Login :</strong> ' . htmlspecialchars($user['login']) . ' <button type="button" onclick="document.getElementById(\'edit-login\').style.display=\'inline-block\'">Modifier</button></p>';
echo '<div id="edit-login" style="display:none;margin-bottom:8px">';
echo '<form method="post">';
echo '<input type="hidden" name="field" value="login">';
echo '<input type="text" name="value" value="' . htmlspecialchars($user['login']) . '">';
echo '<button type="submit">Enregistrer</button> ';
echo '<button type="button" onclick="this.parentNode.parentNode.style.display=\'none\'">Annuler</button>';
echo '</form>';
echo '</div>';

disconnect($pdo);

?>






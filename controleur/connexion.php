<?php
session_start();
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';
$pdo = connect();
$_SESSION['id'] = $user['id'];
$message = '';
$css = 'login.css'; // 🔹 définir le CSS avant l'inclusion du header

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'inscription') {
        // --- INSCRIPTION ---
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $login = trim($_POST['login']);
        $password = $_POST['password'];
        $accept = $_POST['accept'] ?? '';

        if ($accept !== 'on') {
            $message = "❌ Vous devez accepter la politique de confidentialité pour créer un compte.";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{12,}$/', $password)) {
            $message = "❌ Le mot de passe doit contenir au minimum 12 caractères, avec 1 majuscule, 1 chiffre et 1 caractère spécial.";
        } elseif (!loginExiste($pdo, $login)) {
            $role = 'client'; // rôle automatique
            creerUtilisateur($pdo, $nom, $prenom, $login, $password, $role);

            // Connexion automatique après inscription
            $user = verifierUtilisateur($pdo, $login, $password);
            $_SESSION['connected'] = true;
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];

            // Redirection vers dashboard client
            header('Location: ../index.php?action=dashboardClient');
            exit;
        } else {
            $message = "❌ Ce login existe déjà.";
        }
    } else {
        // --- CONNEXION ---
        $login = trim($_POST['login']);
        $password = $_POST['password'];

        $user = verifierUtilisateur($pdo, $login, $password);
        if ($user) {
            $_SESSION['connected'] = true;
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];

            if ($user['role'] === 'bibliothecaire' || $user['role'] === 'admin') {
                header('Location: index.php?action=dashboardAdmin');
                exit;
            }
             else {
                header('Location: ../index.php?action=dashboardClient');
                exit;
            }
            exit;
        } else {
            $message = "❌ Login ou mot de passe incorrect.";
        }
    }
}

disconnect($pdo);

// Inclure la vue après avoir défini le CSS
include __DIR__ . '/../vue/vueConnexion.php';

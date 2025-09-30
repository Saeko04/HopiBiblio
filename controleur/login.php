<?php
session_start();
require_once __DIR__ . '/../modele/mesFonctionsAccesBDD.php';
$conn = connect();

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        $_SESSION['connected'] = true;
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_nom'] = $user['nom'];

        header("Location: espaceMembre.php");
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}

disconnect($conn);

require __DIR__ . '/../vue/vuelogin.php';

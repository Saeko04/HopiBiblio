<?php
include_once __DIR__ . "/../modele/utilisateur.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $login = trim($_POST["login"]);
    $mdp = $_POST["motdepasse"];
    $role = $_POST["role"];

    try {
        ajouterUtilisateur($pdo, $nom, $prenom, $login, $mdp, $role);
        $message = "✅ Inscription réussie, vous pouvez vous connecter.";
    } catch (PDOException $e) {
        $message = "❌ Identifiant déjà pris.";
    }
}

include __DIR__ . "/../vue/inscription.php";

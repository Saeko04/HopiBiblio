<?php
function ajouterUtilisateur($pdo, $nom, $prenom, $login, $motdepasse, $role = "client") {
    $sql = "INSERT INTO utilisateurs (nom, prenom, login, password, role)
            VALUES (:nom, :prenom, :login, :mdp, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":nom" => $nom,
        ":prenom" => $prenom,
        ":login" => $login,
        ":mdp" => password_hash($motdepasse, PASSWORD_DEFAULT),
        ":role" => $role
    ]);
}

function getUtilisateurParLogin($pdo, $login) {
    $sql = "SELECT * FROM utilisateurs WHERE login = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":login" => $login]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

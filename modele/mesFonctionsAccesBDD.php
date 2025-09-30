<?php

// --- Connexion à la BDD ---
function connect(): PDO {
    $host = 'localhost';
    $db = 'dblogin4222';
    $user = 'login4222';
    $pass = 'ydLugQuPXmChIwb';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function disconnect(&$pdo) {
    $pdo = null;
}

// --- CRUD LIVRES ---

// Récupérer tous les livres avec tri (titre, auteur, cotation)
function getTousLesLivres(PDO $pdo, string $tri = 'cotation'): array {
    $colonnesAutorisees = ['cotation', 'titre', 'auteur'];
    if (!in_array($tri, $colonnesAutorisees)) {
        $tri = 'cotation';
    }

    $sql = "SELECT id, cotation, titre, auteur, date_sortie, resume 
            FROM livres 
            ORDER BY $tri ASC";
    return $pdo->query($sql)->fetchAll();
}


// Récupérer un livre précis
function getLivreDetails(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

// Ajouter un livre
function ajouterLivre(PDO $pdo, string $titre, string $auteur, string $date_sortie, string $resume, string $cotation, $image = null): void {
    $sql = "INSERT INTO livres (titre, auteur, date_sortie, resume, cotation, image) 
            VALUES (:titre, :auteur, :date_sortie, :resume, :cotation, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titre' => $titre,
        ':auteur' => $auteur,
        ':date_sortie' => $date_sortie,
        ':resume' => $resume,
        ':cotation' => $cotation,
        ':image' => $image
    ]);
}

// Supprimer un livre
function supprimerLivre(PDO $pdo, int $id): void {
    $stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
    $stmt->execute([$id]);
}

// Modifier un livre
function mettreAJourLivre(PDO $pdo, int $id, string $titre, string $auteur, string $date_sortie, string $resume, string $cotation, $image = null): void {
    $sql = "UPDATE livres 
            SET titre = :titre, auteur = :auteur, date_sortie = :date_sortie, resume = :resume, cotation = :cotation, image = :image 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':titre' => $titre,
        ':auteur' => $auteur,
        ':date_sortie' => $date_sortie,
        ':resume' => $resume,
        ':cotation' => $cotation,
        ':image' => $image,
        ':id' => $id
    ]);
}

// --- RECHERCHE / FILTRES ---

function chercherLivres(PDO $pdo, string $titre = '', string $auteur = '', string $cotation = '', string $annee = ''): array {
    $sql = "SELECT livres.*, genres.nom AS nom_genre
            FROM livres
            LEFT JOIN genres ON livres.cotation = genres.id_cotation
            WHERE 1=1";
    $params = [];

    if ($titre !== '') {
        $sql .= " AND livres.titre LIKE :titre";
        $params[':titre'] = "%$titre%";
    }

    if ($auteur !== '') {
        $sql .= " AND livres.auteur LIKE :auteur";
        $params[':auteur'] = "%$auteur%";
    }

    if ($cotation !== '') {
        $sql .= " AND livres.cotation = :cotation";
        $params[':cotation'] = $cotation;
    }

    if ($annee !== '') {
        $sql .= " AND YEAR(livres.date_sortie) = :annee";
        $params[':annee'] = $annee;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}


function getAnneesDisponibles(PDO $pdo): array {
    $stmt = $pdo->query("SELECT DISTINCT YEAR(date_sortie) AS annee FROM livres ORDER BY annee DESC");
    return $stmt->fetchAll();
}

function cotationsDisponibles(PDO $pdo): array {
    $stmt = $pdo->query("SELECT DISTINCT cotation FROM livres ORDER BY cotation ASC");
    return $stmt->fetchAll();
}

// Vérifie si un login existe
function loginExiste(PDO $pdo, string $login): bool {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    return $stmt->fetchColumn() > 0;
}

// Crée un nouvel utilisateur
function creerUtilisateur(PDO $pdo, string $nom, string $prenom, string $login, string $password, string $role = 'client'): bool {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, login, password, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nom, $prenom, $login, $hash, $role]);
}

// Vérifie le login et retourne l'utilisateur
function verifierUtilisateur(PDO $pdo, string $login, string $password): ?array {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return null;
}



?>

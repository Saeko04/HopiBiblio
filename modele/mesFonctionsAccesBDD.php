<?php

// --- Connexion à la BDD ---
function connect(): PDO
{
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

function disconnect(&$pdo)
{
    $pdo = null;
}

// --- CRUD LIVRES ---

// Récupérer tous les livres avec tri (titre, auteur, cotation)
function getTousLesLivres(PDO $pdo, string $tri = 'id'): array
{
    $colonnesAutorisees = ['id', 'cotation', 'titre', 'auteur'];
    if (!in_array($tri, $colonnesAutorisees)) {
        $tri = 'id';
    }

    $sql = "SELECT id, cotation, titre, auteur, resume, date_sortie, image FROM livres ORDER BY id ASC";

    return $pdo->query($sql)->fetchAll();
}



// Récupérer un livre précis
function getLivreDetails(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

// Ajouter un livre
function ajouterLivre(PDO $pdo, string $titre, string $auteur, string $date_sortie, string $resume, string $cotation, $image = null): void
{
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
function supprimerLivre(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare("DELETE FROM livres WHERE id = ?");
    $stmt->execute([$id]);
}

// Modifier un livre
function mettreAJourLivre(PDO $pdo, int $id, string $titre, string $auteur, string $date_sortie, string $resume, string $cotation, $image = null): void
{
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
function chercherLivres(PDO $pdo, string $titre = '', string $auteur = '', string $cotation = '', string $annee = '', string $id = '', string $genre = ''): array
{
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

    if ($id !== '') {
        $sql .= " AND livres.id = :id";
        $params[':id'] = $id;
    }

    if ($genre !== '') {
        $sql .= " AND livres.cotation = :genre";
        $params[':genre'] = $genre;
    }

    $sql .= " ORDER BY livres.id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




function getAnneesDisponibles(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT DISTINCT YEAR(date_sortie) AS annee FROM livres ORDER BY annee DESC");
    return $stmt->fetchAll();
}

function cotationsDisponibles(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT DISTINCT cotation FROM livres ORDER BY cotation ASC");
    return $stmt->fetchAll();
}

// Vérifie si un login existe
function loginExiste(PDO $pdo, string $login): bool
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    return $stmt->fetchColumn() > 0;
}

// Crée un nouvel utilisateur
function creerUtilisateur(PDO $pdo, string $nom, string $prenom, string $login, string $password, string $role = 'client'): bool
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, login, password, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nom, $prenom, $login, $hash, $role]);
}

// Vérifie le login et retourne l'utilisateur
function verifierUtilisateur(PDO $pdo, string $login, string $password): ?array
{
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return null;
}

// Récupérer un utilisateur par son ID
function getUserById(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT id, nom, prenom, login FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

// Mettre à jour un champ (nom, prenom ou login) d'un utilisateur
function updateUserField(PDO $pdo, int $id, string $field, string $value): bool {
    $allowed = ['nom', 'prenom', 'login'];
    if (!in_array($field, $allowed, true)) {
        return false;
    }
    $sql = "UPDATE utilisateurs SET $field = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$value, $id]);
}
function getLivresNonRendus($pdo, $dureeEmprunt = 14)
{
    $sql = "SELECT e.id, l.titre, l.auteur, e.date_emprunt, 
                   u.nom, u.prenom,
                   DATE_ADD(e.date_emprunt, INTERVAL :duree DAY) AS date_limite,
                   DATEDIFF(CURDATE(), DATE_ADD(e.date_emprunt, INTERVAL :duree DAY)) AS jours_depasse
            FROM emprunts e
            JOIN livres l ON e.id_livre = l.id
            JOIN utilisateurs u ON e.id_utilisateur = u.id
            WHERE e.date_retour IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':duree' => $dureeEmprunt]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function getEmpruntsUtilisateur(PDO $pdo, int $idUtilisateur): array
{
    $sql = "SELECT l.titre, 
                   l.auteur, 
                   e.date_emprunt, 
                   e.date_limite, 
                   e.date_retour,
                   DATEDIFF(CURDATE(), e.date_limite) AS jours_retard
            FROM emprunts e
            JOIN livres l ON e.id_livre = l.id
            WHERE e.id_utilisateur = ?
            ORDER BY e.date_emprunt DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idUtilisateur]);
    return $stmt->fetchAll();
}

// --- STATISTIQUES ---

//Nombre total de livres
function getNombreTotalLivres(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM livres");
    return (int) $stmt->fetchColumn();
}

//Nombre total d'emprunts en cours
function getNombreEmpruntsEnCours(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM emprunts WHERE date_retour IS NULL");
    return (int) $stmt->fetchColumn();
}

//Nombre d'emprunts par livre
function getFrequenceEmpruntParLivre(PDO $pdo): array {
    $sql = "SELECT l.titre, COUNT(e.id) AS nb_emprunts
            FROM livres l
            LEFT JOIN emprunts e ON l.id = e.id_livre
            GROUP BY l.id
            ORDER BY nb_emprunts DESC";
    return $pdo->query($sql)->fetchAll();
}


// Ajouter un emprunt
function ajouterEmprunt(PDO $pdo, int $idUtilisateur, int $idLivre, int $dureeEmprunt = 14): bool {
    $dateEmprunt = date('Y-m-d');
    $dateLimite = date('Y-m-d', strtotime("+$dureeEmprunt days"));
    $sql = "INSERT INTO emprunts (id_utilisateur, id_livre, date_emprunt, date_limite) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$idUtilisateur, $idLivre, $dateEmprunt, $dateLimite]);
}


// Marquer un emprunt comme rendu (retour)
function supprimerEmpruntById(PDO $pdo, int $idEmprunt, int $idUtilisateur): bool {
    $sql = "UPDATE emprunts SET date_retour = NOW() WHERE id = ? AND id_utilisateur = ? AND date_retour IS NULL";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$idEmprunt, $idUtilisateur]);
}

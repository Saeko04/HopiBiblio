<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Liste des livres</title>
    <link rel="stylesheet" type="text/css" href="css/livre.css">
</head>

<body>
    <!-- Formulaire de tri -->
    <form method="post" action="index.php?action=livres">
        <label for="tri">Trier par :</label>
        <select name="tri" id="tri" onchange="this.form.submit()">
            <option value="cotation" <?= ($_POST['tri'] ?? '') === 'cotation' ? 'selected' : '' ?>>Cotation</option>
            <option value="titre" <?= ($_POST['tri'] ?? '') === 'titre' ? 'selected' : '' ?>>Titre</option>
            <option value="auteur" <?= ($_POST['tri'] ?? '') === 'auteur' ? 'selected' : '' ?>>Auteur</option>
        </select>
    </form>
    <h1>Voici l'ensemble des livres que l'on possède</h1>

    <a href="index.php?action=detail-livre&id=<?= $livre['id'] ?>">
        <h3><?= htmlspecialchars($livre['titre']) ?></h3>
    </a>


    <div class="livre-container">
        <?php

        if (isset($_POST['tri']) && in_array($_POST['tri'], ['cotation', 'titre', 'auteur'])) {
            usort($livres, function ($a, $b) {
                return strcmp($a[$_POST['tri']], $b[$_POST['tri']]);
            });
        }

        // Afficher les livres
        foreach ($livres as $livre) {
            echo "<div class='livre'>";
            echo "<a href='index.php?action=detail-livre&id=" . urlencode($livre['id']) . "'>";
            echo '<strong>Titre :</strong> ' . htmlspecialchars($livre['titre']) . '</a><br>';
            echo '<strong>Cotation :</strong> ' . htmlspecialchars($livre['cotation']) . '<br>';
            echo '<strong>Auteur :</strong> ' . htmlspecialchars($livre['auteur']) . '<br>';
            echo '<strong>Résumé :</strong> ' . htmlspecialchars($livre['resume']) . '<br>';
            echo '</div>';
        }
        ?>
    </div>
</body>

</html>

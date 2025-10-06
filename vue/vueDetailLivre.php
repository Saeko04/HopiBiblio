<div class="container">
    <!-- Colonne gauche : image -->
    <?php if (!empty($livre['image'])) : ?>
        <div class="left-column">
            <?php if (!empty($livre['image'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($livre['image']) ?>" alt="Couverture du livre">
            <?php else: ?>
                <img src="img/placeholder.png" alt="Pas d'image disponible">
            <?php endif; ?>
        </div>

    <?php endif; ?>

    <!-- Colonne droite : texte -->
    <div class="right-column">
        <h1><?= htmlspecialchars($livre['titre']) ?></h1>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
        <p><strong>Année :</strong> <?= htmlspecialchars($livre['date_sortie']) ?></p>
        <p><strong>Description :</strong></p>
        <p><?= nl2br(htmlspecialchars($livre['resume'])) ?></p>

        <?php
        $from = $_GET['from'] ?? 'chercher'; // valeur par défaut

        if ($from === 'mes-emprunts') {
            $retourTexte = 'Retour à mes emprunts';
            $retourUrl = 'index.php?action=dashboardClient';
        } else {
            $retourTexte = 'Retour à la liste';
            $retourUrl = 'index.php?action=chercher';
        }
        ?>
        <a href="<?= $retourUrl ?>" class="btn"><?= $retourTexte ?></a>


    </div>
</div>
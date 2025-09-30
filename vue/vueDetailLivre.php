<div class="container">
    <!-- Colonne gauche : image -->
    <?php if (!empty($livre['image'])) : ?>
        <div class="left-column">
            <img src="data:image/jpeg;base64,<?= base64_encode($livre['image']) ?>" alt="Couverture du livre">
        </div>
    <?php endif; ?>

    <!-- Colonne droite : texte -->
    <div class="right-column">
        <h1><?= htmlspecialchars($livre['titre']) ?></h1>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
        <p><strong>Année :</strong> <?= htmlspecialchars($livre['date_sortie']) ?></p>
        <p><strong>Description :</strong></p>
        <p><?= nl2br(htmlspecialchars($livre['resume'])) ?></p>

        <div class="actions">
            <?php if (!empty($_SESSION['connected']) && in_array($_SESSION['role'], ['bibliothecaire', 'admin'])) : ?>
                <a href="index.php?action=modifier-livre&id=<?= $livre['id'] ?>" class="btn">Modifier ce livre</a>
            <?php endif; ?>
            <a href="index.php?action=chercher" class="btn">Retour à la liste</a>
        </div>
    </div>
</div>
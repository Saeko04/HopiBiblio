<div class="container">
    <!-- Colonne gauche : image -->
    <?php if (!empty($livre['image'])): ?>
        <div class="left-column">
            <img src="data:image/jpeg;base64,<?= base64_encode($livre['image']) ?>" alt="Couverture du livre">
        </div>
    <?php endif; ?>

    <!-- Colonne droite : texte et formulaire -->
    <div class="right-column">
        <h1><?= htmlspecialchars($livre['titre']) ?></h1>
        <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
        <p><strong>Date de sortie :</strong> <?= htmlspecialchars($livre['date_sortie']) ?></p>
        <p><strong>Description :</strong></p>
        <p><?= nl2br(htmlspecialchars($livre['resume'])) ?></p>

        <?php if (!empty($_SESSION['connected']) && in_array($_SESSION['role'], ['bibliothecaire','admin'])): ?>
            <h2>Modifier le livre</h2>
            <!-- FORMULAIRE avec action explicite -->
            <form method="POST" action="index.php?action=modifier-livre&id=<?= $livre['id'] ?>" class="modifier-livre-form">
                <input type="text" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required>
                <input type="text" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required>
                <input type="date" name="date_sortie" value="<?= htmlspecialchars($livre['date_sortie']) ?>" required>
                <textarea name="resume" required><?= htmlspecialchars($livre['resume']) ?></textarea>
                <input type="text" name="cotation" value="<?= htmlspecialchars($livre['cotation'] ?? '') ?>">
                <button type="submit">Enregistrer les modifications</button>
            </form>
        <?php endif; ?>

        <div class="actions">
            <a href="index.php?action=dashboardAdmin" class="btn">Retour au dashboard</a>
        </div>
    </div>
</div>

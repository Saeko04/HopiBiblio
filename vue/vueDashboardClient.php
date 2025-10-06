<div class="container">
    <h1>Mes livres empruntés</h1>

    <?php if (empty($livresEmpruntes)) : ?>
        <p>Vous n'avez emprunté aucun livre pour le moment.</p>
    <?php else : ?>
        <div class="book-cards">
            <?php foreach ($livresEmpruntes as $livre) : ?>
                <a href="index.php?action=detail-livre&id=<?= $livre['id'] ?>&from=mes-emprunts" class="book-card">
                    <?php if (!empty($livre['image'])): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($livre['image']) ?>" alt="<?= htmlspecialchars($livre['titre']) ?>">
                    <?php else: ?>
                        <img src="images/placeholder.png" alt="Pas d'image">
                    <?php endif; ?>
                    <div class="book-info">
                        <h3><?= htmlspecialchars($livre['titre']) ?></h3>
                        <p>Auteur : <?= htmlspecialchars($livre['auteur']) ?></p>
                        <p>Sortie : <?= htmlspecialchars($livre['date_sortie']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

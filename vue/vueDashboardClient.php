<div class="container">
    <h1>Mes livres empruntés</h1>

    <?php if (empty($livresEmpruntes)) : ?>
        <p>Vous n'avez emprunté aucun livre pour le moment.</p>
    <?php else : ?>
        <div class="book-cards">
            <?php foreach ($livresEmpruntes as $livre) : ?>
                <div class="book-card">
                    <a href="index.php?action=detail-livre&id=<?= $livre['id_livre'] ?>&from=mes-emprunts">
                        <?php if (!empty($livre['image'])): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($livre['image']) ?>" alt="<?= htmlspecialchars($livre['titre']) ?>">
                        <?php else: ?>
                            <img src="images/placeholder.png" alt="Pas d'image">
                        <?php endif; ?>
                    </a>
                    <div class="book-info">
                        <h3><?= htmlspecialchars($livre['titre']) ?></h3>
                        <p>Auteur : <?= htmlspecialchars($livre['auteur']) ?></p>
                        <p>Sortie : <?= htmlspecialchars($livre['date_sortie']) ?></p>
                        <p>Emprunté le : <?= htmlspecialchars($livre['date_emprunt']) ?></p>
                        <p>Date de retour prévue : <?= htmlspecialchars($livre['date_retour']) ?></p>
                        <form method="post" action="index.php?action=rendre" style="margin-top:8px;">
                            <input type="hidden" name="id_emprunt" value="<?= htmlspecialchars($livre['id_emprunt']) ?>">
                            <button type="submit" class="btn">Retour</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

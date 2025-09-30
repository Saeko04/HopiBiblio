<div class="dashboard-container">
    <h2>Bienvenue <?= htmlspecialchars($_SESSION['prenom']) ?> <?= htmlspecialchars($_SESSION['nom']) ?></h2>

    <?php if ($livresEmpruntes): ?>
        <h3>Vos livres empruntés :</h3>
        <ul>
            <?php foreach ($livresEmpruntes as $livre): ?>
                <li><?= htmlspecialchars($livre['titre']) ?> - <?= htmlspecialchars($livre['auteur']) ?> (<?= htmlspecialchars($livre['date_sortie']) ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Vous n'avez encore emprunté aucun livre.</p>
    <?php endif; ?>
</div>

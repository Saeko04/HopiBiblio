<div class="dashboard-admin-container">
    <h1>Dashboard Admin</h1>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?> !</p>

    <h2>Ajouter un livre</h2>
    <form method="POST" class="form-ajouter">
        <input type="text" name="titre" placeholder="Titre" required>
        <input type="text" name="auteur" placeholder="Auteur" required>
        <input type="date" name="date_sortie" required>
        <input type="text" name="resume" placeholder="Résumé" required>
        <input type="text" name="cotation" placeholder="Cotation">
        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <h2>Liste des livres</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Résumé</th>
                <th>Cotation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($livres as $livre) : ?>
                <tr>
                    <td><?= htmlspecialchars($livre['id']) ?></td>
                    <td><?= htmlspecialchars($livre['titre']) ?></td>
                    <td><?= htmlspecialchars($livre['auteur']) ?></td>
                    <td><?= htmlspecialchars($livre['date_sortie']) ?></td>
                    <td><?= htmlspecialchars($livre['resume']) ?></td>
                    <td><?= htmlspecialchars($livre['cotation']) ?></td>
                    <td>

                        <a href="index.php?action=modifier-livre&id=<?= $livre['id'] ?>"><button>✏️</button></a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce livre ?');">
                            <input type="hidden" name="supprimer" value="<?= $livre['id'] ?>">
                            <button type="submit">🗑️</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
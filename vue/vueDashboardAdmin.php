<div class="dashboard-admin-container">
    <h1>Tableau de bord - Bibliothécaire</h1>
    <p class="welcome">Bienvenue, <strong><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></strong> !</p>

    <h2>Rechercher un livre</h2>
    <form method="GET" action="index.php">
        <!-- Indique l'action pour le contrôleur -->
        <input type="hidden" name="action" value="dashboardAdmin">

        <input type="text" name="titre" placeholder="Titre" value="<?= htmlspecialchars($titre ?? '') ?>">
        <input type="text" name="auteur" placeholder="Auteur" value="<?= htmlspecialchars($auteur ?? '') ?>">

        <select name="cotation">
            <option value="">Toutes les cotations</option>
            <?php foreach ($cotationsDisponibles as $cot): ?>
                <option value="<?= htmlspecialchars($cot['cotation']) ?>" <?= ($cotation ?? '') == $cot['cotation'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cot['cotation']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Rechercher</button>
    </form>



    <!-- Section ajout de livre -->
    <form method="POST" class="form-ajouter" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Titre" required>
        <input type="text" name="auteur" placeholder="Auteur" required>
        <input type="date" name="date_sortie" required>
        <input type="text" name="resume" placeholder="Résumé" required>
        <input type="text" name="cotation" placeholder="Cotation">

        <!-- Nouveau champ image -->
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" name="ajouter">Ajouter</button>
    </form>


    <!-- Section liste des livres -->
    <section class="card">
        <h2>Liste des livres</h2>
        <div class="table-container">
            <table class="table-livres">
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
                            <td class="resume-cell"><?= htmlspecialchars($livre['resume']) ?></td>
                            <td><?= htmlspecialchars($livre['cotation']) ?></td>
                            <td class="actions">
                                <a href="index.php?action=modifier-livre&id=<?= $livre['id'] ?>" class="btn-edit">✏️</a>
                                <form method="POST" onsubmit="return confirm('Supprimer ce livre ?');" class="inline-form">
                                    <input type="hidden" name="supprimer" value="<?= $livre['id'] ?>">
                                    <button type="submit" class="btn-delete">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Section livres non rendus -->
    <h2>Livres non rendus</h2>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Emprunt</th>
                    <th>Livre</th>
                    <th>Auteur</th>
                    <th>Emprunteur</th>
                    <th>Date Emprunt</th>
                    <th>Date Limite</th>
                    <th>Jours de retard</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($livresNonRendus)) : ?>
                    <?php foreach ($livresNonRendus as $livre) : ?>
                        <tr>
                            <td><?= htmlspecialchars($livre['id']) ?></td>
                            <td><?= htmlspecialchars($livre['titre']) ?></td>
                            <td><?= htmlspecialchars($livre['auteur']) ?></td>
                            <td><?= htmlspecialchars($livre['prenom'] . ' ' . $livre['nom']) ?></td>
                            <td><?= htmlspecialchars($livre['date_emprunt']) ?></td>
                            <td><?= htmlspecialchars($livre['date_limite']) ?></td>
                            <td>
                                <?php
                                $retard = max(0, intval($livre['jours_depasse']));
                                echo $retard > 0 ? $retard . ' jours' : '-';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;">Aucun livre non rendu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Sauvegardes -->
     <h2>Sauvegardes</h2>
     <form method="post" action="">
    <button type="submit" name="sauvegarde">Lancer la sauvegarde</button>
</form>
     
</div>
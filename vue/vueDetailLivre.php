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
        <div class="actions">
            <a href="<?= $retourUrl ?>" class="btn"><?= $retourTexte ?></a>

            <!-- Formulaire POST pour l'emprunt : envoie id + infos du livre -->
            <?php if (!empty($isEmprunte)): ?>
                <button class="btn emprunter disabled" disabled>Emprunter</button>
                <p class="error">Livre déjà emprunté</p>
                <?php if (!empty($dateRetourPrevue)): ?>
                    <p class="small">Date de retour prévue : <?= htmlspecialchars($dateRetourPrevue) ?></p>
                <?php endif; ?>
            <?php else: ?>
                <form method="post" action="index.php?action=emprunter" style="margin:0;">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($livre['id']) ?>">
                    <input type="hidden" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>">
                    <input type="hidden" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>">
                    <input type="hidden" name="from" value="<?= htmlspecialchars($from) ?>">
                    <button type="submit" class="btn emprunter">Emprunter</button>
                </form>
            <?php endif; ?>
        </div>


    </div>
</div>
<div class="main-login-wrapper">
    <!-- Encadré Connexion -->
    <div class="login-container">
        <h2>Connexion</h2>

        <?php if ($message && $_POST['action'] === 'connexion') : ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="connexion">
            <input type="text" name="login" placeholder="Login" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>

    <!-- Encadré Inscription -->
    <div class="login-container" style="margin-top: 20px;">
        <h2>Inscription</h2>

        <?php if ($message && $_POST['action'] === 'inscription') : ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="inscription">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="text" name="login" placeholder="Login" required>
            <input type="password" name="password" placeholder="Mot de passe" required>

            <!-- Case à cocher pour la politique de confidentialité -->
            <label>
                <input type="checkbox" name="accept" required>
                J'accepte la <a href="index.php?action=contact" target="_blank">politique de confidentialité</a>
            </label>

            <button type="submit">S'inscrire</button>
        </form>
    </div>
</div>

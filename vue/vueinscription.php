<div class="main-login-wrapper">
    <div class="login-container">
        <h1>Connexion</h1>

        <?php if (!empty($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if (empty($_SESSION['connected'])): ?>
            <form method="POST" action="index.php?action=connexion">
                <input type="text" name="login" placeholder="Login" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">Se connecter</button>
            </form>
        <?php else: ?>
            <p>✅ Vous êtes déjà connecté en tant que <strong><?= htmlspecialchars($_SESSION['login']) ?></strong> (<?= $_SESSION['role'] ?>)</p>
            <a href="index.php?action=deconnexion">Se déconnecter</a>
        <?php endif; ?>
    </div>
</div>

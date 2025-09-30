<div class="main-login-wrapper">
    <div class="login-container">
        <h1>Connexion</h1>

        <?php if (!empty($message)): ?>
            <p class="login-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if (empty($_SESSION['connected'])): ?>
            <form method="POST" action="index.php?action=login">
                <!-- formulaire de connexion -->
            </form>
        <?php else: ?>
            <p>✅ Vous êtes déjà connecté en tant que <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.</p>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <p><a href="index.php?action=membre">Accéder à l'espace admin</a></p>
            <?php endif; ?>

            <a href="index.php?action=logout">Se déconnecter</a>
        <?php endif; ?>

    </div>
</div>
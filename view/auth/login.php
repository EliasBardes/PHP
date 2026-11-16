<?php
require_once __DIR__ . '/../layout/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-header">
        <h1>Connexion</h1>
        <p>Accédez à votre espace personnel AUTO-MATCH pour suivre vos demandes et réservations.</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <form action="index.php?action=login" method="POST">
        <div class="form-group">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="exemple@exemple.fr" value="<?= htmlspecialchars($oldEmail ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary btn-full" style="padding: 12px; margin-top: 8px;">Se connecter</button>
    </form>

    <div class="auth-footer">
        Pas encore de compte ? <a href="index.php?action=register">Créer un compte</a>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

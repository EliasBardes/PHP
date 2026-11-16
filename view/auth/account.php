<?php
require_once __DIR__ . '/../layout/header.php';
?>

<div class="subheader-bar">
    <a href="index.php" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Retour au catalogue
    </a>
    <div class="breadcrumbs">
        <span>Mon Compte</span>
    </div>
</div>

<div class="catalog-header" style="margin-bottom: 24px;">
    <div class="catalog-title-group">
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Client') ?></h1>
        <p>Retrouvez ci-dessous l'historique de vos demandes de contact et vos réservations de véhicules.</p>
    </div>
    <div>
        <a href="index.php?action=logout" class="btn btn-secondary">Se déconnecter</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 32px; margin-bottom: 40px;">
    <!-- Section Demandes de contact -->
    <div class="contact-card">
        <h2 style="font-size: 18px; margin-bottom: 16px;">Vos demandes de contact</h2>
        <?php if (empty($requests)): ?>
            <p style="color: var(--text-muted); font-size: 13.5px;">Vous n'avez pas encore envoyé de demande de contact.</p>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach ($requests as $req): ?>
                    <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; background: var(--bg-light); display: flex; justify-content: space-between; align-items: center; gap: 16px;">
                        <div>
                            <strong style="font-size: 14.5px;"><?= htmlspecialchars($req['brand'] . ' ' . $req['model']) ?></strong>
                            <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 2px;">
                                Envoyée le <?= date('d/m/Y à H:i', strtotime($req['created_at'])) ?>
                            </div>
                            <div style="font-size: 13px; color: #334155; margin-top: 6px; font-style: italic;">
                                "<?= htmlspecialchars($req['message']) ?>"
                            </div>
                        </div>
                        <a href="index.php?action=show&id=<?= $req['vehicle_id'] ?>" class="btn btn-secondary" style="font-size: 12.5px; white-space: nowrap;">Voir l'annonce</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Section Réservations -->
    <div class="contact-card">
        <h2 style="font-size: 18px; margin-bottom: 16px;">Vos réservations</h2>
        <?php if (empty($reservations)): ?>
            <p style="color: var(--text-muted); font-size: 13.5px;">Aucune réservation en cours.</p>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <?php foreach ($reservations as $res): ?>
                    <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px; background: #ffffff; display: flex; justify-content: space-between; align-items: center; gap: 16px;">
                        <div>
                            <strong style="font-size: 14.5px;"><?= htmlspecialchars($res['brand'] . ' ' . $res['model']) ?></strong>
                            <div style="font-size: 12.5px; color: var(--text-muted); margin-top: 2px;">
                                Réservé le <?= date('d/m/Y à H:i', strtotime($res['created_at'])) ?>
                            </div>
                            <span style="display: inline-block; background-color: #ecfdf5; color: #047857; font-weight: 600; padding: 2px 8px; border-radius: 9999px; font-size: 11.5px; margin-top: 6px;">
                                Statut : <?= htmlspecialchars($res['status']) ?>
                            </span>
                        </div>
                        <a href="index.php?action=show&id=<?= $res['vehicle_id'] ?>" class="btn btn-secondary" style="font-size: 12.5px; white-space: nowrap;">Fiche véhicule</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../layout/footer.php';
?>

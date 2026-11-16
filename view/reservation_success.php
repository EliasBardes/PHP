<?php
require_once __DIR__ . '/layout/header.php';
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
        Catalogue &gt; <?= htmlspecialchars($vehicle->getFullName()) ?> &gt; <span>Réservation</span>
    </div>
</div>

<div class="confirmation-container">
    <div class="confirm-icon-wrap" style="background-color: #eff6ff; border-color: #bfdbfe; color: #165df5;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
    </div>

    <h1 class="confirmation-title">Votre réservation est enregistrée !</h1>
    <p class="confirmation-subtitle">La <?= htmlspecialchars($vehicle->getFullName()) ?> est désormais réservée à votre attention. Un conseiller AUTO-MATCH prendra contact avec vous dans un délai de 24 heures pour finaliser votre dossier.</p>

    <div class="confirm-grid">
        <div class="confirm-vehicle-card">
            <div class="confirm-vehicle-thumb">
                <img src="<?= htmlspecialchars($vehicle->getImage()) ?>" alt="<?= htmlspecialchars($vehicle->getFullName()) ?>">
            </div>
            <div class="confirm-vehicle-title"><?= htmlspecialchars($vehicle->getFullName()) ?></div>
            <div class="confirm-vehicle-version"><?= htmlspecialchars($vehicle->getVersion()) ?></div>
            <div class="confirm-vehicle-specs">
                <?= (int)$vehicle->getYear() ?> • <?= htmlspecialchars($vehicle->getFormattedMileage()) ?> • <?= htmlspecialchars($vehicle->getFuel()) ?>
            </div>
            <div class="confirm-vehicle-price"><?= htmlspecialchars($vehicle->getFormattedPrice()) ?></div>
        </div>

        <div class="confirm-request-card">
            <div class="confirm-request-title">Détails de la réservation</div>
            <div class="confirm-section-label">Numéro de réservation :</div>
            <div class="confirm-contact-details">
                <strong>#RES-<?= sprintf('%05d', $reservation->getId() ?? 1) ?></strong>
            </div>

            <div class="confirm-section-label">Statut :</div>
            <div class="confirm-contact-details">
                <span style="display: inline-block; background-color: #ecfdf5; color: #047857; font-weight: 600; padding: 4px 10px; border-radius: 9999px; font-size: 12px;">Confirmée</span>
            </div>

            <div class="confirm-section-label">Date :</div>
            <div class="confirm-contact-details">
                <?= date('d/m/Y à H:i') ?>
            </div>
        </div>
    </div>

    <div class="confirm-actions">
        <a href="index.php" class="btn btn-primary" style="padding: 10px 28px;">Retour au catalogue</a>
        <a href="index.php?action=show&id=<?= $vehicle->getId() ?>" class="link-review">Revoir la fiche véhicule</a>
    </div>
</div>

<?php
require_once __DIR__ . '/layout/footer.php';
?>

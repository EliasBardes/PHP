<?php
require_once __DIR__ . '/layout/header.php';
$vehicleName = $vehicle ? $vehicle->getFullName() : 'Véhicule';
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
        Catalogue &gt; <?= htmlspecialchars($vehicleName) ?> &gt; <span>Confirmation</span>
    </div>
</div>

<div class="confirmation-container">
    <div class="confirm-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </div>

    <h1 class="confirmation-title">Votre demande a bien été envoyée.</h1>
    <p class="confirmation-subtitle">Votre intérêt pour cette <?= htmlspecialchars($vehicleName) ?> a été transmis avec vos coordonnées et votre message. Aucune inscription n'est requise.</p>

    <div class="confirm-grid">
        <!-- Carte récapitulatif véhicule (Gauche) -->
        <?php if ($vehicle): ?>
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
                <a href="index.php?action=reserve&id=<?= $vehicle->getId() ?>" class="btn btn-primary btn-full">Réserver la voiture</a>
            </div>
        <?php endif; ?>

        <!-- Carte récapitulatif contact (Droite) -->
        <div class="confirm-request-card">
            <div class="confirm-request-title">Votre demande de contact</div>

            <div class="confirm-section-label">Coordonnées transmises :</div>
            <div class="confirm-contact-details">
                <div><strong><?= htmlspecialchars($contactRequest->getName()) ?></strong></div>
                <div><?= htmlspecialchars($contactRequest->getEmail()) ?></div>
                <?php if ($contactRequest->getPhone()): ?>
                    <div><?= htmlspecialchars($contactRequest->getPhone()) ?></div>
                <?php endif; ?>
            </div>

            <div class="confirm-section-label">Votre message :</div>
            <div class="confirm-message-box">
                "<?= nl2br(htmlspecialchars($contactRequest->getMessage())) ?>"
            </div>
        </div>
    </div>

    <div class="confirm-actions">
        <a href="index.php" class="btn btn-primary" style="padding: 10px 28px;">Retour au catalogue</a>
        <?php if ($vehicle): ?>
            <a href="index.php?action=show&id=<?= $vehicle->getId() ?>" class="link-review">Revoir la fiche <?= htmlspecialchars($vehicle->getModel()) ?></a>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/layout/footer.php';
?>

<?php
require_once __DIR__ . '/layout/header.php';
$hasErrors = !empty($errors);
$errorCount = count($errors);
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
        Catalogue &gt; <span><?= htmlspecialchars($vehicle->getFullName()) ?></span>
    </div>
</div>

<div class="detail-layout">
    <!-- Colonne gauche : Présentation du véhicule -->
    <div class="vehicle-detail-main">
        <div class="detail-header">
            <div class="detail-title-group">
                <h1><?= htmlspecialchars($vehicle->getFullName()) ?></h1>
                <p><?= htmlspecialchars($vehicle->getVersion()) ?></p>
            </div>
            <div class="detail-price-box">
                <div class="price-label">Prix du véhicule</div>
                <div class="price-value"><?= htmlspecialchars($vehicle->getFormattedPrice()) ?></div>
            </div>
        </div>

        <div class="detail-image-box">
            <img src="<?= htmlspecialchars($vehicle->getImage()) ?>" alt="<?= htmlspecialchars($vehicle->getFullName()) ?>">
        </div>

        <a href="index.php?action=reserve&id=<?= $vehicle->getId() ?>" class="btn-reserve-large">Réserver la voiture</a>

        <h3 class="specs-section-title">L'essentiel du véhicule</h3>
        <div class="specs-grid">
            <div class="spec-item">
                <div class="spec-item-top">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Année</span>
                </div>
                <div class="spec-item-val"><?= (int)$vehicle->getYear() ?></div>
            </div>

            <div class="spec-item">
                <div class="spec-item-top">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Kilométrage</span>
                </div>
                <div class="spec-item-val"><?= htmlspecialchars($vehicle->getFormattedMileage()) ?></div>
            </div>

            <div class="spec-item">
                <div class="spec-item-top">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 22h12"></path>
                        <path d="M4 9h10"></path>
                        <path d="M14 22V4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v18"></path>
                        <path d="M14 13h2a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V9.83a2 2 0 0 0-.59-1.42L18 5"></path>
                    </svg>
                    <span>Énergie</span>
                </div>
                <div class="spec-item-val"><?= htmlspecialchars($vehicle->getFuel()) ?></div>
            </div>
        </div>

        <div class="callout-question">
            <div class="callout-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>
            <div class="callout-content">
                <h4>Une question sur cette <?= htmlspecialchars($vehicle->getFullName()) ?> ?</h4>
                <p>Utilisez le formulaire pour demander des précisions sur le véhicule ou organiser un échange. Aucun compte n'est nécessaire.</p>
            </div>
        </div>
    </div>

    <!-- Colonne droite : Formulaire de contact (Maquettes 2 & 3) -->
    <div class="contact-card">
        <h2>Ce véhicule vous intéresse ?</h2>
        <p class="contact-subtitle">Demandez votre contact au sujet de cette <?= htmlspecialchars($vehicle->getFullName()) ?>, sans vous inscrire.</p>

        <div class="form-info-pill">
            Une demande, sans création de compte. Les champs marqués d'un * sont obligatoires.
        </div>

        <?php if ($hasErrors): ?>
            <div class="alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>Vérifiez les <?= $errorCount ?> champs signalés. Votre demande n'a pas pu être envoyée.</span>
            </div>
        <?php endif; ?>

        <form action="index.php?action=contact&id=<?= $vehicle->getId() ?>" method="POST" novalidate>
            <div class="form-group">
                <label for="input-nom" class="form-label">Nom *</label>
                <input 
                    type="text" 
                    id="input-nom" 
                    name="name" 
                    class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                    value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                    placeholder="Votre nom"
                >
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="input-email" class="form-label">E-mail *</label>
                <input 
                    type="email" 
                    id="input-email" 
                    name="email" 
                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>" 
                    placeholder="exemple@exemple.fr"
                >
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="input-phone" class="form-label">Téléphone (facultatif)</label>
                <input 
                    type="tel" 
                    id="input-phone" 
                    name="phone" 
                    class="form-control" 
                    value="<?= htmlspecialchars($old['phone'] ?? '') ?>" 
                    placeholder="06 12 34 56 78"
                >
            </div>

            <div class="form-group">
                <label for="input-message" class="form-label">Message *</label>
                <textarea 
                    id="input-message" 
                    name="message" 
                    class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" 
                    rows="4"
                    placeholder="Bonjour, je souhaiterais en savoir plus..."
                ><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['message']) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="padding: 12px; font-size: 14.5px;">Envoyer ma demande</button>
            <div class="form-subtext">Votre demande concerne uniquement le véhicule dont vous consultez la fiche</div>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/layout/footer.php';
?>

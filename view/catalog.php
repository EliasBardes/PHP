<?php
require_once __DIR__ . '/layout/header.php';
?>

<div class="catalog-header">
    <div class="catalog-title-group">
        <h1>Découvrez nos véhicules</h1>
        <p>Trois annonces sélectionnées pour commencer votre recherche. Chaque carte reprend les informations essentielles du véhicule : modèle, version, année, kilométrage, énergie et prix.</p>
    </div>
    <div>
        <span class="badge-count">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <?= (int)($total ?? count($vehicles)) ?> véhicules référencés
        </span>
    </div>
</div>

<div class="vehicles-grid">
    <?php foreach ($vehicles as $vehicle): ?>
        <article class="vehicle-card">
            <div class="card-image-wrap">
                <img src="<?= htmlspecialchars($vehicle->getImage()) ?>" alt="<?= htmlspecialchars($vehicle->getFullName()) ?>" loading="lazy">
            </div>
            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($vehicle->getFullName()) ?></h2>
                <div class="card-specs"><?= htmlspecialchars($vehicle->getSummaryLine()) ?></div>

                <div class="card-price-section">
                    <div class="price-label">Prix du véhicule</div>
                    <div class="price-value"><?= htmlspecialchars($vehicle->getFormattedPrice()) ?></div>
                </div>

                <div class="card-actions">
                    <a href="index.php?action=show&id=<?= $vehicle->getId() ?>" class="btn btn-secondary flex-1">Voir le véhicule</a>
                    <a href="index.php?action=reserve&id=<?= $vehicle->getId() ?>" class="btn btn-primary flex-1">Réserver la voiture</a>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php
require_once __DIR__ . '/layout/footer.php';
?>

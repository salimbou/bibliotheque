<?php
/**
 * Ads Display Component
 * Affiche les publicités selon le type et la position
 */

function displayAds(string $position = 'banner_side', string $page = 'home', int $count = 1): void {
    $adModel = new Advertisement();
    $ads = $adModel->getActiveAds($position, $page, $count);
    
    if (empty($ads)) {
        return;
    }
    
    foreach ($ads as $ad) {
        // Track impression
        $adModel->trackImpression($ad['id'], Session::getUserId());
        
        // Display ad based on type
        switch ($position) {
            case 'banner_top':
                displayTopBanner($ad);
                break;
            case 'banner_side':
                displaySideBanner($ad);
                break;
            case 'banner_bottom':
                displayBottomBanner($ad);
                break;
            case 'inline':
                displayInlineAd($ad);
                break;
        }
    }
}

function displayTopBanner(array $ad): void {
    ?>
    <div class="ad-banner ad-top mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row align-items-center py-3">
                <div class="col-md-9">
                    <div class="d-flex align-items-center text-white">
                        <?php if ($ad['image_url']): ?>
                            <img src="<?php echo asset('images/uploads/' . $ad['image_url']); ?>" 
                                 alt="<?php echo escape($ad['title']); ?>" 
                                 style="height: 60px; margin-right: 20px; border-radius: 8px;">
                        <?php endif; ?>
                        <div>
                            <h5 class="mb-1"><?php echo escape($ad['title']); ?></h5>
                            <p class="mb-0 small"><?php echo escape($ad['description']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <a href="<?php echo url('/ads/track/' . $ad['id']); ?>" 
                       target="_blank" 
                       class="btn btn-light btn-lg">
                        En savoir plus <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function displaySideBanner(array $ad): void {
    ?>
    <div class="card mb-3 ad-banner ad-side">
        <div class="card-body text-center">
            <small class="text-muted d-block mb-2">Publicité</small>
            <?php if ($ad['image_url']): ?>
                <a href="<?php echo url('/ads/track/' . $ad['id']); ?>" target="_blank">
                    <img src="<?php echo asset('images/uploads/' . $ad['image_url']); ?>" 
                         alt="<?php echo escape($ad['title']); ?>" 
                         class="img-fluid rounded mb-2">
                </a>
            <?php endif; ?>
            <h6><?php echo escape($ad['title']); ?></h6>
            <p class="small text-muted"><?php echo truncate($ad['description'], 80); ?></p>
            <a href="<?php echo url('/ads/track/' . $ad['id']); ?>" 
               target="_blank" 
               class="btn btn-sm btn-primary">
                Voir l'offre
            </a>
        </div>
    </div>
    <?php
}

function displayBottomBanner(array $ad): void {
    ?>
    <div class="ad-banner ad-bottom mt-4 mb-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <?php if ($ad['image_url']): ?>
                        <div class="col-md-3">
                            <img src="<?php echo asset('images/uploads/' . $ad['image_url']); ?>" 
                                 alt="<?php echo escape($ad['title']); ?>" 
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-9">
                    <?php else: ?>
                        <div class="col-md-12">
                    <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary mb-2">Sponsorisé</span>
                                <h5><?php echo escape($ad['title']); ?></h5>
                                <p class="mb-0"><?php echo escape($ad['description']); ?></p>
                            </div>
                            <a href="<?php echo url('/ads/click/' . $ad['id']); ?>" 
                               target="_blank" 
                               class="btn btn-primary ms-3">
                                Découvrir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function displayInlineAd(array $ad): void {
    ?>
    <div class="card bg-light border-0 mb-4 ad-inline">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-2">
                    <span class="badge bg-warning text-dark">Ad</span>
                </div>
                <div class="col-7">
                    <h6 class="mb-0"><?php echo escape($ad['title']); ?></h6>
                    <small class="text-muted"><?php echo escape($ad['company_name']); ?></small>
                </div>
                <div class="col-3 text-end">
                    <a href="<?php echo url('/ads/click/' . $ad['id']); ?>" 
                       target="_blank" 
                       class="btn btn-sm btn-outline-primary">
                        Voir
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
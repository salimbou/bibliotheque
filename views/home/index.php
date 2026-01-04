<?php

require_once ROOT_PATH . '/views/layouts/header.php'; 
require_once ROOT_PATH . '/views/layouts/navbar.php'; 
require_once ROOT_PATH . '/models/News.php';
require_once ROOT_PATH . '/views/components/ads.php';

displayAds('banner_top', 'home', 1);
$pageTitle = 'Accueil';
$news = new News();
$news = $news->getActiveNews(5);
?>

<!-- Hero Section / Carousel -->
<div id="newsCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php foreach ($news as $index => $item): ?>
            <button type="button" data-bs-target="#newsCarousel" data-bs-slide-to="<?php echo $index; ?>" 
                    class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
        <?php endforeach; ?>
    </div>
    
    <div class="carousel-inner">
        <?php if (empty($news)): ?>
            <div class="carousel-item active">
                <div class="bg-primary text-white py-5">
                    <div class="container text-center">
                        <h1 class="display-4">Bienvenue à la Bibliothèque</h1>
                        <p class="lead">Découvrez notre collection de livres et participez à nos événements</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($news as $index => $item): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="bg-primary text-white py-5" style="min-height: 400px; 
                         <?php if (!empty($item['image'])): ?>
                         background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                                     url('<?php echo asset('images/uploads/' . $item['image']); ?>') center/cover;
                         <?php endif; ?>">
                        <div class="container">
                            <div class="row align-items-center" style="min-height: 300px;">
                                <div class="col-md-8 mx-auto text-center">
                                    <h1 class="display-4 mb-3"><?php echo escape($item['title']); ?></h1>
                                    <?php if (!empty($item['subtitle'])): ?>
                                        <p class="lead mb-4"><?php echo escape($item['subtitle']); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($item['link'])): ?>
                                        <a href="<?php echo url('/categories'); ?>" class="btn btn-light btn-lg">
                                            En savoir plus
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<div class="container my-5">
    <!-- Announcements -->
    <?php if (!empty($announcements)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <?php foreach ($announcements as $announcement): ?>
                    <div class="alert alert-<?php echo escape($announcement['type']); ?> alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-megaphone"></i> <?php echo escape($announcement['title']); ?></strong>
                        <p class="mb-0 mt-2"><?php echo nl2br(escape($announcement['content'])); ?></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Top Books Section -->
    <section class="mb-5">
        <h2 class="mb-4"><i class="bi bi-star-fill text-warning"></i> Livres Populaires</h2>
        <div class="row">
            <?php if (empty($topBooks)): ?>
                <div class="col-12">
                    <p class="text-muted">Aucun livre disponible pour le moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($topBooks as $book): ?>
                    <div class="col-md-4 col-lg-2 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($book['cover_image'])): ?>
                                <img src="<?php echo asset('images/uploads/' . $book['cover_image']); ?>" 
                                     class="card-img-top" alt="<?php echo escape($book['title']); ?>" 
                                     style="height: 250px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     style="height: 250px;">
                                    <i class="bi bi-book" style="font-size: 4rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo truncate($book['title'], 50); ?></h6>
                                <p class="card-text text-muted small"><?php echo escape($book['author']); ?></p>
                                <?php if ($book['avg_rating'] > 0): ?>
                                    <div class="text-warning small">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo $i <= round($book['avg_rating']) ? '-fill' : ''; ?>"></i>
                                        <?php endfor; ?>
                                        <span class="text-muted">(<?php echo number_format($book['avg_rating'], 1); ?>)</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
             <!-- Sidebar with ads -->
            <div class="col-md-3">
                <h5 class="mb-3">Nos Partenaires</h5>
                <?php displayAds('banner_side', 'home', 3); ?>
            </div>
        </div>
        <?php displayAds('banner_bottom', 'home', 1); ?>
    </section>
    
    <!-- Upcoming Events Section -->
    <section class="mb-5">
        <h2 class="mb-4"><i class="bi bi-calendar-event"></i> Événements à Venir</h2>
        <div class="row">
            <?php if (empty($upcomingEvents)): ?>
                <div class="col-12">
                    <p class="text-muted">Aucun événement prévu pour le moment.</p>
                </div>
            <?php else: ?>
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($event['image'])): ?>
                                <img src="<?php echo asset('images/uploads/' . $event['image']); ?>" 
                                     class="card-img-top" alt="<?php echo escape($event['title']); ?>" 
                                     style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo escape($event['title']); ?></h5>
                                <p class="card-text"><?php echo truncate($event['description'], 100); ?></p>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-calendar3"></i> <?php echo format_date($event['event_date']); ?>
                                    <?php if ($event['event_time']): ?>
                                        à <?php echo date('H:i', strtotime($event['event_time'])); ?>
                                    <?php endif; ?>
                                </p>
                                <?php if ($event['location']): ?>
                                    <p class="text-muted small">
                                        <i class="bi bi-geo-alt"></i> <?php echo escape($event['location']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="mb-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-4"><i class="bi bi-info-circle"></i> À Propos</h2>
                <p>Notre bibliothèque en ligne vous offre un accès facile à une vaste collection de livres, 
                   des événements culturels réguliers et une communauté de lecteurs passionnés.</p>
                <p>Inscrivez-vous dès aujourd'hui pour emprunter des livres, participer à nos événements 
                   et partager vos avis avec d'autres lecteurs.</p>
                <a href="<?php echo url('/about'); ?>" class="btn btn-outline-primary">En savoir plus</a>
            </div>
            <div class="col-md-6">
                <h2 class="mb-4"><i class="bi bi-geo-alt"></i> Nous Trouver</h2>
                <div class="bg-light p-4 rounded">
                    <p><strong>Adresse:</strong><br>
                    123 Rue de la Bibliothèque<br>
                    75001 Paris, France</p>
                    
                    <p><strong>Horaires:</strong><br>
                    Lundi - Vendredi: 9h00 - 19h00<br>
                    Samedi: 10h00 - 18h00<br>
                    Dimanche: Fermé</p>
                    
                    <p><strong>Contact:</strong><br>
                    Tél: +33 1 23 45 67 89<br>
                    Email: contact@bibliotheque.com</p>
                </div>
            </div>
        </div>
    </section>

</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
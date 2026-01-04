<?php
$pageTitle = 'À Propos';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Accueil</a></li>
            <li class="breadcrumb-item active">À Propos</li>
        </ol>
    </nav>
    
    <div class="row mb-5">
        <div class="col-lg-12">
            <h1 class="mb-4"><i class="bi bi-info-circle"></i> À Propos de Notre Bibliothèque</h1>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="text-primary mb-3">Notre Mission</h3>
                    <p class="lead">
                        Nous sommes dédiés à promouvoir la lecture et l'accès à la connaissance pour tous. 
                        Notre bibliothèque en ligne offre un espace moderne et convivial pour découvrir, 
                        emprunter et partager des livres.
                    </p>
                    <p>
                        Fondée en 2025, notre bibliothèque numérique s'engage à rendre la lecture accessible 
                        à tous, où que vous soyez. Nous croyons au pouvoir des livres pour éduquer, inspirer 
                        et transformer les vies.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-book-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Large Collection</h5>
                    <p class="card-text">
                        Des milliers de livres dans tous les genres : fiction, non-fiction, 
                        classiques, romans contemporains, sciences, histoire et bien plus encore.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-people-fill text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Communauté Active</h5>
                    <p class="card-text">
                        Rejoignez une communauté de lecteurs passionnés. Partagez vos avis, 
                        découvrez de nouvelles recommandations et participez à nos événements.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-calendar-event-fill text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="card-title">Événements Culturels</h5>
                    <p class="card-text">
                        Participez à nos rencontres d'auteurs, clubs de lecture, ateliers d'écriture 
                        et autres événements culturels organisés régulièrement.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-primary mb-3">Nos Services</h3>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Emprunt de livres gratuit pour tous les membres
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Système de réservation en ligne
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Recommandations personnalisées
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Avis et notes des lecteurs
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Événements culturels mensuels
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Espace de discussion et commentaires
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-primary mb-3">Horaires d'Ouverture</h3>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td><strong>Lundi - Vendredi</strong></td>
                                <td>9h00 - 19h00</td>
                            </tr>
                            <tr>
                                <td><strong>Samedi</strong></td>
                                <td>10h00 - 18h00</td>
                            </tr>
                            <tr>
                                <td><strong>Dimanche</strong></td>
                                <td>Fermé</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        <strong>Note:</strong> L'accès en ligne est disponible 24h/24 et 7j/7
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h3 class="text-primary mb-3">Notre Équipe</h3>
                    <p>
                        Notre équipe dévouée de bibliothécaires professionnels est là pour vous aider 
                        à trouver le livre parfait et répondre à toutes vos questions. Nous sommes 
                        passionnés par la lecture et nous nous engageons à créer une expérience 
                        enrichissante pour tous nos membres.
                    </p>
                    <div class="row mt-4">
                        <div class="col-md-4 text-center mb-3">
                            <div class="mb-2">
                                <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                            </div>
                            <h5>Marie Dubois</h5>
                            <p class="text-muted">Directrice de la Bibliothèque</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="mb-2">
                                <i class="bi bi-person-circle text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h5>Jean Martin</h5>
                            <p class="text-muted">Bibliothécaire en Chef</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="mb-2">
                                <i class="bi bi-person-circle text-warning" style="font-size: 4rem;"></i>
                            </div>
                            <h5>Sophie Bernard</h5>
                            <p class="text-muted">Responsable des Événements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12 text-center">
            <h3 class="mb-4">Rejoignez-nous Aujourd'hui !</h3>
            <p class="lead mb-4">
                Inscrivez-vous gratuitement et commencez votre voyage de lecture dès maintenant.
            </p>
            <?php if (!is_authenticated()): ?>
                <a href="<?php echo url('/register'); ?>" class="btn btn-primary btn-lg me-2">
                    <i class="bi bi-person-plus"></i> S'inscrire Gratuitement
                </a>
                <a href="<?php echo url('/login'); ?>" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Se Connecter
                </a>
            <?php else: ?>
                <a href="<?php echo url('/lecteur/books'); ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-book"></i> Parcourir le Catalogue
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
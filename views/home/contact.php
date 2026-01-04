<?php
$pageTitle = 'Contact';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Accueil</a></li>
            <li class="breadcrumb-item active">Contact</li>
        </ol>
    </nav>
    
    <div class="row mb-5">
        <div class="col-lg-12">
            <h1 class="mb-4"><i class="bi bi-envelope"></i> Contactez-nous</h1>
            <p class="lead">
                Vous avez une question ? Une suggestion ? N'hésitez pas à nous contacter. 
                Notre équipe se fera un plaisir de vous répondre dans les plus brefs délais.
            </p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-envelope-fill"></i> Envoyez-nous un Message</h5>
                </div>
                <div class="card-body">
                    <?php display_flash(); ?>
                    
                    <form method="POST" action="<?php echo url('/contact/send'); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Sujet *</label>
                            <select class="form-select" id="subject" name="subject" required>
                                <option value="">Sélectionnez un sujet</option>
                                <option value="information">Demande d'information</option>
                                <option value="inscription">Inscription</option>
                                <option value="emprunt">Question sur un emprunt</option>
                                <option value="suggestion">Suggestion</option>
                                <option value="reclamation">Réclamation</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Note:</strong> Les champs marqués d'un astérisque (*) sont obligatoires.
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Envoyer le Message
                        </button>
                    </form>
                    
                    <div class="alert alert-warning mt-4">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Remarque:</strong> Cette fonctionnalité est en cours de développement. 
                        Pour le moment, veuillez nous contacter directement par email ou téléphone.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Contact Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations de Contact</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> Adresse</h6>
                    <p>
                        123 Rue de la Bibliothèque<br>
                        75001 Paris<br>
                        France
                    </p>
                    
                    <hr>
                    
                    <h6 class="mb-3"><i class="bi bi-telephone-fill text-primary"></i> Téléphone</h6>
                    <p>
                        <a href="tel:+33123456789" class="text-decoration-none">
                            +33 1 23 45 67 89
                        </a>
                    </p>
                    
                    <hr>
                    
                    <h6 class="mb-3"><i class="bi bi-envelope-fill text-warning"></i> Email</h6>
                    <p>
                        <a href="mailto:contact@bibliotheque.com" class="text-decoration-none">
                            contact@bibliotheque.com
                        </a>
                    </p>
                    
                    <hr>
                    
                    <h6 class="mb-3"><i class="bi bi-clock-fill text-info"></i> Horaires</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td><strong>Lun-Ven:</strong></td>
                                <td>9h00 - 19h00</td>
                            </tr>
                            <tr>
                                <td><strong>Samedi:</strong></td>
                                <td>10h00 - 18h00</td>
                            </tr>
                            <tr>
                                <td><strong>Dimanche:</strong></td>
                                <td>Fermé</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Social Media -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-share"></i> Suivez-nous</h5>
                </div>
                <div class="card-body text-center">
                    <a href="#" class="btn btn-outline-primary btn-lg m-1" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-info btn-lg m-1" title="Twitter">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-outline-danger btn-lg m-1" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-dark btn-lg m-1" title="LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map Section -->
    <div class="row mt-5">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-map"></i> Localisation</h5>
                </div>
                <div class="card-body p-0">
                    <!-- Placeholder for map -->
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <div class="text-center">
                            <i class="bi bi-geo-alt-fill text-danger" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">Carte Interactive</h4>
                            <p class="text-muted">123 Rue de la Bibliothèque, 75001 Paris, France</p>
                            <a href="https://www.google.com/maps" target="_blank" class="btn btn-primary">
                                <i class="bi bi-map"></i> Ouvrir dans Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="row mt-5">
        <div class="col-lg-12">
            <h2 class="mb-4"><i class="bi bi-question-circle"></i> Questions Fréquentes</h2>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Comment puis-je m'inscrire à la bibliothèque ?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            L'inscription est gratuite et simple. Cliquez sur le bouton "Inscription" en haut de la page, 
                            remplissez le formulaire avec vos informations, et votre compte sera activé par un administrateur 
                            dans les 24 heures.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Combien de livres puis-je emprunter ?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Vous pouvez emprunter jusqu'à 5 livres simultanément pour une durée de 2 à 4 semaines, 
                            selon le type de livre. Les emprunts peuvent être prolongés si le livre n'est pas réservé 
                            par un autre membre.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Que faire si je perds un livre emprunté ?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Veuillez nous contacter immédiatement. Des frais de remplacement peuvent s'appliquer selon 
                            la valeur du livre. Nous travaillerons avec vous pour trouver une solution adaptée.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Comment puis-je suggérer un livre pour la collection ?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Nous encourageons les suggestions ! Utilisez le formulaire de contact ci-dessus en 
                            sélectionnant "Suggestion" comme sujet, et indiquez le titre, l'auteur et pourquoi vous 
                            pensez que ce livre serait un bon ajout à notre collection.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
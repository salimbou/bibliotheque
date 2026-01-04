<?php
$pageTitle = 'Connexion';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Connexion</h4>
                </div>
                <div class="card-body">
                    <?php display_flash(); ?>
                    
                    <form action="<?php echo url('/login'); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Se connecter
                            </button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p class="mb-0">Pas encore de compte ? 
                            <a href="<?php echo url('/register'); ?>">Inscrivez-vous</a>
                        </p>
                    </div>
                    
                    <div class="mt-3">
                        <div class="alert alert-info">
                            <strong>Comptes de test :</strong><br>
                            <small>
                                <strong>Admin:</strong> admine@bibliotheque.com / Admin@123<br>
                                <strong>Bibliothécaire:</strong> biblio@bibliotheque.com / Biblio@123<br>
                                <strong>Lecteur:</strong> lecteur@bibliotheque.com / Reader@123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
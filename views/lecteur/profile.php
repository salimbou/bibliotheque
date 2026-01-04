<?php
$pageTitle = 'Mon Profil';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';

$errors = Session::get('profile_errors', []);
Session::remove('profile_errors');
?>

<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="list-group">
                <a href="<?php echo url('/lecteur/dashboard'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2"></i> Mon Espace
                </a>
                <a href="<?php echo url('/lecteur/books'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-book"></i> Catalogue
                </a>
                <a href="<?php echo url('/lecteur/borrowings'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-arrow-repeat"></i> Mes Emprunts
                </a>
                <a href="<?php echo url('/lecteur/profile'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person"></i> Mon Profil
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="mb-4"><i class="bi bi-person"></i> Mon Profil</h1>
            
            <?php display_flash(); ?>
            
            <div class="row">
                <!-- Profile Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-circle"></i> Informations Personnelles</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo url('/lecteur/profile/update'); ?>">
                                <?php echo csrf_field(); ?>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">Prénom *</label>
                                        <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" 
                                               id="first_name" name="first_name" value="<?php echo escape($user['first_name'] ?? ''); ?>" required>
                                        <?php if (isset($errors['first_name'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Nom *</label>
                                        <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" 
                                               id="last_name" name="last_name" value="<?php echo escape($user['last_name'] ?? ''); ?>" required>
                                        <?php if (isset($errors['last_name'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="<?php echo escape($user['email'] ?? ''); ?>" disabled>
                                    <small class="text-muted">L'email ne peut pas être modifié</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>" 
                                           id="phone" name="phone" value="<?php echo escape($user['phone'] ?? ''); ?>">
                                    <?php if (isset($errors['phone'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo escape($user['address'] ?? ''); ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Enregistrer les modifications
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Change Password -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0"><i class="bi bi-key"></i> Changer le Mot de Passe</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo url('/lecteur/profile/change-password'); ?>">
                                <?php echo csrf_field(); ?>
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel *</label>
                                    <input type="password" class="form-control" id="current_password" 
                                           name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nouveau mot de passe *</label>
                                    <input type="password" class="form-control" id="new_password" 
                                           name="new_password" required>
                                    <small class="text-muted">
                                        Min. 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe *</label>
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" required>
                                </div>
                                
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-key"></i> Modifier le mot de passe
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Account Info -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations du Compte</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Statut:</strong> 
                                <span class="badge bg-success">Actif</span>
                            </p>
                            <p class="mb-2">
                                <strong>Membre depuis:</strong> <?php echo format_date($user['created_at'] ?? date('Y-m-d')); ?>
                            </p>
                            <?php if (!empty($user['last_login'])): ?>
                                <p class="mb-0">
                                    <strong>Dernière connexion:</strong> <?php echo format_datetime($user['last_login']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
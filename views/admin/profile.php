<?php
$pageTitle = 'Mon Profil Admin';
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
                <a href="<?php echo url('/admin/dashboard'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="<?php echo url('/admin/users'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-people"></i> Utilisateurs
                </a>
                <a href="<?php echo url('/admin/stats'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-graph-up"></i> Statistiques
                </a>
                <a href="<?php echo url('/bibliothecaire/books'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-book"></i> Livres
                </a>
                <a href="<?php echo url('/bibliothecaire/events'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-calendar-event"></i> Événements
                </a>
                <a href="<?php echo url('/admin/profile'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person-circle"></i> Mon Profil
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="mb-4"><i class="bi bi-person-circle"></i> Mon Profil Administrateur</h1>
            
            <?php display_flash(); ?>
            
            <div class="row">
                <!-- Profile Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-person-fill"></i> Informations Personnelles</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo url('/admin/profile/update'); ?>">
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
                                           id="phone" name="phone" value="<?php echo escape($user['phone'] ?? ''); ?>"
                                           placeholder="0612345678">
                                    <?php if (isset($errors['phone'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo escape($user['address'] ?? ''); ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-save"></i> Enregistrer les modifications
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Change Password -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-key-fill"></i> Changer le Mot de Passe</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?php echo url('/admin/profile/change-password'); ?>">
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
                            <h5 class="mb-0"><i class="bi bi-shield-check"></i> Informations du Compte</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Rôle:</strong> 
                                <span class="badge bg-danger">Administrateur</span>
                            </p>
                            <p class="mb-2">
                                <strong>Statut:</strong> 
                                <span class="badge bg-success">
                                    <?php echo ucfirst($user['status'] ?? 'active'); ?>
                                </span>
                            </p>
                            <p class="mb-2">
                                <strong>Membre depuis:</strong> 
                                <?php echo format_date($user['created_at'] ?? date('Y-m-d')); ?>
                            </p>
                            <?php if (!empty($user['last_login'])): ?>
                                <p class="mb-0">
                                    <strong>Dernière connexion:</strong> 
                                    <?php echo format_datetime($user['last_login']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Admin Privileges -->
                    <div class="card mt-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="bi bi-award"></i> Privilèges Administrateur</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    Gestion complète des utilisateurs
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    Accès aux statistiques globales
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    Activation/Désactivation des comptes
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    Accès à toutes les fonctionnalités
                                </li>
                                <li class="mb-0">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    Gestion des livres et événements
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Security Tips -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-shield-exclamation"></i> Conseils de Sécurité</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6><i class="bi bi-key text-warning"></i> Mot de Passe Fort</h6>
                                    <p class="small text-muted">
                                        Utilisez un mot de passe complexe avec au moins 12 caractères, 
                                        incluant des majuscules, minuscules, chiffres et caractères spéciaux.
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h6><i class="bi bi-arrow-clockwise text-primary"></i> Changement Régulier</h6>
                                    <p class="small text-muted">
                                        Changez votre mot de passe tous les 3 mois pour maintenir 
                                        la sécurité de votre compte administrateur.
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h6><i class="bi bi-eye-slash text-danger"></i> Confidentialité</h6>
                                    <p class="small text-muted">
                                        Ne partagez jamais vos identifiants administrateur et 
                                        déconnectez-vous toujours après utilisation.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
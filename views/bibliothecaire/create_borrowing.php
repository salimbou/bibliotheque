<?php
$pageTitle = 'Nouvel Emprunt';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="list-group">
                <a href="<?php echo url('/bibliothecaire/dashboard'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="<?php echo url('/bibliothecaire/books'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-book"></i> Livres
                </a>
                <a href="<?php echo url('/bibliothecaire/borrowings'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-arrow-repeat"></i> Emprunts
                </a>
                <a href="<?php echo url('/bibliothecaire/moderate'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-shield-check"></i> Modération
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-plus-circle"></i> Nouvel Emprunt</h1>
                <a href="<?php echo url('/bibliothecaire/borrowings'); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
            
            <?php display_flash(); ?>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo url('/bibliothecaire/borrowings/create'); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Utilisateur *</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">Sélectionner un utilisateur</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>">
                                        <?php echo escape($user['first_name'] . ' ' . $user['last_name'] . ' (' . $user['email'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($users)): ?>
                                <small class="text-danger">Aucun lecteur actif disponible</small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="book_id" class="form-label">Livre *</label>
                            <select class="form-select" id="book_id" name="book_id" required>
                                <option value="">Sélectionner un livre</option>
                                <?php foreach ($books as $book): ?>
                                    <option value="<?php echo $book['id']; ?>">
                                        <?php echo escape($book['title'] . ' - ' . $book['author'] . ' (Disponibles: ' . $book['available_copies'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($books)): ?>
                                <small class="text-danger">Aucun livre disponible</small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="days_to_return" class="form-label">Durée de l'emprunt (jours) *</label>
                            <select class="form-select" id="days_to_return" name="days_to_return" required>
                                <option value="7">7 jours (1 semaine)</option>
                                <option value="14" selected>14 jours (2 semaines)</option>
                                <option value="21">21 jours (3 semaines)</option>
                                <option value="30">30 jours (1 mois)</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Information:</strong> La date de retour sera automatiquement calculée à partir de la date d'emprunt (aujourd'hui).
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo url('/bibliothecaire/borrowings'); ?>" class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" <?php echo (empty($users) || empty($books)) ? 'disabled' : ''; ?>>
                                <i class="bi bi-save"></i> Créer l'emprunt
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
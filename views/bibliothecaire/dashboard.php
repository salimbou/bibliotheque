<?php
$pageTitle = 'Tableau de bord Bibliothécaire';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="list-group">
                <a href="<?php echo url('/bibliothecaire/dashboard'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="<?php echo url('/bibliothecaire/books'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-book"></i> Livres
                </a>
                <a href="<?php echo url('/bibliothecaire/borrowings'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-arrow-repeat"></i> Emprunts
                </a>
                <a href="<?php echo url('/bibliothecaire/moderate'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-shield-check"></i> Modération
                    <?php if ($pendingReviews + $pendingComments > 0): ?>
                        <span class="badge bg-danger"><?php echo $pendingReviews + $pendingComments; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Tableau de bord Bibliothécaire</h1>
            
            <?php display_flash(); ?>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Livres</h6>
                                    <h2 class="mb-0"><?php echo $bookStats['total_books']; ?></h2>
                                </div>
                                <div>
                                    <i class="bi bi-book" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                            <small><?php echo $bookStats['available_copies']; ?> disponibles</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Emprunts actifs</h6>
                                    <h2 class="mb-0"><?php echo $borrowingStats['active']; ?></h2>
                                </div>
                                <div>
                                    <i class="bi bi-arrow-repeat" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                            <small><?php echo $borrowingStats['overdue']; ?> en retard</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">En attente</h6>
                                    <h2 class="mb-0"><?php echo $pendingReviews + $pendingComments; ?></h2>
                                </div>
                                <div>
                                    <i class="bi bi-shield-check" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                            <small>
                                <?php echo $pendingReviews; ?> avis | <?php echo $pendingComments; ?> commentaires
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Événements</h6>
                                    <h2 class="mb-0"><?php echo $eventStats['upcoming']; ?></h2>
                                </div>
                                <div>
                                    <i class="bi bi-calendar-event" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                            <small><?php echo $eventStats['total_participants']; ?> participants</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-lightning"></i> Actions Rapides</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo url('/bibliothecaire/books/add'); ?>" class="btn btn-primary w-100">
                                        <i class="bi bi-plus-circle"></i> Ajouter un livre
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo url('/bibliothecaire/borrowings/create'); ?>" class="btn btn-success w-100">
                                        <i class="bi bi-plus-circle"></i> Nouvel emprunt
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo url('/bibliothecaire/borrowings'); ?>" class="btn btn-warning w-100">
                                        <i class="bi bi-list"></i> Gérer emprunts
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="<?php echo url('/bibliothecaire/moderate'); ?>" class="btn btn-danger w-100">
                                        <i class="bi bi-shield-check"></i> Modération
                                        <?php if ($pendingReviews + $pendingComments > 0): ?>
                                            <span class="badge bg-light text-dark"><?php echo $pendingReviews + $pendingComments; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Overdue Borrowings -->
            <?php if (!empty($overdueBorrowings)): ?>
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Emprunts en Retard</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Livre</th>
                                        <th>Date emprunt</th>
                                        <th>Date retour prévue</th>
                                        <th>Jours de retard</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($overdueBorrowings as $borrowing): ?>
                                        <tr>
                                            <td><?php echo escape($borrowing['user_first_name'] . ' ' . $borrowing['user_last_name']); ?></td>
                                            <td><?php echo truncate($borrowing['title'], 40); ?></td>
                                            <td><?php echo format_date($borrowing['borrowed_date']); ?></td>
                                            <td><?php echo format_date($borrowing['due_date']); ?></td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <?php echo $borrowing['days_overdue']; ?> jours
                                                </span>
                                            </td>
                                            <td>
                                                <a href="mailto:<?php echo escape($borrowing['user_email']); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-envelope"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
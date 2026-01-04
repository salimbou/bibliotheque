<?php
$pageTitle = 'Mon Espace';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="list-group">
                <a href="<?php echo url('/lecteur/dashboard'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-speedometer2"></i> Mon Espace
                </a>
                <a href="<?php echo url('/lecteur/books'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-book"></i> Catalogue
                </a>
                <a href="<?php echo url('/lecteur/borrowings'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-arrow-repeat"></i> Mes Emprunts
                </a>
                <a href="<?php echo url('/lecteur/profile'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-person"></i> Mon Profil
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Mon Espace Lecteur</h1>
            
            <?php display_flash(); ?>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h2 class="mb-0"><?php echo count($activeBorrowings); ?></h2>
                            <p class="mb-0">Emprunts actifs</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h2 class="mb-0"><?php echo count($overdueBorrowings); ?></h2>
                            <p class="mb-0">En retard</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h2 class="mb-0"><?php echo $returnedCount; ?></h2>
                            <p class="mb-0">Livres rendus</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h2 class="mb-0"><?php echo count($reviews); ?></h2>
                            <p class="mb-0">Mes avis</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Overdue Warning -->
            <?php if (!empty($overdueBorrowings)): ?>
                <div class="alert alert-danger">
                    <h5><i class="bi bi-exclamation-triangle"></i> Attention !</h5>
                    <p class="mb-0">Vous avez <?php echo count($overdueBorrowings); ?> emprunt(s) en retard. Veuillez les retourner dès que possible.</p>
                </div>
            <?php endif; ?>
            
            <!-- Active Borrowings -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Mes Emprunts en Cours</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($activeBorrowings) && empty($overdueBorrowings)): ?>
                        <p class="text-muted">Vous n'avez aucun emprunt en cours</p>
                        <a href="<?php echo url('/lecteur/books'); ?>" class="btn btn-primary">
                            <i class="bi bi-search"></i> Parcourir le catalogue
                        </a>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Livre</th>
                                        <th>Date emprunt</th>
                                        <th>Date retour prévue</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_merge($activeBorrowings, $overdueBorrowings) as $borrowing): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo escape($borrowing['title']); ?></strong><br>
                                                <small class="text-muted"><?php echo escape($borrowing['author']); ?></small>
                                            </td>
                                            <td><?php echo format_date($borrowing['borrowed_date']); ?></td>
                                            <td><?php echo format_date($borrowing['due_date']); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = $borrowing['status'] === 'overdue' ? 'danger' : 'primary';
?>
<span class="badge bg-<?php echo $statusClass; ?>">
<?php echo $borrowing['status'] === 'overdue' ? 'En retard' : 'Actif'; ?>
</span>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<a href="<?php echo url('/lecteur/borrowings'); ?>" class="btn btn-outline-primary">
Voir tous mes emprunts
</a>
<?php endif; ?>
</div>
</div><!-- Recent Reviews -->
        <?php if (!empty($reviews)): ?>
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Mes Derniers Avis</h5>
                </div>
                <div class="card-body">
                    <?php foreach (array_slice($reviews, 0, 3) as $review): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-start">
                                <?php if (!empty($review['cover_image'])): ?>
                                    <img src="<?php echo asset('images/uploads/' . $review['cover_image']); ?>" 
                                         alt="<?php echo escape($review['book_title']); ?>" 
                                         style="width: 60px; height: 80px; object-fit: cover;" 
                                         class="me-3">
                                <?php endif; ?>
                                <div class="flex-grow-1">
                                    <h6><?php echo escape($review['book_title']); ?></h6>
                                    <div class="text-warning mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="mb-1"><?php echo truncate($review['review_text'], 150); ?></p>
                                    <small class="text-muted">
                                        <?php
                                        $statusBadge = match($review['status']) {
                                            'approved' => '<span class="badge bg-success">Approuvé</span>',
                                            'pending' => '<span class="badge bg-warning">En attente</span>',
                                            'rejected' => '<span class="badge bg-danger">Rejeté</span>',
                                            default => ''
                                        };
                                        echo $statusBadge;
                                        ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
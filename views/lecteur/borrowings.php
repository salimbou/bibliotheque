<?php
$pageTitle = 'Mes Emprunts';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
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
                <a href="<?php echo url('/lecteur/borrowings'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-arrow-repeat"></i> Mes Emprunts
                </a>
                <a href="<?php echo url('/lecteur/profile'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-person"></i> Mon Profil
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="mb-4"><i class="bi bi-arrow-repeat"></i> Mes Emprunts</h1>
            
            <?php display_flash(); ?>
            
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a href="<?php echo url('/lecteur/borrowings'); ?>" 
                           class="btn <?php echo !isset($_GET['status']) ? 'btn-primary' : 'btn-outline-primary'; ?>">
                            Tous
                        </a>
                        <a href="<?php echo url('/lecteur/borrowings?status=active'); ?>" 
                           class="btn <?php echo (isset($_GET['status']) && $_GET['status'] === 'active') ? 'btn-primary' : 'btn-outline-primary'; ?>">
                            Actifs
                        </a>
                        <a href="<?php echo url('/lecteur/borrowings?status=overdue'); ?>" 
                           class="btn <?php echo (isset($_GET['status']) && $_GET['status'] === 'overdue') ? 'btn-danger' : 'btn-outline-danger'; ?>">
                            En retard
                        </a>
                        <a href="<?php echo url('/lecteur/borrowings?status=returned'); ?>" 
                           class="btn <?php echo (isset($_GET['status']) && $_GET['status'] === 'returned') ? 'btn-success' : 'btn-outline-success'; ?>">
                            Retournés
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Borrowings List -->
            <?php if (empty($borrowings)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucun emprunt trouvé
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($borrowings as $borrowing): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <?php if (!empty($borrowing['cover_image'])): ?>
                                                <img src="<?php echo asset('images/uploads/' . $borrowing['cover_image']); ?>" 
                                                     class="img-fluid rounded" alt="<?php echo escape($borrowing['title']); ?>">
                                            <?php else: ?>
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                                                     style="height: 150px;">
                                                    <i class="bi bi-book" style="font-size: 3rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-8">
                                            <h5 class="card-title"><?php echo escape($borrowing['title']); ?></h5>
                                            <p class="card-text text-muted"><?php echo escape($borrowing['author']); ?></p>
                                            
                                            <p class="mb-1">
                                                <strong>Emprunté le:</strong> <?php echo format_date($borrowing['borrowed_date']); ?>
                                            </p>
                                            <p class="mb-1">
                                                <strong>À retourner le:</strong> <?php echo format_date($borrowing['due_date']); ?>
                                            </p>
                                            
                                            <?php if ($borrowing['return_date']): ?>
                                                <p class="mb-1">
                                                    <strong>Retourné le:</strong> <?php echo format_date($borrowing['return_date']); ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <div class="mt-2">
                                                <?php
                                                $statusClass = match($borrowing['status']) {
                                                    'active' => 'primary',
                                                    'returned' => 'success',
                                                    'overdue' => 'danger',
                                                    default => 'secondary'
                                                };
                                                $statusText = match($borrowing['status']) {
                                                    'active' => 'En cours',
                                                    'returned' => 'Retourné',
                                                    'overdue' => 'En retard',
                                                    default => $borrowing['status']
                                                };
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                    <?php echo $statusText; ?>
                                                </span>
                                            </div>
                                            
                                            <?php if ($borrowing['status'] === 'overdue'): ?>
                                                <div class="alert alert-danger mt-2 mb-0 py-1 px-2 small">
                                                    <i class="bi bi-exclamation-triangle"></i> Veuillez retourner ce livre dès que possible
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
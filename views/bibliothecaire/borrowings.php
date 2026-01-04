<?php
$pageTitle = 'Gestion des Emprunts';
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
                <h1><i class="bi bi-arrow-repeat"></i> Gestion des Emprunts</h1>
                <a href="<?php echo url('/bibliothecaire/borrowings/create'); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvel emprunt
                </a>
            </div>
            
            <?php display_flash(); ?>
            
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo url('/bibliothecaire/borrowings'); ?>" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="active" <?php echo ($status ?? '') === 'active' ? 'selected' : '';
                                ?>>Actifs</option>
<option value="overdue" <?php echo ($status ?? '') === 'overdue' ? 'selected' : ''; ?>>En retard</option>
<option value="returned" <?php echo ($status ?? '') === 'returned' ? 'selected' : ''; ?>>Retournés</option>
</select>
</div>
<div class="col-md-2 d-flex align-items-end">
<button type="submit" class="btn btn-primary w-100">
<i class="bi bi-search"></i> Filtrer
</button>
</div>
</form>
</div>
</div>
<!-- Borrowings Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Liste des Emprunts (<?php echo $total; ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($borrowings)): ?>
                    <p class="text-muted">Aucun emprunt trouvé</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Livre</th>
                                    <th>Date emprunt</th>
                                    <th>Date retour prévue</th>
                                    <th>Date retour réel</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($borrowings as $borrowing): ?>
                                    <tr>
                                        <td><?php echo $borrowing['id']; ?></td>
                                        <td>
                                            <?php echo escape($borrowing['user_first_name'] . ' ' . $borrowing['user_last_name']); ?><br>
                                            <small class="text-muted"><?php echo escape($borrowing['user_email']); ?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo truncate($borrowing['title'], 40); ?></strong><br>
                                            <small class="text-muted"><?php echo escape($borrowing['author']); ?></small>
                                        </td>
                                        <td><?php echo format_date($borrowing['borrowed_date']); ?></td>
                                        <td><?php echo format_date($borrowing['due_date']); ?></td>
                                        <td>
                                            <?php echo $borrowing['return_date'] ? format_date($borrowing['return_date']) : '-'; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = match($borrowing['status']) {
                                                'active' => 'primary',
                                                'returned' => 'success',
                                                'overdue' => 'danger',
                                                'lost' => 'dark',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <?php echo escape($borrowing['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (in_array($borrowing['status'], ['active', 'overdue'])): ?>
                                                <form method="POST" action="<?php echo url('/bibliothecaire/borrowings/return'); ?>" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="borrowing_id" value="<?php echo $borrowing['id']; ?>">
                                                    <button type="submit" class="btn btn-success btn-sm" 
                                                            onclick="return confirm('Marquer ce livre comme retourné ?')">
                                                        <i class="bi bi-check-circle"></i> Retourner
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pagination['total_pages'] > 1): ?>
                        <nav class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php if ($pagination['has_prev']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $status ? '&status=' . $status : ''; ?>">
                                            Précédent
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                    <li class="page-item <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo $status ? '&status=' . $status : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['has_next']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $status ? '&status=' . $status : ''; ?>">
                                            Suivant
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
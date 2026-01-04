<?php
$pageTitle = 'Gestion des Utilisateurs';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar -->
          <div class="col-md-3 col-lg-2">
        <div class="list-group">
    <a href="<?php echo url('/admin/dashboard'); ?>" class="list-group-item list-group-item-action <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
        <i class="bi bi-speedometer2"></i> Tableau de bord
    </a>
    <a href="<?php echo url('/admin/users'); ?>" class="list-group-item list-group-item-action <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
        <i class="bi bi-people"></i> Utilisateurs
    </a>
    <a href="<?php echo url('/admin/stats'); ?>" class="list-group-item list-group-item-action <?php echo basename($_SERVER['PHP_SELF']) == 'stats.php' ? 'active' : ''; ?>">
        <i class="bi bi-graph-up"></i> Statistiques
    </a>
    <a href="<?php echo url('/bibliothecaire/books'); ?>" class="list-group-item list-group-item-action">
        <i class="bi bi-book"></i> Livres
    </a>
    <a href="<?php echo url('/bibliothecaire/events'); ?>" class="list-group-item list-group-item-action">
        <i class="bi bi-calendar-event"></i> Événements
    </a>
    <a href="<?php echo url('/admin/profile'); ?>" class="list-group-item list-group-item-action <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
        <i class="bi bi-person-circle"></i> Mon Profil
    </a>
</div>
    </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="mb-4"><i class="bi bi-people"></i> Gestion des Utilisateurs</h1>
            
            <?php display_flash(); ?>
            
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo url('/admin/users'); ?>" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Rôle</label>
                            <select name="role" class="form-select">
                                <option value="">Tous les rôles</option>
                                <option value="lecteur" <?php echo ($role ?? '') === 'lecteur' ? 'selected' : ''; ?>>Lecteur</option>
                                <option value="bibliothecaire" <?php echo ($role ?? '') === 'bibliothecaire' ? 'selected' : ''; ?>>Bibliothécaire</option>
                                <option value="admin" <?php echo ($role ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rechercher</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Nom, email..." value="<?php echo escape($search ?? ''); ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Liste des Utilisateurs (<?php echo $total; ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <p class="text-muted">Aucun utilisateur trouvé</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Rôle</th>
                                        <th>Statut</th>
                                        <th>Inscrit le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo escape($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                            <td><?php echo escape($user['email']); ?></td>
                                            <td><?php echo escape($user['phone'] ?? '-'); ?></td>
                                            <td>
                                                <?php
                                                $roleClass = match($user['role']) {
                                                    'admin' => 'danger',
                                                    'bibliothecaire' => 'warning',
                                                    'lecteur' => 'info',
                                                    default => 'secondary'
                                                };
                                                ?>
                                                <span class="badge bg-<?php echo $roleClass; ?>">
                                                    <?php echo escape($user['role']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = match($user['status']) {
                                                    'active' => 'success',
                                                    'pending' => 'warning',
                                                    'inactive' => 'secondary',
                                                    default => 'secondary'
                                                };
                                                ?>
                                                <span class="badge bg-<?php echo $statusClass; ?>">
                                                    <?php echo escape($user['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo format_date($user['created_at']); ?></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <?php if ($user['status'] === 'pending' || $user['status'] === 'inactive'): ?>
                                                        <form method="POST" action="<?php echo url('/admin/users/activate'); ?>" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <button type="submit" class="btn btn-success btn-sm" 
                                                                    onclick="return confirm('Activer cet utilisateur ?')">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($user['status'] === 'active' && $user['id'] !== Session::getUserId()): ?>
                                                        <form method="POST" action="<?php echo url('/admin/users/deactivate'); ?>" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <button type="submit" class="btn btn-warning btn-sm" 
                                                                    onclick="return confirm('Désactiver cet utilisateur ?')">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($user['id'] !== Session::getUserId()): ?>
                                                        <form method="POST" action="<?php echo url('/admin/users/delete'); ?>" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                                    onclick="return confirm('Supprimer définitivement cet utilisateur ?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
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
                                            <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $role ? '&role=' . $role : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                                Précédent
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                        <li class="page-item <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo $role ? '&role=' . $role : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($pagination['has_next']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $role ? '&role=' . $role : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
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
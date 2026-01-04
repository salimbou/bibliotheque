<?php
$pageTitle = 'Tableau de bord Admin';
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
            <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Tableau de bord Admin</h1>
            
            <?php display_flash(); ?>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Utilisateurs</h6>
                                    <h2 class="mb-0"><?php echo $userStats['total']; ?></h2>
                                </div>
                                <div>
                                    <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                            <small>
                                <i class="bi bi-check-circle"></i> <?php echo $userStats['active']; ?> actifs
                                | <i class="bi bi-clock"></i> <?php echo $userStats['pending']; ?> en attente
                            </small>
                        </div>
                    </div>
                </div>
                
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
                            <small>
                                <i class="bi bi-check"></i> <?php echo $bookStats['available_copies']; ?> disponibles
                                | <i class="bi bi-bookmark"></i> <?php echo $bookStats['borrowed_copies']; ?> empruntés
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Emprunts</h6>
                                    <h2 class="mb-0"><?php echo $borrowingStats['active']; ?></h2>
                                </div>
                                <div>
                                    <i class="bi bi-arrow-repeat" style="font-size: 3rem; opacity: 0.5;"></i>
                                </div>
                            </div>
                            <small>
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $borrowingStats['overdue']; ?> en retard
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
                            <small>
                                <i class="bi bi-people"></i> <?php echo $eventStats['total_participants']; ?> participants
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Users and Overdue Borrowings -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-plus"></i> Utilisateurs Récents</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($recentUsers)): ?>
                                <p class="text-muted">Aucun utilisateur récent</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Email</th>
                                                <th>Rôle</th>
                                                <th>Statut</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentUsers as $user): ?>
                                                <tr>
                                                    <td><?php echo escape($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                                    <td><?php echo escape($user['email']); ?></td>
                                                    <td>
                                                        <span class="badge bg-secondary">
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
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="<?php echo url('/admin/users'); ?>" class="btn btn-sm btn-primary">
                                    Voir tous les utilisateurs
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Emprunts en Retard</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($overdueBorrowings)): ?>
                                <p class="text-muted">Aucun emprunt en retard</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Utilisateur</th>
                                                <th>Livre</th>
                                                <th>Date retour</th>
                                                <th>Jours retard</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (array_slice($overdueBorrowings, 0, 5) as $borrowing): ?>
                                                <tr>
                                                    <td><?php echo escape($borrowing['user_first_name'] . ' ' . $borrowing['user_last_name']); ?></td>
                                                    <td><?php echo truncate($borrowing['title'], 30); ?></td>
                                                    <td><?php echo format_date($borrowing['due_date']); ?></td>
                                                    <td>
                                                        <span class="badge bg-danger">
                                                            <?php echo $borrowing['days_overdue']; ?> jours
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="<?php echo url('/bibliothecaire/borrowings?status=overdue'); ?>" class="btn btn-sm btn-danger">
                                    Voir tous les retards
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
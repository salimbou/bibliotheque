<?php
$pageTitle = 'Statistiques';
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
            <h1 class="mb-4"><i class="bi bi-graph-up"></i> Statistiques Globales</h1>
            
            <!-- User Statistics -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Statistiques Utilisateurs</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6>Total</h6>
                            <h2><?php echo $userStats['total']; ?></h2>
                        </div>
                        <div class="col-md-3">
                            <h6>Lecteurs</h6>
                            <h2 class="text-info"><?php echo $userStats['lecteurs']; ?></h2>
                        </div>
                        <div class="col-md-3">
                            <h6>Bibliothécaires</h6>
                            <h2 class="text-warning"><?php echo $userStats['bibliothecaires']; ?></h2>
                        </div>
                        <div class="col-md-3">
                            <h6>Admins</h6>
                            <h2 class="text-danger"><?php echo $userStats['admins']; ?></h2>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <h6>Actifs</h6>
                            <h3 class="text-success"><?php echo $userStats['active']; ?></h3>
                        </div>
                        <div class="col-md-4">
                            <h6>En attente</h6>
                            <h3 class="text-warning"><?php echo $userStats['pending']; ?></h3>
                    </div>
                    <div class="col-md-4">
                        <h6>Inactifs</h6>
                        <h3 class="text-secondary"><?php echo $userStats['inactive']; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Book Statistics -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-book"></i> Statistiques Livres</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6>Total Livres</h6>
                        <h2><?php echo $bookStats['total_books']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>Total Exemplaires</h6>
                        <h2><?php echo $bookStats['total_copies']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>Disponibles</h6>
                        <h2 class="text-success"><?php echo $bookStats['available_copies']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>Empruntés</h6>
                        <h2 class="text-warning"><?php echo $bookStats['borrowed_copies']; ?></h2>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h6>Catégories</h6>
                        <h3><?php echo $bookStats['total_categories']; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Borrowing Statistics -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Statistiques Emprunts</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6>Total Emprunts</h6>
                        <h2><?php echo $borrowingStats['total_borrowings']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>Actifs</h6>
                        <h2 class="text-primary"><?php echo $borrowingStats['active']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>En retard</h6>
                        <h2 class="text-danger"><?php echo $borrowingStats['overdue']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>Retournés</h6>
                        <h2 class="text-success"><?php echo $borrowingStats['returned']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Event Statistics -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Statistiques Événements</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6>Total Événements</h6>
                        <h2><?php echo $eventStats['total_events']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>À venir</h6>
                        <h2 class="text-primary"><?php echo $eventStats['upcoming']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>Terminés</h6>
                        <h2 class="text-success"><?php echo $eventStats['completed']; ?></h2>
                    </div>
                    <div class="col-md-3">
                        <h6>Participants</h6>
                        <h2 class="text-info"><?php echo $eventStats['total_participants']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
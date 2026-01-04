<?php
$pageTitle = 'Espace Scolaire';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 mb-3">
            <i class="bi bi-mortarboard-fill text-success"></i> Espace Scolaire
        </h1>
        <p class="lead">Livres, manuels et exercices pour tous les niveaux scolaires</p>
    </div>
    
    <?php display_flash(); ?>
    
    <!-- Search Bar -->
    <div class="card mb-5 shadow">
        <div class="card-body">
            <form action="<?php echo url('/school/exercises'); ?>" method="GET" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control form-control-lg" 
                           placeholder="Rechercher des exercices, livres..." 
                           value="<?php echo escape($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- School Cycles -->
    <?php foreach ($levelsByCycle as $cycle => $levels): ?>
        <?php if (!empty($levels)): ?>
            <section class="mb-5">
                <?php
                $cycleInfo = match($cycle) {
                    'primaire' => ['title' => 'Enseignement Primaire', 'icon' => 'bi-backpack', 'color' => '#3498db'],
                    'moyenne' => ['title' => 'Enseignement Moyen (CEM)', 'icon' => 'bi-journal-text', 'color' => '#e74c3c'],
                    'secondaire' => ['title' => 'Enseignement Secondaire (Lycée)', 'icon' => 'bi-mortarboard', 'color' => '#27ae60'],
                    default => ['title' => $cycle, 'icon' => 'bi-book', 'color' => '#95a5a6']
                };
                ?>
                
                <div class="d-flex align-items-center mb-4">
                    <i class="<?php echo $cycleInfo['icon']; ?> me-3" 
                       style="font-size: 2.5rem; color: <?php echo $cycleInfo['color']; ?>"></i>
                    <h2 class="mb-0"><?php echo $cycleInfo['title']; ?></h2>
                </div>
                
                <div class="row">
                    <?php foreach ($levels as $level): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="<?php echo url('/school/level?slug=' . $level['slug']); ?>" 
                               class="text-decoration-none">
                                <div class="card h-100 shadow-sm hover-lift" 
                                     style="border-left: 4px solid <?php echo $cycleInfo['color']; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo escape($level['name']); ?></h5>
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-person"></i> <?php echo escape($level['age_range']); ?>
                                        </p>
                                        <p class="card-text small"><?php echo escape($level['description']); ?></p>
                                        <div class="d-flex justify-content-between mt-3">
                                            <span class="badge bg-primary">
                                                <i class="bi bi-book"></i> Livres
                                            </span>
                                            <span class="badge bg-success">
                                                <i class="bi bi-file-earmark-text"></i> Exercices
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <small class="text-primary">
                                            Accéder aux ressources <i class="bi bi-arrow-right"></i>
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <!-- Popular Exercises -->
    <?php if (!empty($popularExercises)): ?>
        <section class="mb-5">
            <h2 class="mb-4"><i class="bi bi-star-fill text-warning"></i> Exercices Populaires</h2>
            <div class="row">
                <?php foreach ($popularExercises as $exercise): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge" style="background-color: <?php echo $exercise['subject_color']; ?>">
                                        <i class="<?php echo $exercise['subject_icon']; ?>"></i>
                                        <?php echo escape($exercise['subject_name']); ?>
                                    </span>
                                    <span class="badge bg-secondary"><?php echo escape($exercise['difficulty']); ?></span>
                                </div>
                                <h6 class="card-title"><?php echo truncate($exercise['title'], 60); ?></h6>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-mortarboard"></i> <?php echo escape($exercise['level_name']); ?>
                                </p>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span><i class="bi bi-eye"></i> <?php echo $exercise['view_count']; ?></span>
                                    <span><i class="bi bi-download"></i> <?php echo $exercise['download_count']; ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="<?php echo url('/school/exercise?id=' . $exercise['id']); ?>" 
                                   class="btn btn-sm btn-outline-success w-100">
                                    Voir l'exercice
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center">
                <a href="<?php echo url('/school/exercises'); ?>" class="btn btn-success">
                    Voir tous les exercices <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>
    <?php endif; ?>
</div>

<style>
.hover-lift {
    transition: transform 0.3s, box-shadow 0.3s;
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}
</style>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
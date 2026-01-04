<?php
$pageTitle = 'Exercices Scolaires';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <h1 class="mb-4"><i class="bi bi-file-earmark-text"></i> Exercices et Devoirs</h1>
    
    <?php display_flash(); ?>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo url('/school/exercises'); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Niveau</label>
                    <select name="level" class="form-select">
                        <option value="">Tous les niveaux</option>
                        <?php foreach ($levels as $level): ?>
                            <option value="<?php echo $level['id']; ?>" 
                                    <?php echo (isset($_GET['level']) && $_GET['level'] == $level['id']) ? 'selected' : ''; ?>>
                                <?php echo escape($level['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Matière</label>
                    <select name="subject" class="form-select">
                        <option value="">Toutes les matières</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo $subject['id']; ?>" 
                                    <?php echo (isset($_GET['subject']) && $_GET['subject'] == $subject['id']) ? 'selected' : ''; ?>>
                                <?php echo escape($subject['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Difficulté</label>
                    <select name="difficulty" class="form-select">
                        <option value="">Toutes</option>
                        <option value="facile" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] == 'facile') ? 'selected' : ''; ?>>Facile</option>
                        <option value="moyen" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] == 'moyen') ? 'selected' : ''; ?>>Moyen</option>
                        <option value="difficile" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] == 'difficile') ? 'selected' : ''; ?>>Difficile</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Rechercher</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Titre..." 
                           value="<?php echo escape($_GET['search'] ?? ''); ?>">
                </div>
                
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Results Count -->
    <div class="mb-3">
        <p class="text-muted">
            <strong><?php echo $total; ?></strong> exercice(s) trouvé(s)
        </p>
    </div>
    
    <!-- Exercises Grid -->
    <?php if (empty($exercises)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Aucun exercice trouvé avec ces critères.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($exercises as $exercise): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm exercise-card">
                        <div class="card-header" style="background-color: <?php echo $exercise['subject_color']; ?>15;">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge" style="background-color: <?php echo $exercise['subject_color']; ?>">
                                    <i class="<?php echo $exercise['subject_icon']; ?>"></i>
                                    <?php echo escape($exercise['subject_name']); ?>
                                </span>
                                <span class="badge bg-secondary"><?php echo ucfirst($exercise['difficulty']); ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title"><?php echo truncate($exercise['title'], 70); ?></h6>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-mortarboard"></i> <?php echo escape($exercise['level_name']); ?>
                            </p>
                            <p class="card-text small text-muted"><?php echo truncate($exercise['description'], 100); ?></p>
                            <?php if ($exercise['duration_minutes']): ?>
                                <p class="small text-muted mb-2">
                                    <i class="bi bi-clock"></i> Durée: <?php echo $exercise['duration_minutes']; ?> min
                                </p>
                            <?php endif; ?>
                            <?php if ($exercise['has_solutions']): ?>
                                <span class="badge bg-info">
                                    <i class="bi bi-check-circle"></i> Avec corrections
                                </span>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between text-muted small mt-3">
                                <span><i class="bi bi-eye"></i> <?php echo $exercise['view_count']; ?></span>
                                <span><i class="bi bi-download"></i> <?php echo $exercise['download_count']; ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="<?php echo url('/school/exercise?id=' . $exercise['id']); ?>" 
                               class="btn btn-sm btn-success w-100">
                                <i class="bi bi-eye"></i> Voir l'exercice
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($pagination['has_prev']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo http_build_query(array_diff_key($_GET, ['page' => ''])); ?>">
                                Précédent
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <li class="page-item <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&<?php echo http_build_query(array_diff_key($_GET, ['page' => ''])); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['has_next']): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo http_build_query(array_diff_key($_GET, ['page' => ''])); ?>">
                                Suivant
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.exercise-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.exercise-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}
</style>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
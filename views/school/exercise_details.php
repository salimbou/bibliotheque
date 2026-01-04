<?php
$pageTitle = $exercise['title'];
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('/school'); ?>">Espace Scolaire</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('/school/exercises'); ?>">Exercices</a></li>
            <li class="breadcrumb-item active"><?php echo truncate($exercise['title'], 40); ?></li>
        </ol>
    </nav>
    
    <?php display_flash(); ?>
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Exercise Header -->
            <div class="card mb-4 shadow">
                <div class="card-header" style="background: linear-gradient(135deg, <?php echo $exercise['subject_color']; ?> 0%, <?php echo $exercise['subject_color']; ?>dd 100%);">
                    <div class="text-white">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-light text-dark">
                                <i class="<?php echo $exercise['subject_icon']; ?>"></i>
                                <?php echo escape($exercise['subject_name']); ?>
                            </span>
                            <span class="badge bg-dark"><?php echo ucfirst($exercise['difficulty']); ?></span>
                        </div>
                        <h2 class="mb-2"><?php echo escape($exercise['title']); ?></h2>
                        <p class="mb-0">
                            <i class="bi bi-mortarboard"></i> <?php echo escape($exercise['level_name']); ?>
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-primary me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Durée</small>
                                    <strong><?php echo $exercise['duration_minutes'] ? $exercise['duration_minutes'] . ' min' : 'Non spécifiée'; ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-eye text-success me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Vues</small>
                                    <strong><?php echo $exercise['view_count']; ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-download text-info me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <small class="text-muted d-block">Téléchargements</small>
                                    <strong><?php echo $exercise['download_count']; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5>Description</h5>
                    <p><?php echo nl2br(escape($exercise['description'])); ?></p>
                    
                    <?php if (!empty($exercise['tags'])): ?>
                        <div class="mb-3">
                            <h6>Tags</h6>
                            <?php foreach ($exercise['tags'] as $tag): ?>
                                <span class="badge bg-secondary me-1">
                                    #<?php echo escape($tag['name']); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($exercise['book_title']): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-book"></i>
                            <strong>Livre associé:</strong> <?php echo escape($exercise['book_title']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Download Buttons -->
                    <div class="d-grid gap-2 mt-4">
                        <?php if ($exercise['file_path']): ?>
                            <a href="<?php echo url('/school/download?id=' . $exercise['id']); ?>" 
                               class="btn btn-success btn-lg">">
<i class="bi bi-download"></i> Télécharger l'exercice (<?php echo strtoupper($exercise['file_type']); ?>)
</a>
<?php endif; ?>
<?php if ($exercise['has_solutions'] && $exercise['solutions_file']): ?>
                        <a href="<?php echo url('/school/download-solution?id=' . $exercise['id']); ?>" 
                           class="btn btn-outline-success btn-lg">
                            <i class="bi bi-check-circle"></i> Télécharger les corrections
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="mt-3 text-muted small">
                    <i class="bi bi-info-circle"></i>
                    Ajouté le <?php echo format_date($exercise['created_at']); ?>
                    <?php if ($exercise['first_name']): ?>
                        par <?php echo escape($exercise['first_name'] . ' ' . $exercise['last_name']); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Quick Info -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Niveau:</strong></td>
                        <td>
                            <a href="<?php echo url('/school/level?slug=' . $exercise['level_slug']); ?>" class="text-decoration-none">
                                <?php echo escape($exercise['level_name']); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Matière:</strong></td>
                        <td><?php echo escape($exercise['subject_name']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Difficulté:</strong></td>
                        <td>
                            <?php
                            $difficultyColor = match($exercise['difficulty']) {
                                'facile' => 'success',
                                'moyen' => 'warning',
                                'difficile' => 'danger',
                                default => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?php echo $difficultyColor; ?>">
                                <?php echo ucfirst($exercise['difficulty']); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Format:</strong></td>
                        <td><?php echo strtoupper($exercise['file_type']); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Related Exercises -->
        <?php if (!empty($relatedExercises)): ?>
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-collection"></i> Exercices similaires</h5>
                </div>
                <div class="list-group list-group-flush">
                    <?php foreach ($relatedExercises as $related): ?>
                        <?php if ($related['id'] != $exercise['id']): ?>
                            <a href="<?php echo url('/school/exercise?id=' . $related['id']); ?>" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 small"><?php echo truncate($related['title'], 50); ?></h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> <?php echo $related['duration_minutes']; ?> min
                                        </small>
                                    </div>
                                    <span class="badge bg-secondary"><?php echo ucfirst($related['difficulty']); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
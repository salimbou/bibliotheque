<?php
$pageTitle = $level['name'];
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('/school'); ?>">Espace Scolaire</a></li>
            <li class="breadcrumb-item active"><?php echo escape($level['name']); ?></li>
        </ol>
    </nav>
    
    <!-- Level Header -->
    <div class="card mb-5 shadow-lg border-0">
        <div class="card-body p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="row align-items-center text-white">
                <div class="col-md-8">
                    <h1 class="display-5 mb-3">
                        <i class="bi bi-mortarboard-fill"></i> <?php echo escape($level['name']); ?>
                    </h1>
                    <p class="lead mb-2"><?php echo escape($level['description']); ?></p>
                    <p class="mb-0">
                        <i class="bi bi-person"></i> <strong>Tranche d'âge:</strong> <?php echo escape($level['age_range']); ?>
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="bg-white rounded p-4 text-dark">
                        <h2 class="text-primary mb-2"><?php echo $levelDetails['book_count']; ?></h2>
                        <p class="mb-3">Livres disponibles</p>
                        <h2 class="text-success mb-2"><?php echo $levelDetails['exercise_count']; ?></h2>
                        <p class="mb-0">Exercices</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Subjects -->
    <?php if (!empty($subjects)): ?>
        <section class="mb-5">
            <h3 class="mb-4"><i class="bi bi-journal-text"></i> Matières Disponibles</h3>
            <div class="row">
                <?php foreach ($subjects as $subject): ?>
                    <div class="col-md-3 col-lg-2 mb-3">
                        <a href="<?php echo url('/school/exercises?level=' . $level['id'] . '&subject=' . $subject['id']); ?>" 
                           class="text-decoration-none">
                            <div class="card text-center shadow-sm hover-card" style="border-top: 3px solid <?php echo $subject['color']; ?>">
                                <div class="card-body">
                                    <i class="<?php echo $subject['icon']; ?> mb-2" 
                                       style="font-size: 2rem; color: <?php echo $subject['color']; ?>"></i>
                                    <h6 class="card-title small"><?php echo escape($subject['name']); ?></h6>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Books Section -->
    <?php if (!empty($books)): ?>
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-book"></i> Livres et Manuels</h3>
                <a href="<?php echo url('/lecteur/books?level=' . $level['id']); ?>" class="btn btn-outline-primary">
                    Voir tous les livres <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="row">
                <?php foreach ($books as $book): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if (!empty($book['cover_image'])): ?>
                                <img src="<?php echo asset('images/uploads/' . $book['cover_image']); ?>" 
                                     class="card-img-top" alt="<?php echo escape($book['title']); ?>" 
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="bi bi-book" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo truncate($book['title'], 50); ?></h6>
                                <p class="card-text text-muted small"><?php echo escape($book['author']); ?></p>
                                <?php if ($book['available_copies'] > 0): ?>
                                    <span class="badge bg-success">Disponible</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Non disponible</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="<?php echo url('/lecteur/books/details?id=' . $book['id']); ?>" 
                                   class="btn btn-sm btn-primary w-100">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Exercises Section -->
    <?php if (!empty($exercises)): ?>
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="bi bi-file-earmark-text"></i> Exercices et Devoirs</h3>
                <a href="<?php echo url('/school/exercises?level=' . $level['id']); ?>" class="btn btn-outline-success">
                    Voir tous les exercices <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="row">
                <?php foreach ($exercises as $exercise): ?>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header" style="background-color: <?php echo $exercise['subject_color']; ?>15;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge" style="background-color: <?php echo $exercise['subject_color']; ?>">
                                        <i class="<?php echo $exercise['subject_icon']; ?>"></i>
                                        <?php echo escape($exercise['subject_name']); ?>
                                    </span>
                                    <span class="badge bg-secondary"><?php echo escape($exercise['difficulty']); ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title"><?php echo truncate($exercise['title'], 60); ?></h6>
                                <p class="card-text small text-muted"><?php echo truncate($exercise['description'], 80); ?></p>
                                <?php if ($exercise['duration_minutes']): ?>
                                    <p class="small text-muted mb-2">
                                        <i class="bi bi-clock"></i> <?php echo $exercise['duration_minutes']; ?> min
                                    </p>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span><i class="bi bi-eye"></i> <?php echo $exercise['view_count']; ?></span>
                                    <span><i class="bi bi-download"></i> <?php echo $exercise['download_count']; ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="<?php echo url('/school/exercise?id=' . $exercise['id']); ?>" 
                                   class="btn btn-sm btn-success w-100">
                                    Accéder <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<style>
.hover-card {
    transition: all 0.3s;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
}
</style>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
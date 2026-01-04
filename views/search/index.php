<?php
$pageTitle = !empty($query) ? 'Résultats pour "' . $query . '"' : 'Recherche';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <!-- Search Header -->
    <div class="mb-4">
        <?php if (!empty($query)): ?>
            <h1 class="mb-3">Résultats de recherche pour: <span class="text-primary">"<?php echo escape($query); ?>"</span></h1>
        <?php else: ?>
            <h1 class="mb-3"><i class="bi bi-search"></i> Recherche Avancée</h1>
        <?php endif; ?>
        
        <!-- Search Bar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="<?php echo url('/search'); ?>" method="GET">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="q" class="form-control" 
                                       placeholder="Rechercher des livres, exercices..." 
                                       value="<?php echo escape($query); ?>"
                                       id="searchInput"
                                       autocomplete="off">
                            </div>
                            <div id="suggestions" class="list-group position-absolute w-100" style="z-index: 1000; display: none;"></div>
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select form-select-lg">
                                <option value="all" <?php echo ($type ?? 'all') === 'all' ? 'selected' : ''; ?>>Tout</option>
                                <option value="books" <?php echo ($type ?? '') === 'books' ? 'selected' : ''; ?>>Livres</option>
                                <option value="exercises" <?php echo ($type ?? '') === 'exercises' ? 'selected' : ''; ?>>Exercices</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Rechercher</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtres</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('/search'); ?>" method="GET" id="filterForm">
                        <input type="hidden" name="q" value="<?php echo escape($query); ?>">
                        <input type="hidden" name="type" value="<?php echo escape($type); ?>">
                        
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catégorie</label>
                            <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Toutes</option>
                                <?php foreach ($categories as $cat): ?>
                                    <?php if ($cat['parent_id'] === null): ?>
                                        <option value="<?php echo $cat['id']; ?>" 
                                                <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo escape($cat['name']); ?> (<?php echo $cat['book_count']; ?>)
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Level Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Niveau Scolaire</label>
                            <select name="level" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <?php foreach ($levels as $level): ?>
                                    <option value="<?php echo $level['id']; ?>" 
                                            <?php echo (isset($_GET['level']) && $_GET['level'] == $level['id']) ? 'selected' : ''; ?>>
                                        <?php echo escape($level['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Subject Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Matière</label>
                            <select name="subject" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Toutes</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?php echo $subject['id']; ?>" 
                                            <?php echo (isset($_GET['subject']) && $_GET['subject'] == $subject['id']) ? 'selected' : ''; ?>>
                                        <?php echo escape($subject['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Difficulty Filter (for exercises) -->
                        <?php if ($type === 'all' || $type === 'exercises'): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Difficulté</label>
                                <select name="difficulty" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">Toutes</option>
                                    <option value="facile" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] === 'facile') ? 'selected' : ''; ?>>Facile</option>
                                    <option value="moyen" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] === 'moyen') ? 'selected' : ''; ?>>Moyen</option>
                                    <option value="difficile" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] === 'difficile') ? 'selected' : ''; ?>>Difficile</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Availability Filter -->
                        <?php if ($type === 'all' || $type === 'books'): ?>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="available" value="1" 
                                           id="availableOnly" <?php echo isset($_GET['available']) ? 'checked' : ''; ?>
                                           onchange="this.form.submit()">
                                    <label class="form-check-label" for="availableOnly">
                                        Disponibles seulement
                                    </label>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Sort -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Trier par</label>
                            <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="relevance" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'relevance') ? 'selected' : ''; ?>>Pertinence</option>
                                <option value="recent" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'recent') ? 'selected' : ''; ?>>Plus récents</option>
                                <option value="title" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'title') ? 'selected' : ''; ?>>Titre (A-Z)</option>
                                <option value="author" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'author') ? 'selected' : ''; ?>>Auteur (A-Z)</option>
                                <option value="rating" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'rating') ? 'selected' : ''; ?>>Mieux notés</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-sm">Appliquer les filtres</button>
                        <a href="<?php echo url('/search?q=' . urlencode($query)); ?>" class="btn btn-outline-secondary w-100 btn-sm mt-2">
                            Réinitialiser
                        </a>
                    </form>
                </div>
            </div>
            
            <!-- Popular Searches -->
            <?php if (!empty($popularSearches)): ?>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-star"></i> Recherches populaires</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($popularSearches as $popular): ?>
                            <a href="<?php echo url('/search?q=' . urlencode($popular['search_term'])); ?>" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span><?php echo escape($popular['search_term']); ?></span>
                                <span class="badge bg-primary rounded-pill"><?php echo $popular['search_count']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Results -->
        <div class="col-md-9">
            <!-- Results Summary -->
            <?php if (!empty($query)): ?>
                <div class="alert alert-info">
                    <strong><?php echo $results['total_books'] + $results['total_exercises']; ?></strong> résultat(s) trouvé(s)
                    <?php if ($results['total_books'] > 0): ?>
                        - <strong><?php echo $results['total_books']; ?></strong> livre(s)
                    <?php endif; ?>
                    <?php if ($results['total_exercises'] > 0): ?>
                        - <strong><?php echo $results['total_exercises']; ?></strong> exercice(s)
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Books Results -->
            <?php if (($type === 'all' || $type === 'books') && !empty($results['books'])): ?>
                <section class="mb-5">
                    <h3 class="mb-4"><i class="bi bi-book"></i> Livres</h3>
                    <div class="row">
                        <?php foreach ($results['books'] as $book): ?>
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <?php if (!empty($book['cover_image'])): ?>
                                        <img src="<?php echo asset('images/uploads/' . $book['cover_image']); ?>" 
                                             class="card-img-top" alt="<?php echo escape($book['title']); ?>" 
                                             style="height: 250px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                             style="height: 250px;">
                                            <i class="bi bi-book" style="font-size: 4rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title"><?php echo truncate($book['title'], 50); ?></h6>
                                        <p class="card-text text-muted small"><?php echo escape($book['author']); ?></p><?php if ($book['avg_rating'] > 0): ?>
                                        <div class="text-warning mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?php echo $i <= round($book['avg_rating']) ? '-fill' : ''; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mt-auto">
                                        <?php if ($book['available_copies'] > 0): ?>
                                            <span class="badge bg-success mb-2">Disponible</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger mb-2">Non disponible</span>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo url('/lecteur/books/details?id=' . $book['id']); ?>" 
                                           class="btn btn-primary btn-sm w-100">
                                            Voir détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if (!empty($results['pagination_books']) && $results['pagination_books']['total_pages'] > 1): ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <!-- Pagination pour les livres -->
                            <?php if ($results['pagination_books']['has_prev']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $results['pagination_books']['current_page'] - 1])); ?>">Précédent</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $results['pagination_books']['total_pages']; $i++): ?>
                                <li class="page-item <?php echo $i === $results['pagination_books']['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($results['pagination_books']['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $results['pagination_books']['current_page'] + 1])); ?>">Suivant</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </section>
        <?php endif; ?>
        
        <!-- Exercises Results -->
        <?php if (($type === 'all' || $type === 'exercises') && !empty($results['exercises'])): ?>
            <section>
                <h3 class="mb-4"><i class="bi bi-file-earmark-text"></i> Exercices</h3>
                <div class="row">
                    <?php foreach ($results['exercises'] as $exercise): ?>
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header" style="background-color: <?php echo $exercise['subject_color']; ?>15;">
                                    <span class="badge" style="background-color: <?php echo $exercise['subject_color']; ?>">
                                        <i class="<?php echo $exercise['subject_icon']; ?>"></i>
                                        <?php echo escape($exercise['subject_name']); ?>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo truncate($exercise['title'], 60); ?></h6>
                                    <p class="text-muted small mb-2">
                                        <i class="bi bi-mortarboard"></i> <?php echo escape($exercise['level_name']); ?>
                                    </p>
                                    <span class="badge bg-secondary"><?php echo ucfirst($exercise['difficulty']); ?></span>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="<?php echo url('/school/exercise?id=' . $exercise['id']); ?>" 
                                       class="btn btn-success btn-sm w-100">
                                        Accéder
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- No Results -->
        <?php if (!empty($query) && empty($results['books']) && empty($results['exercises'])): ?>
            <div class="text-center py-5">
                <i class="bi bi-search" style="font-size: 5rem; color: #ddd;"></i>
                <h3 class="mt-4">Aucun résultat trouvé</h3>
                <p class="text-muted">Essayez avec d'autres mots-clés ou ajustez vos filtres</p>
                <a href="<?php echo url('/search'); ?>" class="btn btn-primary">Nouvelle recherche</a>
            </div>
        <?php endif; ?>
    </div>
</div></div>
<script>
// Auto-suggestion
const searchInput = document.getElementById('searchInput');
const suggestionsDiv = document.getElementById('suggestions');

let debounceTimer;
searchInput.addEventListener('input', function() {
    clearTimeout(debounceTimer);
    const query = this.value.trim();
    
    if (query.length < 2) {
        suggestionsDiv.style.display = 'none';
        return;
    }
    
    debounceTimer = setTimeout(() => {
        fetch(`<?php echo url('/search/suggestions'); ?>?q=` + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    suggestionsDiv.innerHTML = data.map(item => 
                        `<a href="<?php echo url('/search'); ?>?q=${encodeURIComponent(item.search_term)}" class="list-group-item list-group-item-action">
                            <i class="bi bi-search"></i> ${item.search_term}
                            <span class="badge bg-secondary float-end">${item.search_count}</span>
                        </a>`
                    ).join('');
                    suggestionsDiv.style.display = 'block';
                } else {
                    suggestionsDiv.style.display = 'none';
                }
            });
    }, 300);
});

// Hide suggestions on click outside
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
        suggestionsDiv.style.display = 'none';
    }
});
</script>
<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
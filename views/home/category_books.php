<?php
$pageTitle = $category['name'];
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Accueil</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('/categories'); ?>">Catégories</a></li>
            <?php foreach ($breadcrumb as $index => $crumb): ?>
                <?php if ($index === count($breadcrumb) - 1): ?>
                    <li class="breadcrumb-item active"><?php echo escape($crumb['name']); ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?php echo url('/categories/' . $crumb['slug']); ?>">
                            <?php echo escape($crumb['name']); ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
    
    <!-- Category Header -->
    <div class="card mb-4" style="border-left: 5px solid <?php echo $category['color']; ?>">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-3">
                        <i class="<?php echo $category['icon']; ?>" style="color: <?php echo $category['color']; ?>"></i>
                        <?php echo escape($category['name']); ?>
                    </h1>
                    <p class="lead mb-0"><?php echo escape($category['description']); ?></p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-light rounded p-3">
                        <h2 class="mb-0"><?php //echo $category['book_count']; ?></h2>
                        <p class="text-muted mb-0">Livres disponibles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Subcategories -->
    <?php if (!empty($subcategories)): ?>
        <div class="mb-4">
            <h5 class="mb-3">Sous-catégories</h5>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($subcategories as $sub): ?>
                    <a href="<?php echo url('/categories/' . $sub['slug']); ?>" 
                       class="btn btn-outline-primary">
                        <?php echo escape($sub['name']); ?>
                        <span class="badge bg-primary"><?php echo $sub['book_count'] ?? 0; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Filters and Sort -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Rechercher dans cette catégorie</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Titre, auteur..." 
                           value="<?php echo escape($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trier par</label>
                    <select name="sort" class="form-select">
                        <option value="recent" <?php echo ($_GET['sort'] ?? '') === 'recent' ? 'selected' : ''; ?>>Plus récents</option>
                        <option value="title" <?php echo ($_GET['sort'] ?? '') === 'title' ? 'selected' : ''; ?>>Titre (A-Z)</option>
                        <option value="author" <?php echo ($_GET['sort'] ?? '') === 'author' ? 'selected' : ''; ?>>Auteur (A-Z)</option>
                        <option value="rating" <?php echo ($_GET['sort'] ?? '') === 'rating' ? 'selected' : ''; ?>>Mieux notés</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Books Grid -->
    <div class="row">
        <div class="col-md-9">
            <?php if (empty($books)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucun livre trouvé dans cette catégorie.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($books as $book): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <?php if (!empty($book['cover_image'])): ?>
                                    <img src="<?php echo asset('images/uploads/' . $book['cover_image']); ?>" 
                                         class="card-img-top" alt="<?php echo escape($book['title']); ?>" 
                                         style="height: 300px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                         style="height: 300px;">
                                        <i class="bi bi-book" style="font-size: 4rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo escape($book['title']); ?></h5>
                                    <p class="card-text text-muted"><?php echo escape($book['author']); ?></p>
                                    
                                    <?php if ($book['avg_rating'] > 0): ?>
                                        <div class="text-warning mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?php echo $i <= round($book['avg_rating']) ? '-fill' : ''; ?>"></i>
                                            <?php endfor; ?>
                                            <small class="text-muted">(<?php echo number_format($book['avg_rating'], 1); ?>)</small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <p class="card-text small">
                                        <?php echo truncate($book['description'] ?? '', 100); ?>
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <?php if ($book['available_copies'] > 0): ?>
                                            <span class="badge bg-success mb-2">
                                                <i class="bi bi-check-circle"></i> Disponible
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger mb-2">
                                                <i class="bi bi-x-circle"></i> Non disponible
                                            </span>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo url('/lecteur/books/details?id=' . $book['id']); ?>" 
                                           class="btn btn-primary w-100 mt-2">
                                            <i class="bi bi-eye"></i> Voir détails
                                        </a>
                                    </div>
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
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>">Précédent</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>">Suivant</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-3">
            <h5 class="mb-3">Filtres</h5>
            
            <!-- Availability Filter -->
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Disponibilité</h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="available" name="available">
                        <label class="form-check-label" for="available">
                            Disponibles seulement
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Ads Sidebar -->
            <?php 
            require_once ROOT_PATH . '/views/components/ads.php';
            displayAds('banner_side', 'categories', 2); 
            ?>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
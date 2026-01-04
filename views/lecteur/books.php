<?php
$pageTitle = 'Catalogue de Livres';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="list-group">
                <a href="<?php echo url('/lecteur/dashboard'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2"></i> Mon Espace
                </a>
                <a href="<?php echo url('/lecteur/books'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-book"></i> Catalogue
                </a>
                <a href="<?php echo url('/lecteur/borrowings'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-arrow-repeat"></i> Mes Emprunts
                </a>
                <a href="<?php echo url('/lecteur/profile'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-person"></i> Mon Profil
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="mb-4"><i class="bi bi-book"></i> Catalogue de Livres</h1>
            
            <?php display_flash(); ?>
            
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo url('/lecteur/books'); ?>" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Catégorie</label>
                            <select name="category" class="form-select">
                                <option value="">Toutes les catégories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo escape($cat); ?>" 
                                            <?php echo ($category ?? '') === $cat ? 'selected' : ''; ?>>
                                        <?php echo escape($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rechercher</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Titre, auteur..." 
                                   value="<?php echo escape($search ?? ''); ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Books Grid -->
            <?php if (empty($books)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Aucun livre trouvé avec ces critères
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($books as $book): ?>
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                <?php if (!empty($book['cover_image'])): ?>
                                    <img src="<?php echo asset('images/uploads/' . $book['cover_image']); ?>" 
                                         class="card-img-top" alt="<?php echo escape($book['title']); ?>" 
                                         style="height: 300px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                         style="height: 300px;">
                                        <i class="bi bi-book" style="font-size: 5rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo escape($book['title']); ?></h5>
                                    <p class="card-text text-muted"><?php echo escape($book['author']); ?></p>
                                    
                                    <?php if ($book['category']): ?>
                                        <p class="mb-2">
                                            <span class="badge bg-info"><?php echo escape($book['category']); ?></span>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <p class="card-text">
                                        <?php echo truncate($book['description'] ?? '', 100); ?>
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small>
                                                <?php if ($book['available_copies'] > 0): ?>
                                                    <span class="text-success">
                                                        <i class="bi bi-check-circle"></i> Disponible (<?php echo $book['available_copies']; ?>)
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-danger">
                                                        <i class="bi bi-x-circle"></i> Non disponible
                                                    </span>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <a href="<?php echo url('/lecteur/books/details?id=' . $book['id']); ?>" 
                                           class="btn btn-primary w-100">
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
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['has_prev']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                        Précédent
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
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

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
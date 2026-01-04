<?php
$pageTitle = 'Gestion des Livres';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container-fluid my-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2">
            <div class="list-group">
                <a href="<?php echo url('/bibliothecaire/dashboard'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2"></i> Tableau de bord
                </a>
                <a href="<?php echo url('/bibliothecaire/books'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-book"></i> Livres
                </a>
                <a href="<?php echo url('/bibliothecaire/borrowings'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-arrow-repeat"></i> Emprunts
                </a>
                <a href="<?php echo url('/bibliothecaire/moderate'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-shield-check"></i> Modération
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-book"></i> Gestion des Livres</h1>
                <a href="<?php echo url('/bibliothecaire/books/add'); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Ajouter un livre
                </a>
            </div>
            
            <?php display_flash(); ?>
            
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo url('/bibliothecaire/books'); ?>" class="row g-3">
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
                                   placeholder="Titre, auteur, ISBN..." 
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
            
            <!-- Books Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Liste des Livres (<?php echo $total; ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($books)): ?>
                        <p class="text-muted">Aucun livre trouvé</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Couverture</th>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>ISBN</th>
                                        <th>Catégorie</th>
                                        <th>Exemplaires</th>
                                        <th>Disponibles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($books as $book): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($book['cover_image'])): ?>
                                                    <img src="<?php echo asset('images/uploads/' . $book['cover_image']); ?>" 
                                                         alt="<?php echo escape($book['title']); ?>" 
                                                         style="width: 50px; height: 70px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 70px;">
                                                        <i class="bi bi-book"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo escape($book['title']); ?></strong><br>
                                                <small class="text-muted">
                                                    <?php echo escape($book['publisher'] ?? ''); ?> 
                                                    <?php echo $book['publication_year'] ? '(' . $book['publication_year'] . ')' : ''; ?>
                                                </small>
                                            </td>
                                            <td><?php echo escape($book['author']); ?></td>
                                            <td><?php echo escape($book['isbn'] ?? '-'); ?></td>
                                            <td>
                                                <?php if ($book['category']): ?>
                                                    <span class="badge bg-info">
                                                        <?php echo escape($book['category']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $book['total_copies']; ?></td>
                                            <td>
                                                <?php
                                                $availabilityClass = $book['available_copies'] > 0 ? 'success' : 'danger';
                                                ?>
                                                <span class="badge bg-<?php echo $availabilityClass; ?>">
                                                    <?php echo $book['available_copies']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo url('/bibliothecaire/books/edit?id=' . $book['id']); ?>" 
                                                       class="btn btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="<?php echo url('/bibliothecaire/books/delete'); ?>" 
                                                          class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('Supprimer ce livre ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
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
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
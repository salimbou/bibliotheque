<?php
$pageTitle = 'Catégories';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-5">
    <h1 class="mb-4"><i class="bi bi-grid"></i> Toutes les Catégories</h1>
    
    <?php display_flash(); ?>
    
    <!-- Featured Categories -->
    <?php if (!empty($featuredCategories)): ?>
        <section class="mb-5">
            <h3 class="mb-4">Catégories Populaires</h3>
            <div class="row">
                <?php foreach ($featuredCategories as $category): ?>
                    <div class="col-md-3 mb-4">
                        <a href="<?php echo url('/categories/' . $category['slug']); ?>" class="text-decoration-none">
                            <div class="card h-100 shadow-sm category-card" style="border-left: 4px solid <?php echo $category['color']; ?>">
                                <div class="card-body text-center">
                                    <i class="<?php echo $category['icon']; ?>" style="font-size: 3rem; color: <?php echo $category['color']; ?>"></i>
                                    <h5 class="card-title mt-3"><?php echo escape($category['name']); ?></h5>
                                    <p class="card-text text-muted small">
                                        <?php echo truncate($category['description'], 80); ?>
                                    </p>
                                    <span class="badge bg-primary"><?php echo $category['book_count'] ?? 0; ?> livres</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- All Categories Tree -->
    <section>
        <h3 class="mb-4">Toutes les Catégories</h3>
        
        <?php foreach ($categoryTree as $mainCategory): ?>
            <div class="card mb-4">
                <div class="card-header" style="background-color: <?php echo $mainCategory['color']; ?>15;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="<?php echo $mainCategory['icon']; ?>" style="color: <?php echo $mainCategory['color']; ?>"></i>
                            <?php echo escape($mainCategory['name']); ?>
                        </h4>
                        <a href="<?php echo url('/categories/' . $mainCategory['slug']); ?>" class="btn btn-sm btn-outline-primary">
                            Voir tous les livres <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <?php if (!empty($mainCategory['children'])): ?>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($mainCategory['children'] as $subCategory): ?>
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <a href="<?php echo url('/categories/' . $subCategory['slug']); ?>" 
                                       class="d-block p-3 bg-light rounded text-decoration-none hover-shadow">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-dark"><?php echo escape($subCategory['name']); ?></span>
                                            <span class="badge bg-secondary"><?php echo $subCategory['book_count'] ?? 0; ?></span>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>
</div>

<style>
.category-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2) !important;
}

.hover-shadow {
    transition: all 0.3s;
}

.hover-shadow:hover {
    background-color: #e9ecef !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
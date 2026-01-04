<?php
$pageTitle = 'Modifier le Livre';
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';

$errors = Session::get('book_errors', []);
Session::remove('book_errors');
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
                <h1><i class="bi bi-pencil"></i> Modifier le Livre</h1>
                <a href="<?php echo url('/bibliothecaire/books'); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
            
            <?php display_flash(); ?>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo url('/bibliothecaire/books/update'); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre *</label>
                                    <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                                           id="title" name="title" value="<?php echo escape($book['title']); ?>" required>
                                    <?php if (isset($errors['title'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['title']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="author" class="form-label">Auteur *</label>
                                    <input type="text" class="form-control <?php echo isset($errors['author']) ? 'is-invalid' : ''; ?>" 
                                           id="author" name="author" value="<?php echo escape($book['author']); ?>" required>
                                    <?php if (isset($errors['author'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['author']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="isbn" class="form-label">ISBN</label>
                                        <input type="text" class="form-control <?php echo isset($errors['isbn']) ? 'is-invalid' : ''; ?>" 
                                               id="isbn" name="isbn" value="<?php echo escape($book['isbn'] ?? ''); ?>">
                                        <?php if (isset($errors['isbn'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['isbn']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Catégorie</label>
                                        <input type="text" class="form-control" 
                                               id="category" name="category" value="<?php echo escape($book['category'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="publisher" class="form-label">Éditeur</label>
                                        <input type="text" class="form-control" 
                                               id="publisher" name="publisher" value="<?php echo escape($book['publisher'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="publication_year" class="form-label">Année de publication</label>
                                        <input type="number" class="form-control" 
                                               id="publication_year" name="publication_year" 
                                               value="<?php echo escape($book['publication_year'] ?? ''); ?>" 
                                               min="1000" max="<?php echo date('Y'); ?>">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"><?php echo escape($book['description'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="total_copies" class="form-label">Nombre d'exemplaires *</label>
                                    <input type="number" class="form-control <?php echo isset($errors['total_copies']) ? 'is-invalid' : ''; ?>" 
                                           id="total_copies" name="total_copies" value="<?php echo $book['total_copies']; ?>" 
                                           min="1" required>
                                    <?php if (isset($errors['total_copies'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['total_copies']; ?></div>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        Actuellement: <?php echo $book['available_copies']; ?> disponibles / 
                                        <?php echo $book['total_copies'] - $book['available_copies']; ?> empruntés
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cover_image" class="form-label">Image de couverture</label>
                                    <?php if (!empty($book['cover_image'])): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo asset('images/uploads/' . $book['cover_image']); ?>" 
                                                 class="img-fluid rounded" alt="Couverture actuelle">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control <?php echo isset($errors['cover_image']) ? 'is-invalid' : ''; ?>" 
                                           id="cover_image" name="cover_image" accept="image/*">
                                    <?php if (isset($errors['cover_image'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['cover_image']; ?></div>
                                    <?php endif; ?>
                                    <small class="text-muted">Laissez vide pour garder l'image actuelle</small>
                                </div>
                                
                                <div id="image-preview" class="mt-3"></div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo url('/bibliothecaire/books'); ?>" class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<p class="text-muted">Nouvelle image:</p><img src="' + e.target.result + '" class="img-fluid rounded" alt="Preview">';
        }
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
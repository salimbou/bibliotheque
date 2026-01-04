<?php
$pageTitle = $book['title'];
require_once ROOT_PATH . '/views/layouts/header.php';
require_once ROOT_PATH . '/views/layouts/navbar.php';
?>

<div class="container my-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo url('/lecteur/dashboard'); ?>">Mon Espace</a></li>
            <li class="breadcrumb-item"><a href="<?php echo url('/lecteur/books'); ?>">Catalogue</a></li>
            <li class="breadcrumb-item active"><?php echo escape($book['title']); ?></li>
        </ol>
    </nav>
    
    <?php display_flash(); ?>
    
    <!-- Book Details -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?php echo asset('images/uploads/' . $book['cover_image']); ?>" 
                             class="img-fluid rounded shadow" alt="<?php echo escape($book['title']); ?>">
                    <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" 
                             style="height: 400px;">
                            <i class="bi bi-book" style="font-size: 6rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-9">
                    <h1 class="mb-3"><?php echo escape($book['title']); ?></h1>
                    <h5 class="text-muted mb-3">par <?php echo escape($book['author']); ?></h5>
                    
                    <div class="mb-3">
                        <?php if ($book['avg_rating'] > 0): ?>
                            <div class="text-warning d-inline-block me-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star<?php echo $i <= round($book['avg_rating']) ? '-fill' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-muted">
                                <?php echo number_format($book['avg_rating'], 1); ?> 
                                (<?php echo $book['total_reviews']; ?> avis)
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Aucun avis pour le moment</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <?php if ($book['category']): ?>
                            <span class="badge bg-info me-2">
                                <i class="bi bi-tag"></i> <?php echo escape($book['category']); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($book['available_copies'] > 0): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Disponible (<?php echo $book['available_copies']; ?> exemplaires)
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle"></i> Non disponible
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <table class="table table-sm">
                        <tbody>
                            <?php if ($book['isbn']): ?>
                                <tr>
                                    <th width="150">ISBN:</th>
                                    <td><?php echo escape($book['isbn']); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($book['publisher']): ?>
                                <tr>
                                    <th>Éditeur:</th>
                                    <td><?php echo escape($book['publisher']); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($book['publication_year']): ?>
                                <tr>
                                    <th>Année:</th>
                                    <td><?php echo $book['publication_year']; ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <?php if ($book['description']): ?>
                        <h6 class="mt-3">Description</h6>
                        <p><?php echo nl2br(escape($book['description'])); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($hasActiveBorrowing): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Vous avez déjà emprunté ce livre
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Review Section -->
    <?php if (!$hasReviewed): ?>
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-star"></i> Donner votre avis</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo url('/lecteur/reviews/add'); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Note *</label>
                        <div class="rating-input">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                                <label for="star<?php echo $i; ?>" class="star">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="review_text" class="form-label">Votre avis *</label>
                        <textarea class="form-control" id="review_text" name="review_text" 
                                  rows="4" required placeholder="Partagez votre expérience avec ce livre..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-send"></i> Publier mon avis
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Reviews Section -->
    <?php if (!empty($reviews)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-star"></i> Avis des lecteurs (<?php echo count($reviews); ?>)</h5>
            </div>
            <div class="card-body">
                <?php foreach ($reviews as $review): ?>
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <?php echo escape($review['first_name'] . ' ' . $review['last_name']); ?>
                                </h6>
                                <div class="text-warning mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="mb-2"><?php echo nl2br(escape($review['review_text'])); ?></p>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> <?php echo format_datetime($review['created_at']); ?>
                                </small>
                            </div>
                            <div class="ms-3 text-end">
                                <div class="btn-group btn-group-sm">
                                    <span class="btn btn-outline-success">
                                        <i class="bi bi-hand-thumbs-up"></i> <?php echo $review['likes']; ?>
                                    </span>
                                    <span class="btn btn-outline-danger">
                                        <i class="bi bi-hand-thumbs-down"></i> <?php echo $review['dislikes']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Comments Section -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Commentaires (<?php echo count($comments); ?>)</h5>
        </div>
        <div class="card-body">
            <!-- Add Comment Form -->
            <form method="POST" action="<?php echo url('/lecteur/comments/add'); ?>" class="mb-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" value="book">
                <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                
                <div class="mb-3">
                    <textarea class="form-control" name="comment_text" rows="3" 
                              placeholder="Ajouter un commentaire..." required></textarea>
                </div>
                <button type="submit" class="btn btn-info">
                    <i class="bi bi-send"></i> Publier
                </button>
            </form>
            
            <!-- Comments List -->
            <?php if (empty($comments)): ?>
                <p class="text-muted">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="mb-3 pb-3 border-bottom">
                        <h6 class="mb-1">
                            <?php echo escape($comment['first_name'] . ' ' . $comment['last_name']); ?>
                        </h6>
                        <p class="mb-2"><?php echo nl2br(escape($comment['comment_text'])); ?></p>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> <?php echo format_datetime($comment['created_at']); ?>
                        </small>
                        <div class="mt-2">
                            <div class="btn-group btn-group-sm">
                                <span class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-hand-thumbs-up"></i> <?php echo $comment['likes']; ?>
                                </span>
                                <span class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-hand-thumbs-down"></i> <?php echo $comment['dislikes']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    font-size: 2rem;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label.star {
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.rating-input input[type="radio"]:checked ~ label.star,
.rating-input label.star:hover,
.rating-input label.star:hover ~ label.star {
    color: #ffc107;
}
</style>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
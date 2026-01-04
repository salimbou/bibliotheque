<?php
$pageTitle = 'Modération';
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
                <a href="<?php echo url('/bibliothecaire/books'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-book"></i> Livres
                </a>
                <a href="<?php echo url('/bibliothecaire/borrowings'); ?>" class="list-group-item list-group-item-action">
                    <i class="bi bi-arrow-repeat"></i> Emprunts
                </a>
                <a href="<?php echo url('/bibliothecaire/moderate'); ?>" class="list-group-item list-group-item-action active">
                    <i class="bi bi-shield-check"></i> Modération
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="mb-4"><i class="bi bi-shield-check"></i> Modération des Contenus</h1>
            
            <?php display_flash(); ?>
            
            <!-- Pending Reviews -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Avis en Attente (<?php echo count($pendingReviews); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($pendingReviews)): ?>
                        <p class="text-muted">Aucun avis en attente de modération</p>
                    <?php else: ?>
                        <?php foreach ($pendingReviews as $review): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <strong><?php echo escape($review['first_name'] . ' ' . $review['last_name']); ?></strong>
                                                sur <em><?php echo escape($review['book_title']); ?></em>
                                            </h6>
                                            <div class="text-warning mb-2">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <p class="mb-2"><?php echo nl2br(escape($review['review_text'])); ?></p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i> Posté le <?php echo format_datetime($review['created_at']); ?>
                                            </small>
                                        </div>
                                        <div class="ms-3">
                                            <form method="POST" action="<?php echo url('/bibliothecaire/moderate/review'); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm mb-1">
                                                    <i class="bi bi-check-circle"></i> Approuver
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo url('/bibliothecaire/moderate/review'); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i> Rejeter
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Pending Comments -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Commentaires en Attente (<?php echo count($pendingComments); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($pendingComments)): ?>
                        <p class="text-muted">Aucun commentaire en attente de modération</p>
                    <?php else: ?>
                        <?php foreach ($pendingComments as $comment): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <strong><?php echo escape($comment['first_name'] . ' ' . $comment['last_name']); ?></strong>
                                                sur 
                                                <span class="badge bg-secondary"><?php echo escape($comment['commentable_type']); ?></span>
                                                <em><?php echo escape($comment['entity_title']); ?></em>
                                            </h6>
                                            <p class="mb-2"><?php echo nl2br(escape($comment['comment_text'])); ?></p>
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i> Posté le <?php echo format_datetime($comment['created_at']); ?>
                                            </small>
                                        </div>
                                        <div class="ms-3">
                                            <form method="POST" action="<?php echo url('/bibliothecaire/moderate/comment'); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm mb-1">
                                                    <i class="bi bi-check-circle"></i> Approuver
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo url('/bibliothecaire/moderate/comment'); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-x-circle"></i> Rejeter
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/layouts/footer.php'; ?>
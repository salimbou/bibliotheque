<footer class="bg-dark text-white mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5><i class="bi bi-book"></i> <?php echo APP_NAME; ?></h5>
                <p>Votre bibliothèque en ligne pour découvrir, emprunter et partager des livres.</p>
            </div>
            <div class="col-md-4">
                <h5>Liens rapides</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo url('/'); ?>" class="text-white text-decoration-none">Accueil</a></li>
                    <li><a href="<?php echo url('/about'); ?>" class="text-white text-decoration-none">À propos</a></li>
                    <li><a href="<?php echo url('/contact'); ?>" class="text-white text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Contact</h5>
                <p>
                    <i class="bi bi-geo-alt"></i> 123 Rue de la Bibliothèque, 75001 Paris<br>
                    <i class="bi bi-telephone"></i> +33 1 23 45 67 89<br>
                    <i class="bi bi-envelope"></i> contact@bibliotheque.com
                </p>
            </div>
        </div>
        <hr class="bg-white">
        <div class="text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="<?php //echo asset('js/main.js'); ?>"></script>
</body>
</html>
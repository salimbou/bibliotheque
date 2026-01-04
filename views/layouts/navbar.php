<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo url('/'); ?>">
            <i class="bi bi-book"></i> <?php echo APP_NAME; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Search Form (visible on larger screens) -->
            <form action="<?php echo url('/search'); ?>" method="GET" class="d-none d-lg-flex mx-auto" style="width: 400px;">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher..." value="<?php echo escape($_GET['q'] ?? ''); ?>">
                    <button class="btn btn-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/'); ?>">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/categories'); ?>">Catégories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/school'); ?>">Scolaire</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/about'); ?>">À propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo url('/contact'); ?>">Contact</a>
                </li>
                
                <?php if (is_authenticated()): ?>
                    <?php $user = current_user(); ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo escape($user['name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php echo url('/' . $user['role'] . '/dashboard'); ?>">
                                    <i class="bi bi-speedometer2"></i> Tableau de bord
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo url('/' . $user['role'] . '/profile'); ?>">
                                    <i class="bi bi-person-circle"></i> Mon Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?php echo url('/logout'); ?>">
                                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('/login'); ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('/register'); ?>">
                            <i class="bi bi-person-plus"></i> Inscription
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
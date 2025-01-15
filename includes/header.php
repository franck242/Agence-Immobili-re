<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Déterminer le chemin de base
$basePath = '';
$currentPath = $_SERVER['SCRIPT_NAME'];
if (strpos($currentPath, '/admin/') !== false) {
    $basePath = '../';
} else {
    $basePath = '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agence Immobilière</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/detail-appartement.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/carousel.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/footer.css">
    
    <style>
        .navbar {
            background-color: #2c3e50;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
        .dropdown-menu {
            background-color: #2c3e50;
        }
        .dropdown-item {
            color: white;
        }
        .dropdown-item:hover {
            background-color: #34495e;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo isset($_SESSION['admin']) ? $basePath.'../index.php' : $basePath.'index.php'; ?>">
                Agence Immobilière
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#appartements">Appartements</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if(isset($_SESSION['connecte'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <?php 
                                if(isset($_SESSION['admin'])) {
                                    echo htmlspecialchars($_SESSION['admin_firstname']);
                                } else {
                                    echo htmlspecialchars($_SESSION['user_firstname']);
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if(isset($_SESSION['admin'])): ?>
                                    <li><a class="dropdown-item" href="<?php echo $basePath.'admin/dashboard.php'; ?>">Dashboard</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?php echo $basePath.'user/dashboard.php'; ?>">Mon Compte</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo $basePath.'deconnexion.php'; ?>">Déconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $basePath.'connexion.php'; ?>">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $basePath.'inscription.php'; ?>">Inscription</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main>
    </main>
    <!-- Scripts -->
    
    <!-- Swiper and AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true
            });
            
            // Initialize Swiper
            new Swiper('.main-swiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                }
            });
        });
    </script>
    <!-- Script pour le défilement fluide -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélectionner tous les liens qui pointent vers des ancres
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    // Récupérer la cible
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        // Calculer l'offset pour tenir compte du menu fixe
                        const navHeight = document.querySelector('.navbar').offsetHeight;
                        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navHeight;

                        // Défilement fluide
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
    <!-- Custom JS -->
    <script src="<?php echo $basePath; ?>assets/js/main.js"></script>
    <script src="<?php echo $basePath; ?>assets/js/burger.js"></script>
    
    <!-- Bootstrap Bundle avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

<?php
session_start();
require_once 'admin/connexion-bdd.php';

$id = isset($_GET['apt']) ? intval($_GET['apt']) : 0;

$requete = $connexion->prepare("SELECT * FROM appartements WHERE Id_appartements = :id");
$requete->execute(['id' => $id]);
$appartement = $requete->fetch(PDO::FETCH_ASSOC);

if (!$appartement) {
    header('Location: index.php');
    exit();
}

require_once 'includes/header.php';
?>
<link rel="stylesheet" href="assets/css/detail-appartements.css">

<style>
    .apartment-gallery {
        width: 100%;
        height: 550px;
    }
    .swiper-slide {
        width: 100%;
        height: 100%;
    }
    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .swiper.apartment-gallery {
        width: 100% !important;
        height: 550px !important;
    }
    .swiper.apartment-gallery .swiper-wrapper {
        width: 100% !important;
        height: 100% !important;
    }
    .swiper.apartment-gallery .swiper-slide {
        width: 100% !important;
        height: 100% !important;
    }
    .swiper.apartment-gallery .swiper-slide img {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
    }
</style>

<div class="apartment-detail-page">
    <!-- Galerie photos -->
    <div class="swiper apartment-gallery">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="img/<?php echo htmlspecialchars($appartement['photos']); ?>" alt="<?php echo htmlspecialchars($appartement['categorie']); ?>">
            </div>
            <?php if (!empty($appartement['photos2'])) : ?>
            <div class="swiper-slide">
                <img src="img/<?php echo htmlspecialchars($appartement['photos2']); ?>" alt="<?php echo htmlspecialchars($appartement['categorie']); ?>">
            </div>
            <?php endif; ?>
            <?php if (!empty($appartement['photos3'])) : ?>
            <div class="swiper-slide">
                <img src="img/<?php echo htmlspecialchars($appartement['photos3']); ?>" alt="<?php echo htmlspecialchars($appartement['categorie']); ?>">
            </div>
            <?php endif; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <div class="container">
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="apartment-info" data-aos="fade-up" data-aos-duration="1000">
            <h1><?php echo htmlspecialchars($appartement['categorie']); ?></h1>
            <div class="price"><?php echo number_format($appartement['prix'], 0, ',', ' '); ?> €/mois</div>
            <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo htmlspecialchars($appartement['adresse'] . ', ' . $appartement['ville'] . ', France'); ?></span>
            </div>
            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-ruler-combined"></i>
                    <span><?php echo htmlspecialchars($appartement['superficie']); ?> m² Superficie</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-bed"></i>
                    <span><?php echo htmlspecialchars($appartement['lits']); ?> Chambres</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-bath"></i>
                    <span><?php echo htmlspecialchars($appartement['salle_de_bains']); ?> Salles de bain</span>
                </div>
            </div>
            <div class="description">
                <h2>Description</h2>
                <p><?php echo nl2br(htmlspecialchars($appartement['description'])); ?></p>
            </div>
        </div>

        <div class="contact-form" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
            <h3>Intéressé par ce bien ?</h3>
            <form method="POST" action="contact.php">
                <input type="hidden" name="apartment_id" value="<?php echo $id; ?>">
                <input type="text" class="form-control mb-3" name="name" placeholder="Votre nom" required>
                <input type="email" class="form-control mb-3" name="email" placeholder="Votre email" required>
                <input type="tel" class="form-control mb-3" name="phone" placeholder="Votre téléphone" required>
                <textarea class="form-control mb-3" name="message" placeholder="Votre message (précisez si vous souhaitez une visite ou une réservation)" required></textarea>
                <button type="submit" class="btn btn-primary w-100">Contacter l'agence</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var swiper = new Swiper('.apartment-gallery', {
        slidesPerView: 1,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
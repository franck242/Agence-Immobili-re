<?php
session_start();

// Si l'utilisateur est connecté, le rediriger vers son dashboard
if (isset($_SESSION['connecte'])) {
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
        header('Location: admin/dashboard.php');
        exit();
    } else {
        header('Location: user/dashboard.php');
        exit();
    }
}

require_once 'includes/header.php';
?>

<!-- Carousel Section -->
<div class="swiper">
    <div class="swiper-wrapper">
        <?php
        try {
            require("admin/connexion-bdd.php");
            $requete = $connexion->prepare("SELECT * FROM appartements WHERE status = 0");
            $requete->execute();
            $appartements = $requete->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($appartements as $appartement) {
                $imagePath = "img/" . $appartement['photos'];
                echo '<div class="swiper-slide">';
                echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($appartement['categorie']) . '">';
                echo '<div class="slide-overlay">';
                echo '<div class="slide-content">';
                echo '<h2>' . htmlspecialchars($appartement['categorie']) . '</h2>';
                echo '<p class="price">' . htmlspecialchars($appartement['prix']) . ' €/mois</p>';
                echo '<div class="features">';
                echo '<span><i class="fas fa-ruler-combined"></i> ' . htmlspecialchars($appartement['superficie']) . '</span>';
                echo '<span><i class="fas fa-bed"></i> ' . htmlspecialchars($appartement['lits']) . ' chambres</span>';
                echo '<span><i class="fas fa-bath"></i> ' . htmlspecialchars($appartement['salle_de_bains']) . ' SDB</span>';
                echo '</div>';
                echo '<a href="detail-appartements.php?apt=' . $appartement['Id_appartements'] . '" class="btn btn-light mt-3">Voir détails</a>';
                echo '</div></div>';
                echo '</div>';
            }
        } catch (Exception $e) {
            echo "<!-- Erreur: " . $e->getMessage() . " -->";
        }
        ?>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<style>
    .swiper {
        width: 100%;
        height: 550px;
        margin-top: 76px;
    }
    .swiper-slide {
        position: relative;
        width: 100%;
        height: 100%;
    }
    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    .slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.4));
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 30px;
    }
    .slide-content {
        color: white;
        text-align: center;
        width: 100%;
        max-width: 800px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.9);
    }
    .slide-content h2 {
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
    }
    .slide-content .price {
        font-size: 1.8rem;
        color: #3a99ff;
        margin-bottom: 1rem;
    }
    .slide-content .features {
        display: flex;
        gap: 2rem;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .slide-content .features span {
        background: rgba(58, 153, 255, 0.3);
        padding: 10px 20px;
        border-radius: 25px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .slide-content .features i {
        margin-right: 8px;
        color: #3a99ff;
    }
    .swiper-button-next,
    .swiper-button-prev {
        color: white !important;
        background: #3a99ff !important;
        width: 50px !important;
        height: 50px !important;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-size: 20px !important;
        font-weight: bold;
    }
    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        background: rgba(255, 255, 255, 0.9) !important;
        color: #3a99ff !important;
    }
    .swiper-pagination-bullet {
        width: 12px !important;
        height: 12px !important;
        background: white !important;
        opacity: 0.5;
    }
    .swiper-pagination-bullet-active {
        opacity: 1;
        background: #3a99ff !important;
    }
    .slide-content .btn-light {
        background: #3a99ff;
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        border: none;
    }
    .slide-content .btn-light:hover {
        background: white;
        color: #3a99ff;
        transform: translateY(-2px);
    }
</style>

<!-- Initialize Swiper -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper('.swiper', {
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            speed: 1000,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        });
    });
</script>

<!-- Search Section -->
<section class="search-section" data-aos="fade-up" data-aos-duration="1000">
    <div class="container mt-5">
        <form id="searchForm" class="search-form mb-5 mt-5">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="prix_max" class="form-label">Prix maximum</label>
                    <select name="prix_max" id="prix_max" class="form-select">
                        <option value="">Tous les prix</option>
                        <option value="1000">Jusqu'à 1000€</option>
                        <option value="1500">Jusqu'à 1500€</option>
                        <option value="2000">Jusqu'à 2000€</option>
                        <option value="2500">Jusqu'à 2500€</option>
                        <option value="3000">Plus de 2500€</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="surface_min" class="form-label">Surface minimum</label>
                    <select name="surface_min" id="surface_min" class="form-select">
                        <option value="">Toutes surfaces</option>
                        <option value="35">Plus de 35m²</option>
                        <option value="50">Plus de 50m²</option>
                        <option value="75">Plus de 75m²</option>
                        <option value="100">Plus de 100m²</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">Tous types</option>
                        <?php
                        try {
                            $types = $connexion->query("SELECT DISTINCT categorie FROM appartements ORDER BY categorie")->fetchAll(PDO::FETCH_COLUMN);
                            foreach ($types as $type) {
                                echo '<option value="' . htmlspecialchars($type) . '">' . htmlspecialchars($type) . '</option>';
                            }
                        } catch (Exception $e) {
                            echo '<option value="">Erreur de chargement</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="quartier" class="form-label">Quartier</label>
                    <select name="quartier" id="quartier" class="form-select">
                        <option value="">Tous quartiers</option>
                        <?php
                        try {
                            $quartiers = $connexion->query("SELECT DISTINCT quartier FROM appartements ORDER BY quartier")->fetchAll(PDO::FETCH_COLUMN);
                            foreach ($quartiers as $quartier) {
                                echo '<option value="' . htmlspecialchars($quartier) . '">' . htmlspecialchars($quartier) . '</option>';
                            }
                        } catch (Exception $e) {
                            echo '<option value="">Erreur de chargement</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Apartments Section -->
<div class="container mt-5" id="appartements">
    <div class="row">
        <?php
        require("admin/connexion-bdd.php");
        $requete = $connexion->prepare("SELECT * FROM appartements WHERE status = 0");
        $requete->execute();
        $appartements = $requete->fetchAll(PDO::FETCH_ASSOC);
        $delay = 0;
        foreach ($appartements as $appartement): ?>
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                <div class="property-card">
                    <div class="property-image">
                        <img src="img/<?php echo htmlspecialchars($appartement['photos']); ?>" 
                             alt="<?php echo htmlspecialchars($appartement['categorie']); ?>">
                        <div class="property-price">
                            <?php echo htmlspecialchars($appartement['prix']); ?> €/mois
                        </div>
                    </div>
                    <div class="property-content">
                        <h3 class="property-title"><?php echo htmlspecialchars($appartement['categorie']); ?></h3>
                        <div class="property-details">
                            <span class="detail-item">
                                <i class="fas fa-ruler-combined"></i>
                                <?php echo htmlspecialchars($appartement['superficie']); ?> m²
                            </span>
                            <span class="detail-item">
                                <i class="fas fa-bed"></i>
                                <?php echo htmlspecialchars($appartement['lits']); ?> ch.
                            </span>
                            <span class="detail-item">
                                <i class="fas fa-bath"></i>
                                <?php echo htmlspecialchars($appartement['salle_de_bains']); ?> SDB
                            </span>
                        </div>
                        <p class="property-description"><?php echo htmlspecialchars($appartement['description']); ?></p>
                        <a href="detail-appartements.php?apt=<?php echo $appartement['Id_appartements']; ?>" 
                           class="btn btn-primary w-100">Voir détails</a>
                    </div>
                </div>
            </div>
        <?php $delay += 100; endforeach; ?>
    </div>
</div>

<style>
.property-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    margin-bottom: 30px;
    border: 1px solid #e0e0e0;
}

.property-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.property-image {
    position: relative;
    height: 250px;
    overflow: hidden;
    border-radius: 8px 8px 0 0;
    border-bottom: 1px solid #e0e0e0;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.property-price {
    position: absolute;
    bottom: 15px;
    left: 15px;
    background: rgba(0, 123, 255, 0.95);
    color: #fff;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.property-content {
    padding: 20px;
    background: #fff;
    border-radius: 0 0 8px 8px;
}

.property-title {
    font-size: 1.25rem;
    margin-bottom: 15px;
    color: #333;
    font-weight: 600;
}

.property-details {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
    padding: 10px;
    border-radius: 6px;
}

.detail-item {
    display: flex;
    align-items: center;
    color: #555;
    font-size: 0.9rem;
}

.detail-item i {
    margin-right: 5px;
    color: #007bff;
}

.property-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 20px;
    line-height: 1.6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const appartementsContainer = document.querySelector('#appartements .row');

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(searchForm);
        const searchParams = new URLSearchParams();
        
        for (const [key, value] of formData) {
            if (value) {
                searchParams.append(key, value);
            }
        }

        fetch('filter-appartements.php?' + searchParams.toString())
            .then(response => response.text())
            .then(html => {
                appartementsContainer.innerHTML = html;
                document.getElementById('appartements').scrollIntoView({ behavior: 'smooth' });
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la recherche.');
            });
    });
});
</script>

<!-- About Section -->
<section id="about" class="py-5" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="mb-4">À propos de notre agence</h2>
                <p class="lead">Notre agence immobilière s'engage à vous offrir un service personnalisé et professionnel pour tous vos besoins en matière de location.</p>
                <p>Avec des années d'expérience sur le marché immobilier, notre équipe dévouée est là pour vous accompagner dans votre recherche du logement idéal.</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-primary me-2"></i>Large sélection d'appartements</li>
                    <li><i class="fas fa-check text-primary me-2"></i>Service client disponible 7j/7</li>
                    <li><i class="fas fa-check text-primary me-2"></i>Accompagnement personnalisé</li>
                    <li><i class="fas fa-check text-primary me-2"></i>Transparence et professionnalisme</li>
                </ul>
            </div>
            <div class="col-md-6">
                <img src="img/pexels-vecislavas-popa-1571460.jpg" alt="Notre agence" class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5" data-aos="fade-up" data-aos-duration="1000">
    <div class="container">
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-6">
                <form id="contactForm" action="contact.php" method="POST" class="contact-form p-4 rounded shadow-lg">
                    <h3>Contactez-nous</h3>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="nom" name="name" placeholder="Votre nom" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                    </div>
                    <div class="mb-3">
                        <input type="tel" class="form-control" id="telephone" name="phone" placeholder="Votre téléphone">
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Votre message (précisez si vous souhaitez une visite ou une réservation)" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Contacter l'agence</button>
                </form>
            </div>
            <div class="col-lg-6">
                <div class="contact-info p-4">
                    <h3>Nos coordonnées</h3>
                    <p><i class="fas fa-map-marker-alt me-2"></i>123 Rue de la République, 69002 Lyon</p>
                    <p><i class="fas fa-phone me-2"></i>+33 4 78 XX XX XX</p>
                    <p><i class="fas fa-envelope me-2"></i>contact@agence-immo.fr</p>
                    <div class="map-container mt-4">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2783.0876732914246!2d4.834986776926766!3d45.76397661478225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47f4ea56197f0b0f%3A0x1ba109d96d6e4e14!2s123%20Rue%20de%20la%20R%C3%A9publique%2C%2069002%20Lyon!5e0!3m2!1sfr!2sfr!4v1705233456789!5m2!1sfr!2sfr" 
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.contact-form {
    background: #fff;
}

.contact-info {
    background: #f8f9fa;
    height: 100%;
    border-radius: 8px;
}

.contact-info h3 {
    color: #333;
    margin-bottom: 20px;
}

.contact-info p {
    margin-bottom: 15px;
    color: #666;
}

.contact-info i {
    color: #007bff;
}

.map-container {
    border-radius: 8px;
    overflow: hidden;
}

#about {
    background-color: #fff;
}

#about img {
    max-width: 100%;
    height: auto;
}

#about .list-unstyled li {
    margin-bottom: 10px;
}

#about .fas.fa-check {
    color: #28a745;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false,
        offset: 50
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
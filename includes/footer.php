<footer class="footer-modern">
    <div class="footer-top py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h4 class="widget-title">Agence Immo</h4>
                        <p class="footer-text">Votre partenaire de confiance pour trouver l'appartement de vos rêves.</p>
                        <div class="social-links">
                            <a href="#" class="social-link" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h4 class="widget-title">Navigation</h4>
                        <ul class="footer-links">
                            <li><a href="/Agence-immobilliere/index.php">Accueil</a></li>
                            <li><a href="/Agence-immobilliere/contact.php">Contact</a></li>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <li><a href="/Agence-immobilliere/favoris.php">Mes Favoris</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="widget-title">Contact</h4>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Rue de la République, Lyon</span>
                            </li>
                            <li>
                                <i class="fas fa-phone-alt"></i>
                                <span>+33 4 78 XX XX XX</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>contact@agenceimmo.fr</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="widget-title">Horaires d'ouverture</h4>
                        <ul class="footer-hours">
                            <li>
                                <span class="day">Lundi - Vendredi:</span>
                                <span class="hours">9h - 18h</span>
                            </li>
                            <li>
                                <span class="day">Samedi:</span>
                                <span class="hours">9h - 12h</span>
                            </li>
                            <li>
                                <span class="day">Dimanche:</span>
                                <span class="hours">Fermé</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright mb-0">
                        &copy; <?php echo date('Y'); ?> Agence Immo. Tous droits réservés.
                    </p>
                </div>
                <div class="col-md-6">
                    <ul class="footer-bottom-links">
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script>
    // Initialisation des animations AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });
</script>
<script src="/Agence-immobilliere/assets/js/main.js"></script>
</body>
</html>

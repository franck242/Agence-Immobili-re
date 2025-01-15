document.addEventListener('DOMContentLoaded', function() {
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Initialize Swiper on detail page
    if (document.querySelector('.swiper')) {
        const swiper = new Swiper('.swiper', {
            slidesPerView: 1,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        });
    }

    // Initialisation du carousel principal
    const mainCarousel = new Swiper('.main-carousel', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
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

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search form handling
    const searchForm = document.querySelector('#searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('includes/search_apartments.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                updateApartmentsList(data);
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Favorite toggle
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const apartmentId = this.dataset.id;
            
            fetch('includes/toggle_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ apartment_id: apartmentId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.classList.toggle('active');
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fas');
                    icon.classList.toggle('far');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Initialisation de AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Animation des cartes d'appartements au survol
    document.querySelectorAll('.apartment-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Fonction de défilement fluide
    function scrollToSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Animation au scroll
    const fadeElements = document.querySelectorAll('.property-card');
    
    fadeElements.forEach(element => {
        element.classList.add('fade-in');
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });

    fadeElements.forEach(element => {
        observer.observe(element);
    });

    // Animation de transition de page
    document.body.classList.add('page-transition');
});

// Function to update apartments list
function updateApartmentsList(data) {
    const container = document.querySelector('#appartements .row');
    if (!container) return;
    container.innerHTML = '';
    
    data.forEach(apartment => {
        const card = createApartmentCard(apartment);
        container.appendChild(card);
    });
}

// Function to create apartment card
function createApartmentCard(apartment) {
    const template = `
        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
            <div class="property-card">
                <div class="property-image">
                    <img src="img/${apartment.photos}" alt="${apartment.categorie}">
                    <div class="property-price">${apartment.prix} €/mois</div>
                </div>
                <div class="property-content">
                    <h3 class="property-title">${apartment.categorie}</h3>
                    <div class="property-details">
                        <span class="detail-item">
                            <i class="fas fa-ruler-combined"></i> ${apartment.superficie} m²
                        </span>
                        <span class="detail-item">
                            <i class="fas fa-bed"></i> ${apartment.lits} ch.
                        </span>
                        <span class="detail-item">
                            <i class="fas fa-bath"></i> ${apartment.salle_de_bains} SDB
                        </span>
                    </div>
                    <p class="property-description">${apartment.description}</p>
                    <a href="detail-appartements.php?apt=${apartment.Id_appartements}" class="btn btn-primary w-100">Voir détails</a>
                </div>
            </div>
        </div>
    `;
    
    const div = document.createElement('div');
    div.innerHTML = template.trim();
    return div.firstChild;
}

document.addEventListener('DOMContentLoaded', function() {
    // Gérer le menu hamburger avec Bootstrap
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');

    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            if (navbarCollapse.classList.contains('show')) {
                // Si le menu est ouvert, on le ferme
                bootstrap.Collapse.getInstance(navbarCollapse).hide();
            } else {
                // Si le menu est fermé, on l'ouvre
                new bootstrap.Collapse(navbarCollapse).show();
            }
        });
    }

    // Fermer le menu quand on clique sur un lien
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (navbarCollapse.classList.contains('show')) {
                bootstrap.Collapse.getInstance(navbarCollapse).hide();
            }
        });
    });

    // Fermer le menu quand on clique en dehors
    document.addEventListener('click', function(event) {
        if (!navbarCollapse.contains(event.target) && 
            !navbarToggler.contains(event.target) && 
            navbarCollapse.classList.contains('show')) {
            bootstrap.Collapse.getInstance(navbarCollapse).hide();
        }
    });
});

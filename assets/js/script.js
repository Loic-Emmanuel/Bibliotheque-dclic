// Script JavaScript pour les fonctionnalités interactives

document.addEventListener('DOMContentLoaded', function() {
    // Confirmation pour la suppression d'un livre de la liste de lecture
    const removeForms = document.querySelectorAll('.remove-form');
    
    removeForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir retirer ce livre de votre liste de lecture ?')) {
                e.preventDefault();
            }
        });
    });
    
    // Animation pour les cartes de livre
    const bookCards = document.querySelectorAll('.book-card');
    
    bookCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
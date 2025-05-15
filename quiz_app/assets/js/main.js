// main.js - Script JavaScript principal pour QuizApp
// Ce fichier contient les fonctionnalités JavaScript générales utilisées dans l'application

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des composants Bootstrap
    initializeBootstrapComponents();
    
    // Activation des tooltips
    activateTooltips();
    
    // Gestion des messages flash
    handleFlashMessages();
    
    // Validation des formulaires
    setupFormValidation();
    
    // Gestion des confirmations de suppression
    setupDeleteConfirmations();
    
    // Initialisation des filtres de tableaux (si présents)
    setupTableFilters();
});

/**
 * Initialise les composants Bootstrap
 */
function initializeBootstrapComponents() {
    // Initialisation des tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialisation des popovers Bootstrap
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Initialisation des dropdowns Bootstrap
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
}

/**
 * Active les tooltips sur les éléments avec l'attribut title
 */
function activateTooltips() {
    // Ajout de tooltips pour les boutons d'action qui ont un title
    const actionButtons = document.querySelectorAll('.btn[title]');
    actionButtons.forEach(button => {
        button.setAttribute('data-bs-toggle', 'tooltip');
        button.setAttribute('data-bs-placement', 'top');
    });
}

/**
 * Gestion des messages flash (succès, erreur, etc.)
 */
function handleFlashMessages() {
    // Auto-fermeture des messages flash après 5 secondes
    const flashMessages = document.querySelectorAll('.alert:not(.alert-permanent)');
    flashMessages.forEach(message => {
        setTimeout(() => {
            const alert = new bootstrap.Alert(message);
            alert.close();
        }, 5000);
    });
    
    // Gestion des boutons de fermeture des alertes
    const closeButtons = document.querySelectorAll('.alert .btn-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const alert = this.closest('.alert');
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    });
}

/**
 * Configuration de la validation des formulaires
 */
function setupFormValidation() {
    // Validation des formulaires avec la classe 'needs-validation'
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
    
    // Validation du formulaire d'inscription
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        
        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas.');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    }
}

/**
 * Configuration des confirmations de suppression
 */
function setupDeleteConfirmations() {
    // Confirmation avant suppression
    const deleteLinks = document.querySelectorAll('a[data-confirm]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Êtes-vous sûr de vouloir effectuer cette action ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Configuration des filtres de tableaux
 */
function setupTableFilters() {
    // Filtrer les tableaux en fonction des entrées
    const tableFilters = document.querySelectorAll('.table-filter');
    tableFilters.forEach(filter => {
        filter.addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const tableId = this.getAttribute('data-table');
            const table = document.getElementById(tableId);
            
            if (table) {
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.indexOf(searchText) > -1) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    });
}

/**
 * Fonction pour formater les dates en français
 * @param {string} dateString - Date au format ISO
 * @return {string} - Date formatée en français
 */
function formatDateFr(dateString) {
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', options);
}

/**
 * Fonction pour formater les timestamps en durée lisible
 * @param {number} seconds - Durée en secondes
 * @return {string} - Durée formatée
 */
function formatDuration(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    
    if (minutes > 0) {
        return `${minutes}min ${remainingSeconds}s`;
    } else {
        return `${remainingSeconds}s`;
    }
}

/**
 * Fonction pour calculer le pourcentage et retourner une classe CSS
 * @param {number} value - Valeur
 * @param {number} total - Total
 * @return {Object} - Pourcentage et classe CSS
 */
function calculatePercentageClass(value, total) {
    const percentage = (value / total) * 100;
    let cssClass = 'bg-danger';
    
    if (percentage >= 80) {
        cssClass = 'bg-success';
    } else if (percentage >= 60) {
        cssClass = 'bg-info';
    } else if (percentage >= 40) {
        cssClass = 'bg-warning';
    }
    
    return {
        percentage: Math.round(percentage),
        cssClass: cssClass
    };
}

/**
 * Fonction pour ajuster la hauteur des cartes dans une ligne
 */
function equalizeCardHeight() {
    // Sélection des groupes de cartes qui doivent avoir la même hauteur
    const cardGroups = document.querySelectorAll('.card-group-equal-height');
    
    cardGroups.forEach(group => {
        const cards = group.querySelectorAll('.card');
        let maxHeight = 0;
        
        // Réinitialiser les hauteurs
        cards.forEach(card => {
            card.style.height = 'auto';
            const height = card.offsetHeight;
            maxHeight = Math.max(maxHeight, height);
        });
        
        // Appliquer la hauteur maximale
        cards.forEach(card => {
            card.style.height = `${maxHeight}px`;
        });
    });
}

// Ajuster les hauteurs des cartes lors du redimensionnement de la fenêtre
window.addEventListener('resize', function() {
    equalizeCardHeight();
});

// Appeler cette fonction une fois le DOM chargé
document.addEventListener('DOMContentLoaded', function() {
    equalizeCardHeight();
});
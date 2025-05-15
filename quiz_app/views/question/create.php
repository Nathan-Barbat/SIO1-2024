<!-- views/question/create.php -->
<?php
// Page de création de question
$pageTitle = 'Créer une question';
Auth::requireTrainer(); // Vérification des droits formateur
require_once 'views/layout/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=question&action=index">Questions</a></li>
                <li class="breadcrumb-item active" aria-current="page">Créer une question</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Créer une nouvelle question</h3>
            </div>
            <div class="card-body">
                <form action="index.php?controller=question&action=create" method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="texte_question" class="form-label">Texte de la question</label>
                        <textarea class="form-control" id="texte_question" name="texte_question" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            Veuillez saisir le texte de la question.
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_categorie" class="form-label">Catégorie</label>
                            <select class="form-select" id="id_categorie" name="id_categorie" required>
                                <option value="" selected disabled>Sélectionnez une catégorie</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id_categorie']; ?>">
                                        <?php echo $category['nom_categorie']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Veuillez sélectionner une catégorie.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="niveau_difficulte" class="form-label">Niveau de difficulté</label>
                            <select class="form-select" id="niveau_difficulte" name="niveau_difficulte" required>
                                <option value="" selected disabled>Sélectionnez un niveau</option>
                                <option value="1">Facile</option>
                                <option value="2">Moyen</option>
                                <option value="3">Difficile</option>
                            </select>
                            <div class="invalid-feedback">
                                Veuillez sélectionner un niveau de difficulté.
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4>Choix de réponses</h4>
                    <p class="text-muted">Ajoutez au moins 2 choix de réponses et sélectionnez la réponse correcte.</p>
                    
                    <div id="choix-container">
                        <!-- Choix 1 -->
                        <div class="mb-3 choix-item">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="choix_correct" value="0" required aria-label="Choix correct">
                                </div>
                                <input type="text" class="form-control" name="choix_texte[]" placeholder="Choix 1" required>
                                <button type="button" class="btn btn-outline-danger remove-choix" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Choix 2 -->
                        <div class="mb-3 choix-item">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="radio" name="choix_correct" value="1" required aria-label="Choix correct">
                                </div>
                                <input type="text" class="form-control" name="choix_texte[]" placeholder="Choix 2" required>
                                <button type="button" class="btn btn-outline-danger remove-choix" disabled>
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <button type="button" id="add-choix" class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> Ajouter un choix
                        </button>
                    </div>
                    
                    <div class="alert alert-warning" id="choix-warning" style="display: none;">
                        Vous devez avoir au moins deux choix de réponses.
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php?controller=question&action=index" class="btn btn-outline-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer la question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Instructions</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Rédigez une question claire et concise.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Sélectionnez une catégorie appropriée.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Définissez le niveau de difficulté (1 = Facile, 2 = Moyen, 3 = Difficile).
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Ajoutez au moins deux choix de réponses.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Sélectionnez la réponse correcte (un seul choix possible).
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Conseils pour de bonnes questions</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Soyez précis et évitez les ambiguïtés.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Proposez des choix de réponses plausibles.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Adaptez la difficulté aux connaissances attendues.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check text-success me-2"></i>
                        Évitez les indices dans la formulation de la question.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const choixContainer = document.getElementById('choix-container');
    const addChoixBtn = document.getElementById('add-choix');
    const choixWarning = document.getElementById('choix-warning');
    
    // Ajouter un nouveau choix
    addChoixBtn.addEventListener('click', function() {
        const choixCount = document.querySelectorAll('.choix-item').length;
        
        const newChoix = document.createElement('div');
        newChoix.className = 'mb-3 choix-item';
        newChoix.innerHTML = `
            <div class="input-group">
                <div class="input-group-text">
                    <input class="form-check-input mt-0" type="radio" name="choix_correct" value="${choixCount}" required aria-label="Choix correct">
                </div>
                <input type="text" class="form-control" name="choix_texte[]" placeholder="Choix ${choixCount + 1}" required>
                <button type="button" class="btn btn-outline-danger remove-choix">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        choixContainer.appendChild(newChoix);
        updateRemoveButtons();
    });
    
    // Supprimer un choix (délégation d'événements)
    choixContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-choix')) {
            const button = e.target.closest('.remove-choix');
            const choixItem = button.closest('.choix-item');
            
            choixItem.remove();
            updateRadioValues();
            updateRemoveButtons();
        }
    });
    
    // Mettre à jour les valeurs des boutons radio
    function updateRadioValues() {
        const radioButtons = document.querySelectorAll('input[name="choix_correct"]');
        radioButtons.forEach((radio, index) => {
            radio.value = index;
        });
    }
    
    // Mettre à jour l'état des boutons de suppression
    function updateRemoveButtons() {
        const removeButtons = document.querySelectorAll('.remove-choix');
        const choixCount = removeButtons.length;
        
        if (choixCount <= 2) {
            choixWarning.style.display = 'none';
            removeButtons.forEach(button => {
                button.disabled = true;
            });
        } else {
            removeButtons.forEach(button => {
                button.disabled = false;
            });
        }
    }
    
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const choixCount = document.querySelectorAll('.choix-item').length;
        const radioChecked = document.querySelector('input[name="choix_correct"]:checked');
        
        if (choixCount < 2) {
            e.preventDefault();
            choixWarning.style.display = 'block';
            return;
        }
        
        if (!radioChecked) {
            e.preventDefault();
            alert('Veuillez sélectionner la réponse correcte.');
            return;
        }
        
        // Validation Bootstrap
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
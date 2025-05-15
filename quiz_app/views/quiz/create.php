<!-- views/quiz/create.php -->
<?php
// Page de création de quiz
$pageTitle = 'Créer un quiz';
Auth::requireTrainer(); // Vérification des droits formateur
require_once 'views/layout/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=quiz&action=index">Quiz</a></li>
                <li class="breadcrumb-item active" aria-current="page">Créer un quiz</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Créer un nouveau quiz</h3>
            </div>
            <div class="card-body">
                <form action="index.php?controller=quiz&action=create" method="post">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre du quiz</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <small class="form-text text-muted">Décrivez brièvement le contenu et l'objectif de ce quiz.</small>
                    </div>
                    
                    <hr>
                    
                    <h4>Sélection des questions</h4>
                    
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Sélectionnez au moins une question pour votre quiz. Vous pouvez filtrer les questions par catégorie et niveau de difficulté.
                        </div>
                    </div>
                    
                    <!-- Filtres de questions -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="filter-category" class="form-label">Filtrer par catégorie</label>
                            <select class="form-select" id="filter-category">
                                <option value="all">Toutes les catégories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id_categorie']; ?>">
                                        <?php echo $category['nom_categorie']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="filter-difficulty" class="form-label">Filtrer par difficulté</label>
                            <select class="form-select" id="filter-difficulty">
                                <option value="all">Tous les niveaux</option>
                                <option value="1">Facile</option>
                                <option value="2">Moyen</option>
                                <option value="3">Difficile</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Liste des questions -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="questions-table">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </div>
                                    </th>
                                    <th width="45%">Question</th>
                                    <th width="20%">Catégorie</th>
                                    <th width="15%">Difficulté</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($questions)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Aucune question disponible. <a href="index.php?controller=question&action=showCreateForm">Créer une question</a></td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($questions as $question): ?>
                                        <tr class="question-row" 
                                            data-category="<?php echo $question['id_categorie']; ?>" 
                                            data-difficulty="<?php echo $question['niveau_difficulte']; ?>">
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input question-checkbox" type="checkbox" name="questions[]" value="<?php echo $question['id_question']; ?>">
                                                </div>
                                            </td>
                                            <td><?php echo $question['texte_question']; ?></td>
                                            <td><?php echo $question['nom_categorie']; ?></td>
                                            <td>
                                                <?php 
                                                switch ($question['niveau_difficulte']) {
                                                    case 1:
                                                        echo '<span class="badge bg-success">Facile</span>';
                                                        break;
                                                    case 2:
                                                        echo '<span class="badge bg-warning text-dark">Moyen</span>';
                                                        break;
                                                    case 3:
                                                        echo '<span class="badge bg-danger">Difficile</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary">Inconnu</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-info preview-btn" data-id="<?php echo $question['id_question']; ?>">
                                                    <i class="fas fa-eye"></i> Aperçu
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-warning mt-3" id="no-questions-selected" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i> Veuillez sélectionner au moins une question.
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php?controller=quiz&action=index" class="btn btn-outline-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">Créer le quiz</button>
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
                        Donnez un titre explicite à votre quiz.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Sélectionnez les questions que vous souhaitez inclure.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Vous pouvez filtrer les questions par catégorie et niveau de difficulté.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        L'ordre des questions correspondra à l'ordre dans lequel vous les avez sélectionnées.
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Aperçu de la question</h5>
            </div>
            <div class="card-body" id="question-preview">
                <div class="text-center text-muted">
                    <p><i class="fas fa-eye fa-3x mb-3"></i></p>
                    <p>Cliquez sur "Aperçu" à côté d'une question pour voir les détails.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript pour les filtres et l'aperçu -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrage des questions
    const filterCategory = document.getElementById('filter-category');
    const filterDifficulty = document.getElementById('filter-difficulty');
    const questionRows = document.querySelectorAll('.question-row');
    const selectAll = document.getElementById('select-all');
    const submitBtn = document.getElementById('submit-btn');
    const noQuestionsSelected = document.getElementById('no-questions-selected');
    
    // Fonction pour filtrer les questions
    function filterQuestions() {
        const categoryValue = filterCategory.value;
        const difficultyValue = filterDifficulty.value;
        
        questionRows.forEach(row => {
            const rowCategory = row.dataset.category;
            const rowDifficulty = row.dataset.difficulty;
            
            const categoryMatch = categoryValue === 'all' || rowCategory === categoryValue;
            const difficultyMatch = difficultyValue === 'all' || rowDifficulty === difficultyValue;
            
            if (categoryMatch && difficultyMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Écouteurs d'événements pour les filtres
    filterCategory.addEventListener('change', filterQuestions);
    filterDifficulty.addEventListener('change', filterQuestions);
    
    // Sélectionner/désélectionner toutes les questions visibles
    selectAll.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.question-checkbox');
        checkboxes.forEach(checkbox => {
            const row = checkbox.closest('tr');
            if (row.style.display !== 'none') {
                checkbox.checked = selectAll.checked;
            }
        });
    });
    
    // Aperçu des questions
    const previewBtns = document.querySelectorAll('.preview-btn');
    const questionPreview = document.getElementById('question-preview');
    
    previewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const questionId = this.dataset.id;
            const questionText = this.closest('tr').querySelector('td:nth-child(2)').textContent;
            const categoryText = this.closest('tr').querySelector('td:nth-child(3)').textContent;
            const difficultyBadge = this.closest('tr').querySelector('td:nth-child(4)').innerHTML;
            
            // Affichage de l'aperçu
            questionPreview.innerHTML = `
                <h6 class="mb-3">${questionText}</h6>
                <p><strong>Catégorie :</strong> ${categoryText}</p>
                <p><strong>Difficulté :</strong> ${difficultyBadge}</p>
                <p class="text-muted small">Pour voir les choix de réponses, veuillez éditer la question.</p>
            `;
        });
    });
    
    // Validation du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        const selectedQuestions = document.querySelectorAll('.question-checkbox:checked');
        
        if (selectedQuestions.length === 0) {
            e.preventDefault();
            noQuestionsSelected.style.display = 'block';
            window.scrollTo(0, noQuestionsSelected.offsetTop - 100);
        } else {
            noQuestionsSelected.style.display = 'none';
        }
    });
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
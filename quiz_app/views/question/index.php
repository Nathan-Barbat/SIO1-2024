<!-- views/question/index.php -->
<?php
// Page d'index des questions
$pageTitle = 'Gestion des questions';
Auth::requireTrainer(); // Vérification des droits formateur
require_once 'views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Gestion des questions</h1>
            <a href="index.php?controller=question&action=showCreateForm" class="btn btn-primary">
                <i class="fas fa-plus"></i> Créer une question
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Filtres</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="filter-category" class="form-label">Filtrer par catégorie</label>
                            <select class="form-select" id="filter-category" onchange="window.location.href='index.php?controller=question&action=index&category=' + this.value">
                                <option value="">Toutes les catégories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id_categorie']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id_categorie']) ? 'selected' : ''; ?>>
                                        <?php echo $category['nom_categorie']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="filter-difficulty" class="form-label">Filtrer par difficulté</label>
                            <select class="form-select" id="filter-difficulty" onchange="window.location.href='index.php?controller=question&action=index&difficulty=' + this.value">
                                <option value="">Tous les niveaux</option>
                                <option value="1" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] == 1) ? 'selected' : ''; ?>>Facile</option>
                                <option value="2" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] == 2) ? 'selected' : ''; ?>>Moyen</option>
                                <option value="3" <?php echo (isset($_GET['difficulty']) && $_GET['difficulty'] == 3) ? 'selected' : ''; ?>>Difficile</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="search-question" class="form-label">Rechercher</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search-question" placeholder="Rechercher une question...">
                                <button class="btn btn-outline-secondary" type="button" id="search-btn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Liste des questions</h5>
                    <span class="badge bg-light text-dark"><?php echo count($questions); ?> questions</span>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($questions)): ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Aucune question n'a été trouvée. <a href="index.php?controller=question&action=showCreateForm">Créer une question</a></p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="questions-table">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="45%">Question</th>
                                    <th width="15%">Catégorie</th>
                                    <th width="15%">Difficulté</th>
                                    <th width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($questions as $index => $question): ?>
                                    <tr class="question-row">
                                        <td><?php echo $index + 1; ?></td>
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
                                            <div class="btn-group" role="group">
                                                <a href="index.php?controller=question&action=showEditForm&id=<?php echo $question['id_question']; ?>" class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info preview-btn" title="Aperçu" data-id="<?php echo $question['id_question']; ?>" data-bs-toggle="modal" data-bs-target="#previewModal">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="index.php?controller=question&action=delete&id=<?php echo $question['id_question']; ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour l'aperçu de la question -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="previewModalLabel">Aperçu de la question</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewModalBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p>Chargement de la question...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche de questions
    const searchInput = document.getElementById('search-question');
    const searchBtn = document.getElementById('search-btn');
    const questionRows = document.querySelectorAll('.question-row');
    
    function searchQuestions() {
        const searchText = searchInput.value.toLowerCase();
        
        questionRows.forEach(row => {
            const questionText = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const categoryText = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            
            if (questionText.includes(searchText) || categoryText.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    searchBtn.addEventListener('click', searchQuestions);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchQuestions();
        }
    });
    
    // Aperçu de la question
    const previewBtns = document.querySelectorAll('.preview-btn');
    const previewModalBody = document.getElementById('previewModalBody');
    
    previewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const questionId = this.getAttribute('data-id');
            const questionText = this.closest('tr').querySelector('td:nth-child(2)').textContent;
            const categoryText = this.closest('tr').querySelector('td:nth-child(3)').textContent;
            const difficultyBadge = this.closest('tr').querySelector('td:nth-child(4)').innerHTML;
            
            // Affichage de la question dans la modal
            previewModalBody.innerHTML = `
                <h4 class="mb-3">${questionText}</h4>
                <div class="mb-3">
                    <strong>Catégorie :</strong> ${categoryText}
                </div>
                <div class="mb-3">
                    <strong>Difficulté :</strong> ${difficultyBadge}
                </div>
                <hr>
                <div class="mb-3">
                    <strong>Choix de réponses :</strong>
                    <div class="mt-2">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <span>Chargement des choix...</span>
                    </div>
                </div>
            `;
            
            // Simuler le chargement des choix (dans une application réelle, vous feriez un appel AJAX)
            setTimeout(() => {
                // Ici, vous pourriez faire un appel AJAX pour récupérer les choix
                // Pour l'exemple, nous affichons des choix statiques
                const choicesHtml = `
                    <div class="list-group">
                        <button type="button" class="list-group-item list-group-item-action">
                            Choix 1 - <span class="badge bg-success">Correct</span>
                        </button>
                        <button type="button" class="list-group-item list-group-item-action">
                            Choix 2
                        </button>
                        <button type="button" class="list-group-item list-group-item-action">
                            Choix 3
                        </button>
                    </div>
                    <div class="mt-3 text-muted small">
                        <em>Note : Pour voir les choix réels et modifier la question, utilisez le bouton "Modifier".</em>
                    </div>
                `;
                
                previewModalBody.querySelector('.mb-3:last-child').innerHTML = `
                    <strong>Choix de réponses :</strong>
                    <div class="mt-2">
                        ${choicesHtml}
                    </div>
                `;
            }, 1000);
        });
    });
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
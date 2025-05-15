<!-- views/category/create.php -->
<?php
// Page de création de catégorie
$pageTitle = 'Créer une catégorie';
Auth::requireTrainer(); // Vérification des droits formateur
require_once 'views/layout/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=category&action=index">Catégories</a></li>
                <li class="breadcrumb-item active" aria-current="page">Créer une catégorie</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Créer une nouvelle catégorie</h3>
            </div>
            <div class="card-body">
                <form action="index.php?controller=category&action=create" method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="nom_categorie" class="form-label">Nom de la catégorie</label>
                        <input type="text" class="form-control" id="nom_categorie" name="nom_categorie" required>
                        <div class="invalid-feedback">
                            Veuillez saisir un nom pour la catégorie.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (facultative)</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        <small class="form-text text-muted">Une brève description de la catégorie.</small>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=category&action=index" class="btn btn-outline-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Créer la catégorie</button>
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
                        Donnez un nom clair et explicite à votre catégorie.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        La description est facultative mais recommandée pour mieux comprendre le contenu de la catégorie.
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Après avoir créé la catégorie, vous pourrez y associer des questions.
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Exemples de catégories</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Culture générale</strong>
                        <p class="small text-muted mb-0">Questions variées sur des sujets divers.</p>
                    </li>
                    <li class="list-group-item">
                        <strong>Mathématiques</strong>
                        <p class="small text-muted mb-0">Questions de calcul, algèbre, géométrie, etc.</p>
                    </li>
                    <li class="list-group-item">
                        <strong>Programmation</strong>
                        <p class="small text-muted mb-0">Questions sur les langages et techniques de programmation.</p>
                    </li>
                    <li class="list-group-item">
                        <strong>Base de données</strong>
                        <p class="small text-muted mb-0">Questions sur SQL, modélisation, etc.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
<!-- views/category/edit.php -->
<?php
// Page d'édition de catégorie
$pageTitle = 'Modifier une catégorie';
Auth::requireTrainer(); // Vérification des droits formateur
require_once 'views/layout/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=category&action=index">Catégories</a></li>
                <li class="breadcrumb-item active" aria-current="page">Modifier une catégorie</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Modifier une catégorie</h3>
            </div>
            <div class="card-body">
                <form action="index.php?controller=category&action=update" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id_categorie" value="<?php echo $category['id_categorie']; ?>">
                    
                    <div class="mb-3">
                        <label for="nom_categorie" class="form-label">Nom de la catégorie</label>
                        <input type="text" class="form-control" id="nom_categorie" name="nom_categorie" value="<?php echo $category['nom_categorie']; ?>" required>
                        <div class="invalid-feedback">
                            Veuillez saisir un nom pour la catégorie.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (facultative)</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo $category['description']; ?></textarea>
                        <small class="form-text text-muted">Une brève description de la catégorie.</small>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php?controller=category&action=index" class="btn btn-outline-secondary me-md-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <p><strong>Nombre de questions associées :</strong> 
                    <?php 
                    if (isset($category['nombre_questions'])) {
                        echo $category['nombre_questions'];
                    } else {
                        echo '0';
                    }
                    ?>
                </p>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> La modification du nom ou de la description de la catégorie n'affectera pas les questions déjà créées.
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="index.php?controller=category&action=index" class="list-group-item list-group-item-action">
                        <i class="fas fa-arrow-left me-2"></i> Retour à la liste des catégories
                    </a>
                    <a href="index.php?controller=question&action=index" class="list-group-item list-group-item-action">
                        <i class="fas fa-question-circle me-2"></i> Gérer les questions
                    </a>
                    <?php if (isset($category['id_categorie'])): ?>
                        <a href="index.php?controller=question&action=showCreateForm&category=<?php echo $category['id_categorie']; ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus me-2"></i> Ajouter une question dans cette catégorie
                        </a>
                    <?php endif; ?>
                </div>
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
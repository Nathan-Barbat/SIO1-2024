<!-- views/category/index.php -->
<?php
// Page d'index des catégories
$pageTitle = 'Gestion des catégories';
Auth::requireTrainer(); // Vérification des droits formateur
require_once 'views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Gestion des catégories</h1>
            <a href="index.php?controller=category&action=showCreateForm" class="btn btn-primary">
                <i class="fas fa-plus"></i> Créer une catégorie
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Liste des catégories</h3>
                <span class="badge bg-light text-dark"><?php echo count($categories); ?> catégories</span>
            </div>
            <div class="card-body">
                <?php if (empty($categories)): ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Aucune catégorie n'a été trouvée. <a href="index.php?controller=category&action=showCreateForm">Créer une catégorie</a></p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Nom</th>
                                    <th width="40%">Description</th>
                                    <th width="15%">Questions</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $index => $category): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo $category['nom_categorie']; ?></td>
                                        <td><?php echo $category['description'] ? $category['description'] : '<em class="text-muted">Aucune description</em>'; ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary"><?php echo $category['nombre_questions']; ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="index.php?controller=category&action=showEditForm&id=<?php echo $category['id_categorie']; ?>" class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="index.php?controller=category&action=delete&id=<?php echo $category['id_categorie']; ?>" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est impossible si des questions y sont associées.');">
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
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <p>Les catégories permettent d'organiser les questions par thème.</p>
                <p>Vous pouvez créer, modifier et supprimer des catégories depuis cette page.</p>
                <p><strong>Note :</strong> Une catégorie ne peut pas être supprimée si des questions y sont associées.</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="index.php?controller=category&action=showCreateForm" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus me-2"></i> Créer une catégorie
                    </a>
                    <a href="index.php?controller=question&action=index" class="list-group-item list-group-item-action">
                        <i class="fas fa-question-circle me-2"></i> Gérer les questions
                    </a>
                    <a href="index.php?controller=quiz&action=index" class="list-group-item list-group-item-action">
                        <i class="fas fa-list me-2"></i> Voir les quiz
                    </a>
                    <a href="index.php?controller=dashboard&action=trainer" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
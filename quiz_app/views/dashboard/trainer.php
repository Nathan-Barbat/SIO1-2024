<!-- views/dashboard/trainer.php -->
<?php
// Tableau de bord du formateur
$pageTitle = 'Tableau de bord formateur';
Auth::requireTrainer(); // Vérification des droits
require_once 'views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Tableau de bord formateur</h1>
            <a href="index.php?controller=quiz&action=showCreateForm" class="btn btn-primary">
                <i class="fas fa-plus"></i> Créer un quiz
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Mes quiz</h5>
                <span class="badge bg-light text-dark"><?php echo count($quizzes); ?> quiz</span>
            </div>
            <div class="card-body">
                <?php if (empty($quizzes)): ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Vous n'avez pas encore créé de quiz. <a href="index.php?controller=quiz&action=showCreateForm">Créer votre premier quiz</a></p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Date de création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quizzes as $quiz): ?>
                                    <tr>
                                        <td><?php echo $quiz['titre_quiz']; ?></td>
                                        <td><?php echo mb_substr($quiz['description'], 0, 50); echo (mb_strlen($quiz['description']) > 50) ? '...' : ''; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($quiz['date_creation'])); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="index.php?controller=quiz&action=view&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline-primary" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="index.php?controller=score&action=quiz&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline-info" title="Scores">
                                                    <i class="fas fa-chart-bar"></i>
                                                </a>
                                                <a href="index.php?controller=quiz&action=showEditForm&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="index.php?controller=quiz&action=delete&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?')">
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
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Classement des stagiaires</h5>
            </div>
            <div class="card-body">
                <?php if (empty($ranking)): ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Aucun score n'a encore été enregistré.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Quiz joués</th>
                                    <th>Score moyen</th>
                                    <th>Meilleur score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ranking as $index => $student): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo $student['prenom'] . ' ' . $student['nom']; ?></td>
                                        <td><?php echo $student['quiz_count']; ?></td>
                                        <td><?php echo number_format($student['avg_score'], 1); ?></td>
                                        <td><?php echo $student['best_score']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <a href="index.php?controller=score&action=ranking" class="btn btn-sm btn-outline-primary">Voir le classement complet</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Statistiques globales</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>Quiz créés</div>
                    <div><b><?php echo count($quizzes); ?></b></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Tentatives totales</div>
                    <div><b><?php echo isset($stats['total_attempts']) ? $stats['total_attempts'] : 0; ?></b></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>Score moyen</div>
                    <div><b><?php echo isset($stats['global_avg']) ? number_format($stats['global_avg'], 1) : 0; ?></b></div>
                </div>
                
                <?php if (isset($stats['most_played']) && $stats['most_played']): ?>
                    <hr>
                    <h6 class="card-subtitle mb-2 text-muted">Quiz le plus populaire :</h6>
                    <p class="card-text"><?php echo $stats['most_played']['titre_quiz']; ?></p>
                    <p class="small text-muted"><?php echo $stats['most_played']['play_count']; ?> tentatives</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="index.php?controller=quiz&action=showCreateForm" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus me-2"></i> Créer un quiz
                    </a>
                    <a href="index.php?controller=question&action=showCreateForm" class="list-group-item list-group-item-action">
                        <i class="fas fa-question-circle me-2"></i> Ajouter une question
                    </a>
                    <a href="index.php?controller=category&action=showCreateForm" class="list-group-item list-group-item-action">
                        <i class="fas fa-folder me-2"></i> Ajouter une catégorie
                    </a>
                    <a href="index.php?controller=question&action=index" class="list-group-item list-group-item-action">
                        <i class="fas fa-list me-2"></i> Gérer les questions
                    </a>
                    <a href="index.php?controller=category&action=index" class="list-group-item list-group-item-action">
                        <i class="fas fa-tasks me-2"></i> Gérer les catégories
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
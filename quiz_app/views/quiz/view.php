<!-- views/quiz/view.php -->
<?php
// Page de visualisation d'un quiz
$pageTitle = 'Détails du quiz';
Auth::requireLogin(); // Vérification de la connexion
require_once 'views/layout/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=quiz&action=index">Quiz</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $quiz['titre_quiz']; ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><?php echo $quiz['titre_quiz']; ?></h1>
            <div>
                <?php if (Auth::isStudent()): ?>
                    <a href="index.php?controller=quiz&action=play&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-success">
                        <i class="fas fa-play"></i> Jouer
                    </a>
                <?php elseif (Auth::isTrainer() && $quiz['id_formateur'] == $_SESSION['user_id']): ?>
                    <div class="btn-group" role="group">
                        <a href="index.php?controller=score&action=quiz&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Scores
                        </a>
                        <a href="index.php?controller=quiz&action=showEditForm&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="index.php?controller=quiz&action=delete&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?');">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Description</h3>
            </div>
            <div class="card-body">
                <p><?php echo $quiz['description'] ? $quiz['description'] : 'Aucune description disponible.'; ?></p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Questions</h3>
                <span class="badge bg-light text-dark"><?php echo count($quiz['questions']); ?> questions</span>
            </div>
            <div class="card-body">
                <?php if (empty($quiz['questions'])): ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Ce quiz ne contient aucune question.</p>
                    </div>
                <?php else: ?>
                    <div class="accordion" id="accordionQuestions">
                        <?php foreach ($quiz['questions'] as $index => $question): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="false" aria-controls="collapse<?php echo $index; ?>">
                                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                            <div>
                                                <span class="badge bg-secondary me-2"><?php echo $index + 1; ?></span>
                                                <?php echo $question['texte_question']; ?>
                                            </div>
                                            <div>
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
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#accordionQuestions">
                                    <div class="accordion-body">
                                        <?php if (Auth::isTrainer()): ?>
                                            <div class="mb-3">
                                                <strong>Choix de réponses :</strong>
                                                <ul class="list-group mt-2">
                                                    <?php foreach ($question['choix'] as $choix): ?>
                                                        <li class="list-group-item <?php echo $choix['est_correcte'] ? 'list-group-item-success' : ''; ?>">
                                                            <?php echo $choix['texte_choix']; ?>
                                                            <?php if ($choix['est_correcte']): ?>
                                                                <span class="badge bg-success float-end">Réponse correcte</span>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="badge bg-info">Catégorie: <?php echo $question['nom_categorie']; ?></span>
                                                </div>
                                                <div>
                                                    <a href="index.php?controller=question&action=showEditForm&id=<?php echo $question['id_question']; ?>" class="btn btn-sm btn-outline-warning">
                                                        <i class="fas fa-edit"></i> Modifier cette question
                                                    </a>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info">
                                                <p class="mb-0">Les réponses ne sont visibles que pendant le jeu. <a href="index.php?controller=quiz&action=play&id=<?php echo $quiz['id_quiz']; ?>">Jouer maintenant</a></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
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
                <div class="d-flex justify-content-between mb-3">
                    <div>Créé par</div>
                    <div><b><?php echo $quiz['prenom'] . ' ' . $quiz['nom']; ?></b></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Date de création</div>
                    <div><b><?php echo date('d/m/Y', strtotime($quiz['date_creation'])); ?></b></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Nombre de questions</div>
                    <div><b><?php echo count($quiz['questions']); ?></b></div>
                </div>
                
                <?php if (Auth::isTrainer() && isset($scores) && !empty($scores)): ?>
                    <div class="d-flex justify-content-between">
                        <div>Participations</div>
                        <div><b><?php echo count($scores); ?></b></div>
                    </div>
                    
                    <hr>
                    
                    <a href="index.php?controller=score&action=quiz&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-chart-bar"></i> Voir tous les scores
                    </a>
                <?php endif; ?>
                
                <?php if (Auth::isStudent()): ?>
                    <?php if (isset($bestScore) && $bestScore): ?>
                        <hr>
                        <h6>Votre meilleur score :</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <div>Score</div>
                            <div><b><?php echo $bestScore['points']; ?> / <?php echo count($quiz['questions']); ?></b></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <div>Temps</div>
                            <div><b><?php echo $bestScore['temps_total']; ?> secondes</b></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Date</div>
                            <div><b><?php echo date('d/m/Y', strtotime($bestScore['date'])); ?></b></div>
                        </div>
                    <?php else: ?>
                        <hr>
                        <div class="alert alert-info">
                            <p class="mb-0">Vous n'avez pas encore joué à ce quiz.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (Auth::isStudent()): ?>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Prêt à jouer ?</h5>
                </div>
                <div class="card-body">
                    <p>Ce quiz contient <?php echo count($quiz['questions']); ?> questions.</p>
                    <p>Vous avez 10 secondes pour répondre à chaque question.</p>
                    <p>Testez vos connaissances et essayez d'obtenir le meilleur score !</p>
                    
                    <div class="d-grid gap-2 mt-3">
                        <a href="index.php?controller=quiz&action=play&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-success">
                            <i class="fas fa-play"></i> Commencer le quiz
                        </a>
                    </div>
                </div>
            </div>
        <?php elseif (Auth::isTrainer() && $quiz['id_formateur'] == $_SESSION['user_id']): ?>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="index.php?controller=quiz&action=showEditForm&id=<?php echo $quiz['id_quiz']; ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-edit me-2"></i> Modifier le quiz
                        </a>
                        <a href="index.php?controller=score&action=quiz&id=<?php echo $quiz['id_quiz']; ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i> Voir les scores
                        </a>
                        <a href="index.php?controller=quiz&action=delete&id=<?php echo $quiz['id_quiz']; ?>" class="list-group-item list-group-item-action text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?');">
                            <i class="fas fa-trash me-2"></i> Supprimer le quiz
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
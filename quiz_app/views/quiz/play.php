<!-- views/quiz/play.php -->
<?php
// Page de jeu de quiz
$pageTitle = 'Jouer au quiz';
$pageScript = 'quiz'; // Script JavaScript spécifique pour le quiz
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

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0"><?php echo $quiz['titre_quiz']; ?></h3>
            </div>
            <div class="card-body">
                <div id="quiz-description" class="mb-4">
                    <p><?php echo $quiz['description']; ?></p>
                    <p><strong>Nombre de questions :</strong> <?php echo count($quiz['questions']); ?></p>
                    <p><strong>Temps par question :</strong> 10 secondes</p>
                </div>
                
                <div id="quiz-container" style="display: none;">
                    <!-- Progression -->
                    <div class="progress mb-3" style="height: 30px;">
                        <div id="progress-bar" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            Question <span id="current-question">1</span> / <span id="total-questions"><?php echo count($quiz['questions']); ?></span>
                        </div>
                    </div>
                    
                    <!-- Timer -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 id="question-title" class="mb-0">Question</h5>
                        <div id="timer" class="badge bg-primary fs-6">10</div>
                    </div>
                    
                    <!-- Question -->
                    <div id="question-container" class="mb-4 p-3 border rounded">
                        <p id="question-text" class="lead"></p>
                        <div id="difficulty-badge" class="mb-3"></div>
                        
                        <!-- Choix de réponses -->
                        <div id="choices-container" class="list-group mb-3">
                            <!-- Les choix seront ajoutés dynamiquement par JavaScript -->
                        </div>
                        
                        <!-- Feedback après réponse -->
                        <div id="feedback-container" class="alert mt-3" style="display: none;"></div>
                    </div>
                    
                    <!-- Boutons -->
                    <div class="d-flex justify-content-between">
                        <button id="btn-skip" class="btn btn-warning" disabled>
                            <i class="fas fa-forward"></i> Passer
                        </button>
                        <button id="btn-next" class="btn btn-primary" disabled>
                            Question suivante <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Résultats finaux -->
                <div id="results-container" style="display: none;">
                    <h3 class="text-center mb-4">Quiz terminé !</h3>
                    
                    <div class="text-center mb-4">
                        <div id="score-circle" class="mx-auto mb-3" style="width: 150px; height: 150px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; color: white;">
                            0%
                        </div>
                        <h4>Votre score: <span id="final-score">0</span> / <span id="max-score">0</span></h4>
                        <p>Temps total: <span id="total-time">0</span> secondes</p>
                    </div>
                    
                    <div id="feedback-message" class="alert alert-info text-center mb-4">
                        <!-- Message de feedback en fonction du score -->
                    </div>
                    
                    <!-- Formulaire pour enregistrer le score -->
                    <form id="score-form" action="index.php?controller=quiz&action=saveScore" method="post">
                        <input type="hidden" name="id_quiz" value="<?php echo $quiz['id_quiz']; ?>">
                        <input type="hidden" id="points" name="points" value="0">
                        <input type="hidden" id="temps_total" name="temps_total" value="0">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Enregistrer mon score</button>
                            <a href="index.php?controller=quiz&action=index" class="btn btn-outline-primary">Retour aux quiz</a>
                            <button type="button" id="btn-replay" class="btn btn-outline-warning">Rejouer ce quiz</button>
                        </div>
                    </form>
                </div>
                
                <!-- Bouton pour commencer -->
                <div id="start-container" class="text-center">
                    <button id="btn-start" class="btn btn-lg btn-success">
                        <i class="fas fa-play"></i> Commencer le quiz
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <p><strong>Créé par :</strong> <?php echo $quiz['prenom'] . ' ' . $quiz['nom']; ?></p>
                <p><strong>Date de création :</strong> <?php echo date('d/m/Y', strtotime($quiz['date_creation'])); ?></p>
                
                <?php if (isset($bestScore) && $bestScore): ?>
                    <hr>
                    <h6>Votre meilleur score :</h6>
                    <p><?php echo $bestScore['points']; ?> points en <?php echo $bestScore['temps_total']; ?> secondes</p>
                    <p class="text-muted small">Le <?php echo date('d/m/Y à H:i', strtotime($bestScore['date'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Instructions</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Vous avez <strong>10 secondes</strong> pour répondre à chaque question.</li>
                    <li class="list-group-item">Si vous ne répondez pas à temps, la question sera considérée comme incorrecte.</li>
                    <li class="list-group-item">Vous ne pouvez choisir qu'une seule réponse par question.</li>
                    <li class="list-group-item">Votre score final sera affiché à la fin du quiz.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Template pour les données des questions (JSON) -->
<script id="quiz-data" type="application/json">
    <?php 
    $quizData = [
        'id' => $quiz['id_quiz'],
        'title' => $quiz['titre_quiz'],
        'questions' => []
    ];
    
    foreach ($quiz['questions'] as $question) {
        $choices = [];
        $correctChoiceId = null;
        
        foreach ($question['choix'] as $choix) {
            $choices[] = [
                'id' => $choix['id_choix'],
                'text' => $choix['texte_choix']
            ];
            
            if ($choix['est_correcte']) {
                $correctChoiceId = $choix['id_choix'];
            }
        }
        
        $quizData['questions'][] = [
            'id' => $question['id_question'],
            'text' => $question['texte_question'],
            'difficulty' => $question['niveau_difficulte'],
            'choices' => $choices,
            'correctChoiceId' => $correctChoiceId
        ];
    }
    
    echo json_encode($quizData);
    ?>
</script>

<?php require_once 'views/layout/footer.php'; ?>
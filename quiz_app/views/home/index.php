<!-- views/home/index.php -->
<?php
// Page d'accueil
$pageTitle = 'Accueil';
require_once 'views/layout/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <h1 class="card-title">Bienvenue sur QuizApp</h1>
                <p class="card-text lead">La plateforme de quiz interactive pour apprendre et évaluer vos connaissances.</p>
                <hr>
                <p>QuizApp est une application développée pour les centres de formation souhaitant proposer à leurs stagiaires un outil d'auto-évaluation efficace et ludique.</p>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-graduation-cap me-2"></i>Stagiaires</h5>
                                <p class="card-text">Testez vos connaissances, suivez votre progression et améliorez vos compétences.</p>
                                <?php if (!Auth::isLoggedIn()): ?>
                                    <a href="index.php?controller=user&action=showRegisterForm" class="btn btn-primary">S'inscrire</a>
                                <?php elseif (Auth::isStudent()): ?>
                                    <a href="index.php?controller=quiz&action=index" class="btn btn-primary">Voir les quiz</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-chalkboard-teacher me-2"></i>Formateurs</h5>
                                <p class="card-text">Créez des quiz personnalisés, suivez les progrès des stagiaires et adaptez vos formations.</p>
                                <?php if (!Auth::isLoggedIn()): ?>
                                    <a href="index.php?controller=user&action=showRegisterForm" class="btn btn-primary">S'inscrire</a>
                                <?php elseif (Auth::isTrainer()): ?>
                                    <a href="index.php?controller=quiz&action=showCreateForm" class="btn btn-primary">Créer un quiz</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Fonctionnalités principales</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-question-circle fa-3x text-primary mb-2"></i>
                            <h5>Questions variées</h5>
                            <p>Des questions de différentes catégories et niveaux de difficulté.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-chart-line fa-3x text-primary mb-2"></i>
                            <h5>Suivi de progression</h5>
                            <p>Visualisez votre évolution et identifiez vos points forts et faibles.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center mb-3">
                            <i class="fas fa-trophy fa-3x text-primary mb-2"></i>
                            <h5>Classement</h5>
                            <p>Comparez vos résultats avec les autres stagiaires.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>Nombre de quiz</div>
                    <div><b><?php echo isset($quizCount) ? $quizCount : 0; ?></b></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Quiz joués</div>
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
        
        <?php if (!Auth::isLoggedIn()): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Connexion rapide</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?controller=user&action=login" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="index.php?controller=user&action=showRegisterForm">Créer un compte</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
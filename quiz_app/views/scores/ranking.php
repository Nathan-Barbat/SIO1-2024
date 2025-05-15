<!-- views/scores/ranking.php -->
<?php
// Page de classement général
$pageTitle = 'Classement général';
Auth::requireLogin(); // Vérification de la connexion
require_once 'views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Classement général</h1>
            <a href="index.php?controller=quiz&action=index" class="btn btn-primary">
                <i class="fas fa-play"></i> Jouer à un quiz
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Top des stagiaires</h3>
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
                                    <th class="text-center">Position</th>
                                    <th>Stagiaire</th>
                                    <th class="text-center">Quiz complétés</th>
                                    <th class="text-center">Score moyen</th>
                                    <th class="text-center">Meilleur score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ranking as $index => $student): ?>
                                    <tr <?php echo (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $student['id_utilisateur']) ? 'class="table-primary"' : ''; ?>>
                                        <td class="text-center">
                                            <?php if ($index == 0): ?>
                                                <span class="badge bg-warning" style="font-size: 1.2rem;"><i class="fas fa-trophy"></i> 1</span>
                                            <?php elseif ($index == 1): ?>
                                                <span class="badge bg-secondary" style="font-size: 1rem;"><i class="fas fa-trophy"></i> 2</span>
                                            <?php elseif ($index == 2): ?>
                                                <span class="badge bg-danger" style="font-size: 0.9rem;"><i class="fas fa-trophy"></i> 3</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark"><?php echo $index + 1; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo $student['prenom'] . ' ' . $student['nom']; ?>
                                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $student['id_utilisateur']): ?>
                                                <span class="badge bg-info">Vous</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?php echo $student['quiz_count']; ?></td>
                                        <td class="text-center">
                                            <?php 
                                            $avgScore = number_format($student['avg_score'], 1);
                                            $percentage = min(100, max(0, $student['avg_score'] * 100));
                                            
                                            $bgClass = 'bg-danger';
                                            if ($percentage >= 80) {
                                                $bgClass = 'bg-success';
                                            } elseif ($percentage >= 60) {
                                                $bgClass = 'bg-info';
                                            } elseif ($percentage >= 40) {
                                                $bgClass = 'bg-warning';
                                            }
                                            ?>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="me-2"><?php echo $avgScore; ?></div>
                                                <div class="progress flex-grow-1" style="height: 10px; max-width: 100px;">
                                                    <div class="progress-bar <?php echo $bgClass; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%;" 
                                                        aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo $student['best_score']; ?></td>
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
                <h5 class="card-title mb-0">Statistiques globales</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>Nombre de participations</div>
                    <div><b><?php echo isset($stats['total_attempts']) ? $stats['total_attempts'] : 0; ?></b></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Score moyen global</div>
                    <div><b><?php echo isset($stats['global_avg']) ? number_format($stats['global_avg'], 1) : 0; ?></b></div>
                </div>
                
                <?php if (isset($stats['most_played']) && $stats['most_played']): ?>
                    <hr>
                    <h6 class="card-subtitle mb-2 text-muted">Quiz le plus populaire :</h6>
                    <p class="card-text"><?php echo $stats['most_played']['titre_quiz']; ?></p>
                    <p class="small text-muted">Joué <?php echo $stats['most_played']['play_count']; ?> fois</p>
                    <a href="index.php?controller=quiz&action=view&id=<?php echo $stats['most_played']['id_quiz']; ?>" class="btn btn-sm btn-outline-primary">Voir ce quiz</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (Auth::isStudent() && isset($_SESSION['user_id'])): ?>
            <?php 
            // Trouver la position de l'utilisateur dans le classement
            $userPosition = 0;
            $userFound = false;
            foreach ($ranking as $index => $student) {
                if ($_SESSION['user_id'] == $student['id_utilisateur']) {
                    $userPosition = $index + 1;
                    $userFound = true;
                    break;
                }
            }
            ?>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Votre classement</h5>
                </div>
                <div class="card-body">
                    <?php if ($userFound): ?>
                        <div class="text-center mb-3">
                            <div class="display-4">#<?php echo $userPosition; ?></div>
                            <p class="text-muted">sur <?php echo count($ranking); ?> stagiaires</p>
                        </div>
                        
                        <?php 
                        $percentage = ($userPosition / count($ranking)) * 100;
                        $percentile = 100 - $percentage;
                        $message = '';
                        $alertClass = '';
                        
                        if ($percentile >= 90) {
                            $message = 'Excellent ! Vous faites partie des meilleurs stagiaires.';
                            $alertClass = 'alert-success';
                        } elseif ($percentile >= 70) {
                            $message = 'Très bien ! Vous êtes dans le haut du classement.';
                            $alertClass = 'alert-success';
                        } elseif ($percentile >= 50) {
                            $message = 'Bien ! Vous êtes au-dessus de la moyenne.';
                            $alertClass = 'alert-info';
                        } elseif ($percentile >= 30) {
                            $message = 'Vous êtes dans la moyenne.';
                            $alertClass = 'alert-warning';
                        } else {
                            $message = 'Vous pouvez améliorer votre classement en participant à plus de quiz.';
                            $alertClass = 'alert-warning';
                        }
                        ?>
                        
                        <div class="alert <?php echo $alertClass; ?> text-center">
                            <?php echo $message; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <p>Vous n'apparaissez pas encore dans le classement.</p>
                            <p>Participez à des quiz pour y figurer !</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-2 mt-3">
                        <a href="index.php?controller=score&action=student" class="btn btn-primary">
                            <i class="fas fa-chart-bar"></i> Voir mes statistiques détaillées
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Comment améliorer son score</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Participez régulièrement à différents quiz
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Révisez les sujets avant de jouer
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Prenez le temps de lire attentivement les questions
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Rejouez aux quiz pour améliorer vos scores
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
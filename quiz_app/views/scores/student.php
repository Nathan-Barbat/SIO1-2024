<!-- views/scores/student.php -->
<?php
// Page des scores d'un stagiaire
$pageTitle = 'Mes scores';
Auth::requireStudent(); // Vérification qu'il s'agit bien d'un stagiaire
require_once 'views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Mes scores</h1>
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
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Historique des scores</h3>
                <span class="badge bg-light text-dark"><?php echo count($scores); ?> participations</span>
            </div>
            <div class="card-body">
                <?php if (empty($scores)): ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Vous n'avez pas encore joué à un quiz. <a href="index.php?controller=quiz&action=index">Jouer maintenant</a></p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Quiz</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Temps</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scores as $score): ?>
                                    <tr>
                                        <td><?php echo $score['titre_quiz']; ?></td>
                                        <td class="text-center">
                                            <?php 
                                            echo $score['points'] . ' / ' . $score['nombre_questions'];
                                            $percentage = ($score['points'] / $score['nombre_questions']) * 100;
                                            
                                            if ($percentage >= 80) {
                                                echo ' <span class="badge bg-success">Excellent</span>';
                                            } elseif ($percentage >= 60) {
                                                echo ' <span class="badge bg-info">Bon</span>';
                                            } elseif ($percentage >= 40) {
                                                echo ' <span class="badge bg-warning">Moyen</span>';
                                            } else {
                                                echo ' <span class="badge bg-danger">À revoir</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo $score['temps_total']; ?> secondes</td>
                                        <td class="text-center"><?php echo date('d/m/Y H:i', strtotime($score['date'])); ?></td>
                                        <td class="text-center">
                                            <a href="index.php?controller=quiz&action=play&id=<?php echo $score['id_quiz']; ?>" class="btn btn-sm btn-outline-primary" title="Rejouer">
                                                <i class="fas fa-redo"></i> Rejouer
                                            </a>
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
                <h5 class="card-title mb-0">Mes statistiques</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($scores)): ?>
                    <div class="text-center mb-4">
                        <?php 
                        $avgPercentage = (isset($stats['avg_score']) ? $stats['avg_score'] : 0) * 100;
                        $scoreClass = 'bg-danger';
                        
                        if ($avgPercentage >= 80) {
                            $scoreClass = 'bg-success';
                        } elseif ($avgPercentage >= 60) {
                            $scoreClass = 'bg-info';
                        } elseif ($avgPercentage >= 40) {
                            $scoreClass = 'bg-warning';
                        }
                        ?>
                        
                        <div class="mx-auto mb-3" style="width: 150px; height: 150px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; color: white; background-color: var(--<?php echo str_replace('bg-', '', $scoreClass); ?>);">
                            <?php echo number_format($avgPercentage, 0); ?>%
                        </div>
                        <h4>Score moyen global</h4>
                    </div>
                
                    <hr>
                <?php endif; ?>
                
                <div class="d-flex justify-content-between mb-3">
                    <div>Quiz complétés</div>
                    <div><b><?php echo isset($stats['quiz_count']) ? $stats['quiz_count'] : 0; ?></b></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Score moyen</div>
                    <div><b><?php echo isset($stats['avg_score']) ? number_format($stats['avg_score'], 2) : 0; ?></b></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Meilleur score</div>
                    <div><b><?php echo isset($stats['best_score']) ? $stats['best_score'] : 0; ?></b></div>
                </div>
                
                <?php if (!empty($scores)): ?>
                    <?php 
                    // Calcul du temps total
                    $totalTime = 0;
                    foreach ($scores as $score) {
                        $totalTime += $score['temps_total'];
                    }
                    
                    // Temps moyen par question
                    $totalQuestions = 0;
                    foreach ($scores as $score) {
                        $totalQuestions += $score['nombre_questions'];
                    }
                    
                    $avgTimePerQuestion = $totalQuestions > 0 ? $totalTime / $totalQuestions : 0;
                    ?>
                    
                    <div class="d-flex justify-content-between">
                        <div>Temps moyen par question</div>
                        <div><b><?php echo number_format($avgTimePerQuestion, 1); ?> secondes</b></div>
                    </div>
                <?php endif; ?>
                
                <?php if ($stats['quiz_count'] > 0): ?>
                    <hr>
                    
                    <?php 
                    // Message de feedback en fonction du score
                    $avgPercentage = $stats['avg_score'] * 100;
                    $feedbackMessage = '';
                    $feedbackClass = '';
                    
                    if ($avgPercentage >= 80) {
                        $feedbackMessage = 'Excellent ! Continuez comme ça !';
                        $feedbackClass = 'alert-success';
                    } elseif ($avgPercentage >= 60) {
                        $feedbackMessage = 'Bon travail ! Vous êtes sur la bonne voie.';
                        $feedbackClass = 'alert-info';
                    } elseif ($avgPercentage >= 40) {
                        $feedbackMessage = 'Pas mal, mais vous pouvez encore vous améliorer.';
                        $feedbackClass = 'alert-warning';
                    } else {
                        $feedbackMessage = 'Vous devriez réviser davantage et rejouer aux quiz pour progresser.';
                        $feedbackClass = 'alert-danger';
                    }
                    ?>
                    
                    <div class="alert <?php echo $feedbackClass; ?> text-center">
                        <?php echo $feedbackMessage; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="index.php?controller=quiz&action=index" class="list-group-item list-group-item-action">
                        <i class="fas fa-play me-2"></i> Jouer à un quiz
                    </a>
                    <a href="index.php?controller=score&action=ranking" class="list-group-item list-group-item-action">
                        <i class="fas fa-trophy me-2"></i> Voir le classement général
                    </a>
                    <a href="index.php?controller=dashboard&action=student" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
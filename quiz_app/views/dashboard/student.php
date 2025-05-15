<!-- views/dashboard/student.php -->
<?php
// Tableau de bord du stagiaire
$pageTitle = 'Tableau de bord stagiaire';
Auth::requireStudent(); // Vérification des droits
require_once 'views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Tableau de bord stagiaire</h1>
            <a href="index.php?controller=quiz&action=index" class="btn btn-primary">
                <i class="fas fa-play"></i> Jouer à un quiz
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Mes derniers scores</h5>
                <a href="index.php?controller=score&action=student" class="btn btn-sm btn-light">Voir tous mes scores</a>
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
                                    <th>Score</th>
                                    <th>Temps</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($scores, 0, 5) as $score): ?>
                                    <tr>
                                        <td><?php echo $score['titre_quiz']; ?></td>
                                        <td>
                                            <?php echo $score['points']; ?> / <?php echo $score['nombre_questions']; ?>
                                            <?php 
                                            $percentage = ($score['points'] / $score['nombre_questions']) * 100;
                                            if ($percentage >= 80) {
                                                echo '<span class="badge bg-success">Excellent</span>';
                                            } elseif ($percentage >= 60) {
                                                echo '<span class="badge bg-info">Bon</span>';
                                            } elseif ($percentage >= 40) {
                                                echo '<span class="badge bg-warning">Moyen</span>';
                                            } else {
                                                echo '<span class="badge bg-danger">À revoir</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $score['temps_total']; ?> secondes</td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($score['date'])); ?></td>
                                        <td>
                                            <a href="index.php?controller=quiz&action=play&id=<?php echo $score['id_quiz']; ?>" class="btn btn-sm btn-outline-primary" title="Rejouer">
                                                <i class="fas fa-redo"></i>
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
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Quiz recommandés</h5>
            </div>
            <div class="card-body">
                <?php if (empty($newQuizzes)): ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Vous avez déjà participé à tous les quiz disponibles. Consultez régulièrement pour voir les nouveaux quiz.</p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach (array_slice($newQuizzes, 0, 3) as $quiz): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $quiz['titre_quiz']; ?></h5>
                                        <p class="card-text small"><?php echo mb_substr($quiz['description'], 0, 80); echo (mb_strlen($quiz['description']) > 80) ? '...' : ''; ?></p>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <a href="index.php?controller=quiz&action=play&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-sm btn-primary w-100">Jouer</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($newQuizzes) > 3): ?>
                        <div class="text-center mt-3">
                            <a href="index.php?controller=quiz&action=index" class="btn btn-outline-primary">Voir tous les quiz</a>
                        </div>
                    <?php endif; ?>
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
                <div class="d-flex justify-content-between mb-3">
                    <div>Quiz joués</div>
                    <div><b><?php echo isset($stats['quiz_count']) ? $stats['quiz_count'] : 0; ?></b></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>Score moyen</div>
                    <div><b><?php echo isset($stats['avg_score']) ? number_format($stats['avg_score'], 1) : 0; ?></b></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>Meilleur score</div>
                    <div><b><?php echo isset($stats['best_score']) ? $stats['best_score'] : 0; ?></b></div>
                </div>
                
                <hr>
                
                <div class="progress mb-2" style="height: 20px;">
                    <?php 
                    $avg = isset($stats['avg_score']) ? $stats['avg_score'] : 0;
                    $percentage = min(100, max(0, $avg * 100));
                    $bgClass = 'bg-danger';
                    
                    if ($percentage >= 80) {
                        $bgClass = 'bg-success';
                    } elseif ($percentage >= 60) {
                        $bgClass = 'bg-info';
                    } elseif ($percentage >= 40) {
                        $bgClass = 'bg-warning';
                    }
                    ?>
                    <div class="progress-bar <?php echo $bgClass; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%;" 
                         aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                        <?php echo number_format($percentage, 0); ?>%
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="index.php?controller=score&action=ranking" class="btn btn-sm btn-outline-primary">Voir mon classement</a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="index.php?controller=quiz&action=index" class="list-group-item list-group-item-action">
                        <i class="fas fa-play me-2"></i> Jouer à un quiz
                    </a>
                    <a href="index.php?controller=score&action=student" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i> Voir mes scores
                    </a>
                    <a href="index.php?controller=score&action=ranking" class="list-group-item list-group-item-action">
                        <i class="fas fa-trophy me-2"></i> Consulter le classement
                    </a>
                    <a href="index.php?controller=user&action=profile" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Mon profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
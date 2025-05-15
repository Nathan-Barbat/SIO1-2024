<!-- views/scores/view.php -->
<?php
// Page de visualisation des scores
$pageTitle = 'Visualisation des scores';
Auth::requireLogin(); // Vérification de la connexion
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
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Historique des scores</h3>
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
                                <?php foreach ($scores as $score): ?>
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
                
                <h6>Progression globale</h6>
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
                
                <?php if ($percentage >= 80): ?>
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-trophy"></i> Excellent ! Continuez comme ça !
                    </div>
                <?php elseif ($percentage >= 60): ?>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-thumbs-up"></i> Bon travail ! Vous progressez bien.
                    </div>
                <?php elseif ($percentage >= 40): ?>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-chart-line"></i> Continuez vos efforts pour vous améliorer.
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-circle"></i> Il y a encore beaucoup de marge de progression.
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
                        <i class="fas fa-tachometer-alt me-2"></i> Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
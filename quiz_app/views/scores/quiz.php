<!-- views/scores/quiz.php -->
<?php
// Page de scores pour un quiz spécifique
$pageTitle = 'Scores du quiz';
Auth::requireTrainer(); // Vérification des droits formateur
require_once 'views/layout/header.php';
?>

<div class="row mb-3">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=quiz&action=index">Quiz</a></li>
                <li class="breadcrumb-item"><a href="index.php?controller=quiz&action=view&id=<?php echo $quiz['id_quiz']; ?>"><?php echo $quiz['titre_quiz']; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Scores</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Scores du quiz : <?php echo $quiz['titre_quiz']; ?></h1>
            <a href="index.php?controller=quiz&action=view&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Retour au quiz
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Classement des stagiaires</h3>
            </div>
            <div class="card-body">
                <?php if (empty($scores)): ?>
                    <div class="alert alert-info">
                        <p class="mb-0">Aucun stagiaire n'a encore participé à ce quiz.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">Position</th>
                                    <th>Stagiaire</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Temps</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scores as $index => $score): ?>
                                    <tr>
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
                                        <td><?php echo $score['prenom'] . ' ' . $score['nom']; ?></td>
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
                <h5 class="card-title mb-0">Statistiques du quiz</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>Nombre de participants</div>
                    <div><b><?php echo count($scores); ?></b></div>
                </div>
                
                <?php if (!empty($scores)): ?>
                    <?php 
                    // Calcul des statistiques
                    $totalPoints = 0;
                    $totalTime = 0;
                    $bestScore = 0;
                    $worstScore = $scores[0]['nombre_questions'];
                    $fastestTime = PHP_INT_MAX;
                    $slowestTime = 0;
                    
                    foreach ($scores as $score) {
                        $totalPoints += $score['points'];
                        $totalTime += $score['temps_total'];
                        $bestScore = max($bestScore, $score['points']);
                        $worstScore = min($worstScore, $score['points']);
                        $fastestTime = min($fastestTime, $score['temps_total']);
                        $slowestTime = max($slowestTime, $score['temps_total']);
                    }
                    
                    $avgScore = $totalPoints / count($scores);
                    $avgTime = $totalTime / count($scores);
                    $maxPossibleScore = $scores[0]['nombre_questions'];
                    $avgPercentage = ($avgScore / $maxPossibleScore) * 100;
                    ?>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>Score moyen</div>
                        <div><b><?php echo number_format($avgScore, 1); ?> / <?php echo $maxPossibleScore; ?></b></div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>Taux de réussite</div>
                        <div><b><?php echo number_format($avgPercentage, 1); ?>%</b></div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>Temps moyen</div>
                        <div><b><?php echo number_format($avgTime, 1); ?> secondes</b></div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>Meilleur score</div>
                        <div><b><?php echo $bestScore; ?> / <?php echo $maxPossibleScore; ?></b></div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>Score le plus bas</div>
                        <div><b><?php echo $worstScore; ?> / <?php echo $maxPossibleScore; ?></b></div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>Temps le plus rapide</div>
                        <div><b><?php echo $fastestTime; ?> secondes</b></div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>Temps le plus long</div>
                        <div><b><?php echo $slowestTime; ?> secondes</b></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="index.php?controller=quiz&action=view&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-eye"></i> Voir le quiz
                    </a>
                    
                    <a href="index.php?controller=quiz&action=showEditForm&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Modifier le quiz
                    </a>
                    
                    <a href="index.php?controller=dashboard&action=trainer" class="btn btn-outline-secondary">
                        <i class="fas fa-tachometer-alt"></i> Tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
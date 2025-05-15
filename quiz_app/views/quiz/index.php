<!-- views/quiz/index.php -->
<?php
// Page d'index des quiz
$pageTitle = 'Liste des Quiz';
Auth::requireLogin(); // Vérification de la connexion
require_once 'views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Tous les Quiz</h1>
            <?php if (Auth::isTrainer()): ?>
                <a href="index.php?controller=quiz&action=showCreateForm" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un quiz
                </a>
            <?php endif; ?>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <?php if (empty($quizzes)): ?>
        <div class="col-md-12">
            <div class="alert alert-info">
                <p class="mb-0">Aucun quiz n'est disponible pour le moment.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($quizzes as $quiz): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><?php echo $quiz['titre_quiz']; ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo mb_substr($quiz['description'], 0, 100); echo (mb_strlen($quiz['description']) > 100) ? '...' : ''; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($quiz['date_creation'])); ?>
                            </small>
                            <small class="text-muted">
                                Par <?php echo $quiz['prenom'] . ' ' . $quiz['nom']; ?>
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <?php if (Auth::isStudent()): ?>
                                <a href="index.php?controller=quiz&action=play&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-primary">
                                    <i class="fas fa-play"></i> Jouer
                                </a>
                            <?php else: ?>
                                <div class="btn-group w-100" role="group">
                                    <a href="index.php?controller=quiz&action=view&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <?php if (Auth::isTrainer() && isset($_SESSION['user_id']) && $quiz['id_formateur'] == $_SESSION['user_id']): ?>
                                        <a href="index.php?controller=quiz&action=showEditForm&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <a href="index.php?controller=quiz&action=delete&id=<?php echo $quiz['id_quiz']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'views/layout/footer.php'; ?>
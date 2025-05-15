<!-- views/user/profile.php -->
<?php
// Page de profil utilisateur
$pageTitle = 'Mon profil';
Auth::requireLogin(); // Vérification de la connexion
require_once 'views/layout/header.php';
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Mon profil</h1>
            <?php if (Auth::isTrainer()): ?>
                <a href="index.php?controller=dashboard&action=trainer" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i> Tableau de bord
                </a>
            <?php else: ?>
                <a href="index.php?controller=dashboard&action=student" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i> Tableau de bord
                </a>
            <?php endif; ?>
        </div>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Mes informations</h3>
            </div>
            <div class="card-body">
                <form action="index.php?controller=user&action=updateProfile" method="post" class="needs-validation" novalidate>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                            <div class="invalid-feedback">
                                Veuillez saisir votre nom.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                            <div class="invalid-feedback">
                                Veuillez saisir votre prénom.
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        <div class="invalid-feedback">
                            Veuillez saisir une adresse email valide.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Type de compte</label>
                        <input type="text" class="form-control" value="<?php echo $user['type_utilisateur'] === 'formateur' ? 'Formateur' : 'Stagiaire'; ?>" readonly>
                        <small class="form-text text-muted">Le type de compte ne peut pas être modifié.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Date d'inscription</label>
                        <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?>" readonly>
                    </div>
                    
                    <hr>
                    
                    <h4>Changer de mot de passe</h4>
                    <p class="text-muted">Laissez ces champs vides si vous ne souhaitez pas changer votre mot de passe.</p>
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" minlength="6">
                            <small class="form-text text-muted">Minimum 6 caractères</small>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6">
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Résumé</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="display-1">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h4 class="mt-2"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h4>
                    <p class="text-muted"><?php echo $user['type_utilisateur'] === 'formateur' ? 'Formateur' : 'Stagiaire'; ?></p>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <div>Email</div>
                    <div><b><?php echo htmlspecialchars($user['email']); ?></b></div>
                </div>
                <div class="d-flex justify-content-between">
                    <div>Inscrit depuis</div>
                    <div><b><?php echo date('d/m/Y', strtotime($user['date_inscription'])); ?></b></div>
                </div>
            </div>
        </div>
        
        <?php if (Auth::isTrainer()): ?>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="index.php?controller=quiz&action=index" class="list-group-item list-group-item-action">
                            <i class="fas fa-list me-2"></i> Mes quiz
                        </a>
                        <a href="index.php?controller=quiz&action=showCreateForm" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus me-2"></i> Créer un quiz
                        </a>
                        <a href="index.php?controller=dashboard&action=trainer" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="index.php?controller=quiz&action=index" class="list-group-item list-group-item-action">
                            <i class="fas fa-play me-2"></i> Jouer aux quiz
                        </a>
                        <a href="index.php?controller=score&action=student" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar me-2"></i> Mes scores
                        </a>
                        <a href="index.php?controller=score&action=ranking" class="list-group-item list-group-item-action">
                            <i class="fas fa-trophy me-2"></i> Classement
                        </a>
                        <a href="index.php?controller=dashboard&action=student" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Vérifier que les nouveaux mots de passe correspondent s'ils sont remplis
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        
        if (newPassword.value && newPassword.value !== confirmPassword.value) {
            e.preventDefault();
            confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas.');
        } else {
            confirmPassword.setCustomValidity('');
        }
        
        form.classList.add('was-validated');
    });
    
    // Réinitialiser la validation personnalisée lorsque l'utilisateur modifie le champ de confirmation
    document.getElementById('confirm_password').addEventListener('input', function() {
        const newPassword = document.getElementById('new_password');
        if (this.value !== newPassword.value) {
            this.setCustomValidity('Les mots de passe ne correspondent pas.');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
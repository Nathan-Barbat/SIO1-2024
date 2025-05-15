<!-- views/auth/register.php -->
<?php
// Page d'inscription
$pageTitle = 'Inscription';
Auth::redirectIfLoggedIn(); // Redirection si déjà connecté
require_once 'views/layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Inscription</h3>
            </div>
            <div class="card-body">
                <form action="index.php?controller=user&action=register" method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                            <small class="form-text text-muted">Le mot de passe doit contenir au moins 6 caractères.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type de compte</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type_stagiaire" value="stagiaire" checked>
                            <label class="form-check-label" for="type_stagiaire">
                                Stagiaire - Je souhaite participer aux quiz
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="type_formateur" value="formateur">
                            <label class="form-check-label" for="type_formateur">
                                Formateur - Je souhaite créer des quiz
                            </label>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Vous avez déjà un compte ? <a href="index.php?controller=user&action=showLoginForm">Connectez-vous</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
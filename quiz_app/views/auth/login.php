<!-- views/auth/login.php -->
<?php
// Page de connexion
$pageTitle = 'Connexion';
Auth::redirectIfLoggedIn(); // Redirection si déjà connecté
require_once 'views/layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Connexion</h3>
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
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Vous n'avez pas de compte ? <a href="index.php?controller=user&action=showRegisterForm">Inscrivez-vous</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
<?php
require_once 'config.php';

// Instances des objets
$personnage = new Personnage($db);
$arme = new Arme($db);
$pouvoir = new Pouvoir($db);

// Variables pour le formulaire
$id = 0;
$nom = "";
$type = "";
$message = "";
$armes_selectionnees = [];
$pouvoirs_selectionnes = [];

// Récupérer les armes et pouvoirs disponibles
$armes_disponibles = $arme->getAll();
$pouvoirs_disponibles = $pouvoir->getAll();

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nom']) && isset($_POST['type'])) {
        $personnage->setNom($_POST['nom']);
        $personnage->setType($_POST['type']);
        
        // Mode édition
        if (isset($_POST['id']) && $_POST['id'] > 0) {
            $personnage->setId($_POST['id']);
            $personnage->update();
            $personnage->supprimerArmes();
            $personnage->supprimerPouvoirs();
            $message = "Personnage mis à jour avec succès!";
        } else {
            // Mode création
            $personnage->create();
            $message = "Personnage créé avec succès!";
        }
        
        // Ajout des armes
        if (isset($_POST['armes']) && is_array($_POST['armes'])) {
            foreach ($_POST['armes'] as $armeId) {
                $personnage->ajouterArme($armeId);
            }
        }
        
        // Ajout des pouvoirs
        if (isset($_POST['pouvoirs']) && is_array($_POST['pouvoirs'])) {
            foreach ($_POST['pouvoirs'] as $pouvoirId) {
                $personnage->ajouterPouvoir($pouvoirId);
            }
        }
    }
}

// Mode édition: charger les données d'un personnage existant
if (isset($_GET['edit']) && $_GET['edit'] > 0) {
    $id = $_GET['edit'];
    if ($personnage->getById($id)) {
        $nom = $personnage->getNom();
        $type = $personnage->getType();
        
        // Récupérer les armes du personnage
        $armes_perso = $personnage->getArmes();
        while ($arme_row = $armes_perso->fetch(PDO::FETCH_ASSOC)) {
            $armes_selectionnees[] = $arme_row['id'];
        }
        
        // Récupérer les pouvoirs du personnage
        $pouvoirs_perso = $personnage->getPouvoirs();
        while ($pouvoir_row = $pouvoirs_perso->fetch(PDO::FETCH_ASSOC)) {
            $pouvoirs_selectionnes[] = $pouvoir_row['id'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Personnages</title>
    <link rel="stylesheet" href="eval.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Personnages</h1>
        <nav>
            <ul>
                <li><a href="gestion_personnage.php">Créer un personnage</a></li>
                <li><a href="liste_personnages.php">Liste des personnages</a></li>
            </ul>
        </nav>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post" action="gestion_personnage.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="form-group">
                <label for="nom">Nom du personnage:</label>
                <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="type">Type de personnage:</label>
                <input type="text" id="type" name="type" value="<?php echo $type; ?>" required>
                <small>Exemples: Guerrier, Mage, Archer, etc.</small>
            </div>
            
            <div class="form-group">
                <label>Armes:</label>
                <div class="checkbox-group">
                    <?php while ($row = $armes_disponibles->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="checkbox-item">
                            <input type="checkbox" name="armes[]" value="<?php echo $row['id']; ?>" 
                                   id="arme_<?php echo $row['id']; ?>"
                                   <?php if (in_array($row['id'], $armes_selectionnees)) echo "checked"; ?>>
                            <label for="arme_<?php echo $row['id']; ?>">
                                <?php echo $row['nom']; ?> (<?php echo $row['type']; ?>)
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label>Pouvoirs:</label>
                <div class="checkbox-group">
                    <?php while ($row = $pouvoirs_disponibles->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="checkbox-item">
                            <input type="checkbox" name="pouvoirs[]" value="<?php echo $row['id']; ?>" 
                                   id="pouvoir_<?php echo $row['id']; ?>"
                                   <?php if (in_array($row['id'], $pouvoirs_selectionnes)) echo "checked"; ?>>
                            <label for="pouvoir_<?php echo $row['id']; ?>">
                                <?php echo $row['nom']; ?> (<?php echo $row['type']; ?>)
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit">
                    <?php echo ($id > 0) ? 'Modifier' : 'Créer'; ?> le personnage
                </button>
            </div>
        </form>
    </div>
</body>
</html>
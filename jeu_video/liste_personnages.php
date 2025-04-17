<?php
require_once 'config.php';

// Instance de Personnage
$personnage = new Personnage($db);

// Récupérer tous les personnages
$personnages = $personnage->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Personnages</title>
    <link rel="stylesheet" href="eval.css">
</head>
<body>
    <div class="container">
        <h1>Liste des Personnages</h1>
        <nav>
            <ul>
                <li><a href="gestion_personnage.php">Créer un personnage</a></li>
                <li><a href="liste_personnages.php">Liste des personnages</a></li>
            </ul>
        </nav>

        <div class="characters-list">
            <?php while ($row = $personnages->fetch(PDO::FETCH_ASSOC)): ?>
                <?php 
                    // Récupérer les informations du personnage
                    $perso = new Personnage($db);
                    $perso->getById($row['id']);
                    $armes = $perso->getArmes();
                    $pouvoirs = $perso->getPouvoirs();
                ?>
                <div class="character-card">
                    <h2><?php echo $row['nom']; ?></h2>
                    <p class="character-type">Type: <?php echo $row['type']; ?></p>
                    
                    <div class="character-details">
                        <div class="character-weapons">
                            <h3>Armes</h3>
                            <ul>
                                <?php while ($arme = $armes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <li>
                                        <span class="item-name"><?php echo $arme['nom']; ?></span>
                                        <span class="item-type">(<?php echo $arme['type']; ?>)</span>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                        
                        <div class="character-powers">
                            <h3>Pouvoirs</h3>
                            <ul>
                                <?php while ($pouvoir = $pouvoirs->fetch(PDO::FETCH_ASSOC)): ?>
                                    <li>
                                        <span class="item-name"><?php echo $pouvoir['nom']; ?></span>
                                        <span class="item-type">(<?php echo $pouvoir['type']; ?>)</span>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="character-actions">
                        <a href="gestion_personnage.php?edit=<?php echo $row['id']; ?>" class="btn-edit">Modifier</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
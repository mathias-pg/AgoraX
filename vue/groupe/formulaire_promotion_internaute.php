<?php
$roleActuel = $_GET['role'];
$nomRole = $_GET['roleConnecte'];
$roles = [
    'Administrateur' => 'Administrateur (Attention, vous deviendrez un simple membre)',
    'Membre' => 'Membre',
    'Moderateur' => 'Modérateur',
    'Assesseur' => 'Assesseur',
    'Scrutateur' => 'Scrutateur',
    'Decideur' => 'Décideur'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promouvoir un internaute</title>
    <link rel="stylesheet" href="./../../css/ajout_membre.css">
</head>
<body>
    <div class="container">
        <h1>Promouvoir un internaute</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>
        
        <form action="promotion_internaute.php" method="GET">
            <input type="hidden" name="NumGroupe" value="<?= $_GET['NumGroupe'] ?>">
            <input type="hidden" name="NumInternaute" value="<?= $_GET['NumInternaute'] ?>">
            <input type="hidden" name="roleConnecte" value="<?= $nomRole ?>">

            <label for="role">Nouveau Rôle :</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Choisir un rôle</option>
                <?php foreach ($roles as $valeur => $label): ?>
                    <?php if ($valeur != $roleActuel): ?>
                        <option value="<?= htmlspecialchars($valeur) ?>">
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>

            <div class="buttons">
                <input type="submit" value="Promouvoir">
            </div>
        </form>

        <a href="./../groupeDetails<?= $nomRole ?>.php?NumGroupe=<?= $_GET['NumGroupe'] ?>">Retour au groupe</a>
    </div>
</body>
</html>

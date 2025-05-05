<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_GET['NumVote'])) {
    header('Location: ./../index.php');
    exit;
}

$numVote = $_GET['NumVote'];
$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter des choix</title>
    <link rel="stylesheet" href="./../../css/ajout_membre.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter des choix pour le scrutin</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>

        <form action="ajouter_choix_vote.php" method="GET">
            <input type="hidden" name="NumVote" value="<?= $numVote ?>">
            <input type="hidden" name="NumGroupe" value="<?= $numGroupe ?>">
            <input type="hidden" name="role" value="<?= $nomRole ?>">

            <label for="choix1">Choix 1 :</label>
            <input type="text" id="choix1" name="choix1" required>

            <label for="choix2">Choix 2 :</label>
            <input type="text" id="choix2" name="choix2" required>

            <label for="choix3">Choix 3 :</label>
            <input type="text" id="choix3" name="choix3" required>

            <div class="buttons">
                <input type="submit" value="Valider">
            </div>
        </form>
    </div>
</body>
</html>

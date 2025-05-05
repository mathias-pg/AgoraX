<?php
$numProposition = $_GET['NumProposition'] ?? null;
$numCommentaire = $_GET['NumCommentaire'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaler une proposition ou un commmentaire</title>
    <link rel="stylesheet" href="./../../css/ajout_membre.css">
</head>
<body>
    <div class="container">
        <h1>Signaler une proposition ou un commentaire</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>
        
        <form action="signaler_proposition_ou_commentaire.php" method="GET">
            <input type="hidden" name="NumGroupe" value="<?= $_GET['NumGroupe'] ?>">
            <input type="hidden" name="role" value="<?= $_GET['role'] ?>">
            <input type="hidden" name="NumProposition" value="<?= $numProposition ?>">
            <input type="hidden" name="NumCommentaire" value="<?= $numCommentaire ?>">

            <label for="motif">Motif :</label>
            <input type="text" id="motif" name="motif" placeholder="Entrez le motif du signalement" required>

            <div class="buttons">
                <input type="submit" value="Envoyer le signalement">
            </div>
        </form>

        <a href="./../groupeDetails<?= $_GET['role'] ?>.php?NumGroupe=<?= $_GET['NumGroupe'] ?>">Retour au groupe</a>
    </div>
</body>
</html>
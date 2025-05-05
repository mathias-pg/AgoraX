<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Groupe - AgoraX</title>
    <link rel="stylesheet" href="../css/creation_groupe.css">
</head>
<body>
    <div class="container">
        <h2>Ajouter une image a un groupe</h2>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>

        <form class="group-form" action="ajouter_image.php" method="POST" enctype="multipart/form-data">

            <label for="num" class="instructions">Numero Groupe</label>
            <input type="number" id="num" name="num"/>

            <label for="image" class="instructions">Ajoutez une image pour votre groupe :</label>
            <input type="file" id="image" name="image" accept="image/*">

            <div class="buttons">
                <input type="submit" value="Envoyer les données">
            </div>
        </form>

    </div>
</body>
</html>

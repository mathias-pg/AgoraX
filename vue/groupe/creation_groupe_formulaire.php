<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Groupe - AgoraX</title>
    <link rel="stylesheet" href="../../css/creation_groupe.css">
    <script src="./../js/liste_themes.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Créer un nouveau groupe</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>

        <?php
            include("creation_goupe_formulaire.html");
        ?>

        <a href="../accueil.php">Retour à l'accueil</a>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - AgoraX</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <img src="../../image/logoAgoraX.png" alt="Logo AgoraX" class="logo">
        <h1>Création de compte</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>

        <form action="creation_compte.php" method="GET">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="email" name="email" placeholder="Adresse e-mail" value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '' ?>" <?= isset($_GET['email']) ? 'readonly' : '' ?> required>
            <input type="text" name="adresse" placeholder="Adresse Postal" required>
            <input type="password" name="motDePasse" placeholder="Mot de passe" required>
            <input type="password" name="motDePasse_confirm" placeholder="Confirmer le mot de passe" required>
            
            <?php if (isset($_GET['numGroupe']) && isset($_GET['role'])): ?>
                <input type="hidden" name="numGroupe" value="<?= htmlspecialchars($_GET['numGroupe']) ?>">
                <input type="hidden" name="role" value="<?= htmlspecialchars($_GET['role']) ?>">
            <?php endif; ?>

            <input type="submit" value="Créer mon compte">
        </form>
        <p>Déjà un compte ? <a href="./../../index.php">Se connecter</a></p>
    </div>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./../../index.php');
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification du Compte - AgoraX</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <img src="../../image/logoAgoraX.png" alt="Logo AgoraX" class="logo">
        <h1>Modifier votre compte</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>

        <form class="account-form" action="modification_compte.php" method="GET">
            <input type="text" name="nom" placeholder="Nom" value="<?= htmlspecialchars($user['NomInternaute']) ?>" required>
            <input type="text" name="prenom" placeholder="Prénom" value="<?= htmlspecialchars($user['PrenomInternaute']) ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($user['AdresseMail']) ?>" required>
            <input type="text" name="adresse" placeholder="Adresse Postal" value="<?= htmlspecialchars($user['AdressePostal']) ?>" required>
            <input type="password" name="motDePasse" placeholder="Mot de passe" value="<?= htmlspecialchars($user['MotDePasse']) ?>" required>

            <div class="buttons">
                <input type="submit" name="action" value="Modifier le compte">
                <input type="submit" name="action" value="Supprimer le compte" class="delete-button">
            </div>
        </form>

        <a href="../accueil.php">Retour à l'accueil</a>
    </div>
</body>
</html>
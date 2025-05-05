<?php
require_once("./controleur/ControleurInternaute.php");
session_start();

$messageErreur = null;

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['email'], $_GET['motDePasse'])) {
    $email = $_GET['email'];
    $password = $_GET['motDePasse'];

    $internaute = ControleurInternaute::testerConnexion($email, $password);

    if ($internaute) {
        $_SESSION['user'] = [
            'NumInternaute' => $internaute->get('NumInternaute'),
            'NomInternaute' => $internaute->get('NomInternaute'),
            'PrenomInternaute' => $internaute->get('PrenomInternaute'),
            'MotDePasse' => $internaute->get('MotDePasse'),
            'AdresseMail' => $internaute->get('AdresseMail'),
            'AdressePostal' => $internaute->get('AdressePostal'),
        ];
        header('Location: vue/accueil.php');
        exit;
    } else {
        $messageErreur = "Connexion impossible. Vérifiez vos identifiants.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container">
        <img src="./image/logoAgoraX.png" alt="Logo AgoraX" class="logo">
        <h1>Connexion</h1>

        <?php if (!empty($messageErreur)): ?>
            <p style="color: red;"><?= htmlspecialchars($messageErreur) ?></p>
        <?php endif; ?>

        <form action="index.php" method="GET">
            <input type="email" name="email" placeholder="Adresse e-mail" required>
            <input type="password" name="motDePasse" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
        </form>
        <p>Pas encore de compte ? <a href="./vue/compte/creation_compte_formulaire.php">Créer un compte</a></p>
    </div>
</body>
</html>
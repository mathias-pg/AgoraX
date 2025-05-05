<?php
require_once("./../controleur/ControleurGroupe.php");
session_start();

$email = $_GET['email'] ?? null;
$numGroupe = $_GET['numGroupe'] ?? null;
$role = $_GET['role'] ?? null;
$action = $_GET['action'] ?? null;

$groupe = ControleurGroupe::getObjetById($numGroupe);


if ($action == 'accepter') {
    $result = ControleurGroupe::ajouterInternauteAuGroupe($email, $numGroupe, $role);
    header("Location: ./../index.php");
    exit;
} elseif ($action == 'refuser') {
    header("Location: ./../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation au Groupe</title>
    <link rel="stylesheet" href="./../css/accepter_invitation.css">
</head>
<body>
    <div class="container">
        <img src="./../image/logoAgoraX.png" alt="Logo AgoraX" class="logo">
        <h1>Invitation à rejoindre le groupe <?= $groupe->get('NomGroupe')?> en tant que <?= $role ?></h1>

        <div class="buttons">
            <a href="?email=<?= urlencode($email) ?>&numGroupe=<?= urlencode($numGroupe) ?>&role=<?= urlencode($role) ?>&action=accepter" class="btn-accept">Accepter</a>
            <a href="?email=<?= urlencode($email) ?>&numGroupe=<?= urlencode($numGroupe) ?>&role=<?= urlencode($role) ?>&action=refuser" class="btn-decline">Refuser</a>
        </div>
    </div>
</body>
</html>

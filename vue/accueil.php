<?php
require_once("../controleur/ControleurGroupe.php");
require_once("../controleur/ControleurImage.php"); 
require_once("../controleur/ControleurInternaute.php"); 
require_once("../controleur/ControleurRole.php");
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./../index.php');
    exit;
}

$numInternaute = $_SESSION['user']['NumInternaute'];
$groupes = ControleurGroupe::getGroupesParInternaute($numInternaute);
$internaute = ControleurInternaute::getObjetById($numInternaute);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="../css/accueil.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Bienvenue, <?= htmlspecialchars($_SESSION['user']['PrenomInternaute']) ?> <?= htmlspecialchars($_SESSION['user']['NomInternaute']) ?> !</h1>
        </div>
        <div class="header-right">
            <a href="./compte/modification_compte_formulaire.php" class="profile-btn">
                <img src="../image/profil.png" alt="Profil">
            </a>
            <a href="./notification/affichage_notifications.php" class="notification-btn">
                <img src="../image/notification.png" alt="Notifications">
            </a>
        </div>
    </header>

    <div class="content">
        <div class="button-container">
            <h2>Vos groupes :</h2>
            <a href="./groupe/creation_groupe_formulaire.php" class="create-group-btn">Créer un Groupe</a>
        </div>
        <ul class="group-list">
            <?php foreach ($groupes as $groupe):
                $image = ControleurImage::getImageGroupe($groupe->get('NumGroupe')); 
                $imageUrl = $image ? 'data:' . $image->get('TypeImage') . ';base64,' . base64_encode($image->get('Bin')) : '';
                $role = ControleurRole::getRoleParInternauteEtGroupe($internaute->get('NumInternaute'), $groupe->get('NumGroupe'));
            ?>
                <li class="group-item" style="background-image: url('<?= $imageUrl ?>');">
                    <a href="groupeDetails<?= $role->get('NomRole') ?>.php?NumGroupe=<?= htmlspecialchars($groupe->get('NumGroupe')) ?>">
                        <?= htmlspecialchars($groupe->get('NomGroupe')) ?><br>
                        <span><?= htmlspecialchars($groupe->get('DescriptionGroupe')) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="./compte/deconnexion.php" class="logout-btn">Se déconnecter</a>
    </div>
</body>
</html>

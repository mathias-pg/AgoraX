<?php
require_once("./../../controleur/ControleurInternaute.php");
require_once("./../../controleur/ControleurSignalement.php");
require_once("./../../controleur/ControleurGroupe.php");
require_once("./../../controleur/ControleurCommentaire.php");
require_once("./../../controleur/ControleurProposition.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe'])) {
    header('Location: ./../index.php');
    exit;
}

$numInternaute = $_GET['NumInternaute'];
$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];
$groupe = ControleurGroupe::getGroupe($numGroupe);
$internaute = ControleurInternaute::getObjetById($numInternaute);
$nomGroupe = $groupe->get('NomGroupe');
$couleur = $groupe->get('CouleurGroupe');

$signalementPropositions = ControleurSignalement::getSignalementsPropositionsGroupe($numInternaute, $numGroupe);
$signalementCommentaires = ControleurSignalement::getSignalementsCommentairesGroupe($numInternaute, $numGroupe);

?>  

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails sur l'internaute</title>
    <link rel="stylesheet" href="./../../css/infosInternaute.css">
</head>
<body>
    <header style="background-color: <?= htmlspecialchars($couleur) ?>;">
        <a href="./../groupeDetails<?= $nomRole ?>.php?NumGroupe=<?= $numGroupe ?>" class="back-btn">&larr; Retour au groupe</a>
        <h1 class="group-name"><?= htmlspecialchars($nomGroupe) ?></h1>
    </header>

    <div class="main-content-internaute">
        <div class="panel">
            <div class="internaute-card">
                <img src="./../../image/profil.png" alt="Photo de profil">
                <h2><?= htmlspecialchars($internaute->get('PrenomInternaute')) ?> <?= htmlspecialchars($internaute->get('NomInternaute')) ?></h2>
                <p><strong>Email :</strong> <?= htmlspecialchars($internaute->get('AdresseMail')) ?></p>
                <p><strong>Adresse Postal :</strong> <?= htmlspecialchars($internaute->get('AdressePostal')) ?></p>
                <p><strong>Inscrit depuis :</strong> <?= htmlspecialchars($internaute->get('DateInscription')) ?></p>
            </div>
        </div>
    </div>
</body>
</html>

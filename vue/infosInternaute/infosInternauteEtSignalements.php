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

    <div class="main-content">
        <div class="left-panel">
            <div class="internaute-card">
                <img src="./../../image/profil.png" alt="Photo de profil">
                <h2><?= htmlspecialchars($internaute->get('PrenomInternaute')) ?> <?= htmlspecialchars($internaute->get('NomInternaute')) ?></h2>
                <p><strong>Email :</strong> <?= htmlspecialchars($internaute->get('AdresseMail')) ?></p>
                <p><strong>Adresse Postal :</strong> <?= htmlspecialchars($internaute->get('AdressePostal')) ?></p>
                <p><strong>Inscrit depuis :</strong> <?= htmlspecialchars($internaute->get('DateInscription')) ?></p>
            </div>
        </div>

        <div class="right-panel">
            <div class="signalement-container">
                <h2>Signalements</h2>

                <div class="signalement-section">
                    <h3>Propositions Signalées</h3>
                    <?php foreach ($signalementPropositions as $signalementProposition): ?>
                        <?php
                            $proposition = ControleurProposition::getObjetById($signalementProposition->get('NumProposition'))
                        ?>
                        <div class="signalement-card">
                            <h4><strong>Titre proposition : </strong><?= htmlspecialchars($proposition->get('TitreProposition')) ?></h4>
                            <p><strong>Description proposition : </strong><?= htmlspecialchars($proposition->get('DescriptionProposition')) ?></p>
                            <p><strong>Motif : </strong><?= htmlspecialchars($signalementProposition->get('Motif')) ?></p>
                            <span>Date : <?= htmlspecialchars($signalementProposition->get('DateSignalement')) ?></span>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($signalementPropositions)): ?>
                        <p>Aucune proposition signalée.</p>
                    <?php endif; ?>
                </div>

                <div class="signalement-section">
                    <h3>Commentaires Signalés</h3>
                    <?php foreach ($signalementCommentaires as $signalementCommentaire): ?>
                        <?php
                            $commentaire = ControleurCommentaire::getObjetById($signalementCommentaire->get('NumCommentaire'))
                        ?>
                        <div class="signalement-card">
                            <p><strong>Commentaire : </strong><?= htmlspecialchars($commentaire->get('Contenu')) ?></p>
                            <p><strong>Motif : </strong><?= htmlspecialchars($signalementCommentaire->get('Motif')) ?></p>
                            <span>Date : <?= htmlspecialchars($signalementCommentaire->get('DateSignalement')) ?></span>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($signalementCommentaires)): ?>
                        <p>Aucun commentaire signalé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

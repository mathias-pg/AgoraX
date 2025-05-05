<?php
require_once("./../../controleur/ControleurVote.php");
require_once("./../../controleur/ControleurInternaute.php");
require_once("./../../controleur/ControleurGroupe.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumVote'])) {
    header('Location: ./../index.php');
    exit;
}

$numVote = $_GET['NumVote'];
$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];


$vote = ControleurVote::getObjetById($numVote);
$typeScrutin = ControleurVote::getTypeScrutin($vote->get('NumTypeScrutin'));
$groupe = ControleurGroupe::getObjetById($numGroupe);
$nomGroupe = $groupe->get('NomGroupe');
$couleur = $groupe->get('CouleurGroupe');

$emojis = ['🔥', '😡', '👎', '😒' , '👍', '😊'];

if ($typeScrutin->get('NomTypeScrutin') == 'majoritaire_liste') {
    $votesSoumis = ControleurVote::getVotesSoumisNotation($numVote);
} else {
    $votesSoumis = ControleurVote::getVotesSoumis($numVote);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails sur les votes</title>
    <link rel="stylesheet" href="./../../css/infosInternaute.css">
</head>
<body>
    <header style="background-color: <?= htmlspecialchars($couleur) ?>;">
        <a href="./../groupeDetails<?= $nomRole ?>.php?NumGroupe=<?= $numGroupe ?>" class="back-btn">&larr; Retour au groupe</a>
        <h1 class="group-name"><?= htmlspecialchars($nomGroupe) ?></h1>
    </header>

    <div class="main-content-vote">
        <h2>Résumé du Vote</h2>
        <p><strong>Date de Début : </strong> <?= htmlspecialchars($vote->get('DateDebutVote')) ?></p>
        <p><strong>Durée : </strong> <?= htmlspecialchars($vote->get('DureeVote')) ?> jours</p>

        <h2>Votes Soumis</h2>
        <ul class="votes-list">
            <?php if (count($votesSoumis) > 0): ?>
                 <?php foreach ($votesSoumis as $voteSoumis): ?>
                    <?php $internaute = ControleurInternaute::getObjetById($voteSoumis->get('NumInternaute')); ?>
                    <li class="vote-item">
                        <div class="vote-header">
                            <strong><?= htmlspecialchars($internaute->get('PrenomInternaute') . " " . $internaute->get('NomInternaute')) ?></strong>
                        </div>
                        <div class="vote-details">
                            <?php if ($typeScrutin->get('NomTypeScrutin') == 'majoritaire_liste'): ?>
                                <p>Notation Choix 1 : <?= $emojis[$voteSoumis->get('NotationChoix1')] ?></p>
                                <p>Notation Choix 2 : <?= $emojis[$voteSoumis->get('NotationChoix2')] ?></p>
                                <p>Notation Choix 3 : <?= $emojis[$voteSoumis->get('NotationChoix3')] ?></p>
                            <?php else: ?>
                                <p>Choix : <?= $voteSoumis->get('Choix') ?></p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun vote soumis pour ce vote.</p>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>

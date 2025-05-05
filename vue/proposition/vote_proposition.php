<?php
require_once("./../../controleur/ControleurProposition.php");
require_once("./../../controleur/ControleurVote.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumProposition'])) {
    header('Location: ./../../index.php');
    exit;
}

$numInternaute = $_SESSION['user']['NumInternaute'];
$numProposition = $_GET['NumProposition'];
$nomRole = $_GET['role'];
$result = ControleurProposition::votePourOuPasProposition($numInternaute, $numProposition);

if ($result) {
    ControleurProposition::retirerVotePourProposition($numInternaute, $numProposition);
} else {
    ControleurProposition::mettreVotePourProposition($numInternaute, $numProposition);

    $pourcentageVotes = ControleurProposition::getPourcentageVotes($numProposition, $_GET['NumGroupe']);

    if($pourcentageVotes >= 50){
        $reS = ControleurVote::verifierSiVoteProposition($numProposition);
        if(!$res){
            ControleurVote::ajouterNouveauVote('1 mois', 'pour/contre', $_GET['NumGroupe'], $numProposition);
        }
    }
}

header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $_GET['NumGroupe']);
exit;
?>

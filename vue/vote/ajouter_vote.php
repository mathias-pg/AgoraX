<?php
require_once("./../../controleur/ControleurVote.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe'])) {
    header('Location: ./../index.php');
    exit;
}

$numGroupe = $_GET['NumGroupe'];
$numProposition = $_GET['NumProposition'];
$duree = $_GET['duree'];
$typeVote = $_GET['vote'];
$nomRole = $_GET['role'];
$numInternaute = $_SESSION['user']['NumInternaute'];

$result = ControleurVote::verifierSiVoteProposition($numProposition);

if($result){
    $erreur = "Le vote ne peut pas être créé. Cette proposition a déjà un vote en cours ou alors a déjà été voté.";
    header("Location: formulaire_ajouter_vote.php?NumProposition=$numProposition&NumGroupe=$numGroupe&role=$nomRole&erreur=" . urlencode($erreur));
    exit;
}
else{
    $numVote = ControleurVote::ajouterNouveauVote($duree, $typeVote, $numGroupe, $numProposition);

    if($numVote){
        if ($typeVote == "oui/non" || $typeVote == "pour/contre") {
            header("Location: ./../groupeDetails$nomRole.php?NumGroupe=$numGroupe");
            exit;
        } else {
            header("Location: formulaire_choix_vote.php?NumVote=$numVote&NumGroupe=$numGroupe&role=$nomRole");
            exit;
        }
    }
        
    else{
        $erreur = "Erreur lors de la création du vote.";
        header("Location: formulaire_ajout_vote.php?NumProposition=$numProposition&NumGroupe=$numGroupe&role=$nomRole&erreur=" . urlencode($erreur));
        exit;
    }   
}



?>

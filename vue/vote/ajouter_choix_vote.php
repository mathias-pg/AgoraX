<?php
require_once("./../../controleur/ControleurVote.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumVote'])) {
    header('Location: ./../index.php');
    exit;
}

$numVote = $_GET['NumVote'];
$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];
$choix1 = $_GET['choix1'];
$choix2 = $_GET['choix2'];
$choix3 = $_GET['choix3'];

$result = ControleurVote::ajouterNouveauVoteMajoritaire($choix1, $choix2, $choix3, $numVote);

if($result){
    header("Location: ./../groupeDetails$nomRole.php?NumGroupe=$numGroupe");
    exit;
}
    
else{
    $erreur = "Erreur lors de la création du vote.";
    header("Location: formulaire_choix_vote.php?NumVote=$numVote&NumGroupe=$numGroupe&role=$role&erreur=" . urlencode($erreur));
    exit;
} 
?>

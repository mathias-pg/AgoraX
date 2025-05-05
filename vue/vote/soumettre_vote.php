<?php
require_once("../../controleur/ControleurVote.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumVote']) || !isset($_GET['typeScrutin'])) {
    header('Location: ./../index.php');
    exit;
}

$numVote = $_GET['NumVote'];
$typeScrutin = $_GET['typeScrutin'];
$numInternaute = $_SESSION['user']['NumInternaute'];
$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];



if ($typeScrutin == 'oui/non' || $typeScrutin == 'pour/contre' || $typeScrutin == 'majoritaire_simple') {
    $choix = $_GET['choix'];
    $result = ControleurVote::soumettreVote($numVote, $numInternaute, $choix);
        
} 
elseif ($typeScrutin == 'majoritaire_liste') {
    $notation1 = $_GET['notation1'];
    $notation2 = $_GET['notation2'];
    $notation3 = $_GET['notation3'];
    $result = ControleurVote::soumettreVoteNotation($numVote, $numInternaute, $notation1, $notation2, $notation3);
} 

header("Location: ./../groupeDetails$nomRole.php?NumGroupe=$numGroupe");
exit;


?>

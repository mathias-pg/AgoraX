<?php
require_once("../../controleur/ControleurVote.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumVote'])) {
    header('Location: ./../index.php');
    exit;
}

$numVote = $_GET['NumVote'];
$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];

$result = ControleurVote::cloturerVote($numVote);
header("Location: ./../groupeDetails$nomRole.php?NumGroupe=$numGroupe");
exit;

?>

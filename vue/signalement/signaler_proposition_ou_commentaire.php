<?php
require_once("./../../controleur/ControleurSignalement.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe'])) {
    header('Location: ./../../index.php');
    exit;
}

$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];
$numProposition = $_GET['NumProposition'] ?? null;
$numCommentaire = $_GET['NumCommentaire'] ?? null;
$motif = $_GET['motif'];
$numInternaute = $_SESSION['user']['NumInternaute'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($numProposition && !$numCommentaire) {
        $resultat = ControleurSignalement::ajouterSignalementProposition($numProposition, $numInternaute, $motif);
    }
    elseif ($numProposition && $numCommentaire) {
        $resultat = ControleurSignalement::ajouterSignalementCommentaire($numCommentaire, $numProposition, $numInternaute, $motif);
    } 

    header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . urlencode($numGroupe));
    exit;
}
?>
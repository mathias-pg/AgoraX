<?php
require_once("./../../controleur/ControleurProposition.php");
require_once("./../../controleur/ControleurTheme.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe'])) {
    header('Location: ./../../index.php');
    exit;
}

$numInternaute = $_SESSION['user']['NumInternaute'];
$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $titre = $_GET['titre'] ?? null;
    $description = $_GET['description'] ?? null;
    $cout = $_GET['cout'] ?? null;
    $numTheme = $_GET['NumTheme'] ?? null;

    $propositionCree = ControleurProposition::creerProposition($titre, $description, $cout, $numTheme, $numGroupe, $numInternaute);

    if ($propositionCree) {
        header("Location: ./../groupeDetails$nomRole.php?NumGroupe=$numGroupe");
        exit;
    } else {
        $messageErreur = "Erreur lors de la création de la proposition.";
        header("Location: ./creation_proposition_formulaire.php?NumGroupe=" . urlencode($numGroupe) . "&erreur=" . urlencode($messageErreur) .  "&role=" . urlencode($nomRole));
        exit;
    }
}
?>

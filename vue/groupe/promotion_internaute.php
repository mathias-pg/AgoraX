<?php
require_once("./../../controleur/ControleurInternaute.php");
require_once("./../../controleur/ControleurRole.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe']) || !isset($_GET['role'])) {
    header('Location: ./../../index.php');
    exit;
}

$numGroupe = $_GET['NumGroupe'];
$nouveauRole = $_GET['role'];
$numInternaute = $_GET['NumInternaute'];
$numAdmin = $_SESSION['user']['NumInternaute'];
$r = ControleurRole::getRoleParInternauteEtGroupe($numInternaute, $numGroupe);
$ancienRole = $r->get('NomRole');
$nomRole = $_GET['roleConnecte'];

if ($nouveauRole == 'Administrateur') {
    ControleurRole::mettreAJourRole($numInternaute, $numGroupe, $nouveauRole);
    ControleurRole::mettreAJourRole($numAdmin, $numGroupe, 'Membre');
    header("Location: ./../groupeDetailsMembre.php?NumGroupe=" . urlencode($numGroupe));
    exit;
} 
else {
    ControleurRole::mettreAJourRole($numInternaute, $numGroupe, $nouveauRole);
    header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . urlencode($numGroupe));
    exit;
}


?>

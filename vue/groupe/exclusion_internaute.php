<?php
require_once("./../../controleur/ControleurGroupe.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe']) || !isset($_GET['NumInternaute'])) {
    header('Location: ./../../index.php');
    exit;
}

$numGroupe = $_GET['NumGroupe'];
$numInternaute = $_GET['NumInternaute'];
$nomRole = $_GET['role'];

try {
    $result = ControleurGroupe::enleverInternauteGroupe($numGroupe, $numInternaute);
    header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $numGroupe);
    exit;
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

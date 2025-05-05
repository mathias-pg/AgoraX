<?php
require_once("./../../controleur/ControleurProposition.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumProposition'])) {
    header('Location: ./../../index.php');
    exit;
}

$numProposition = $_GET['NumProposition'];
$nomRole = $_GET['role'];

try {
    $result = ControleurProposition::enleverProposition($numProposition);
    header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $_GET['NumGroupe']);
    exit;
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

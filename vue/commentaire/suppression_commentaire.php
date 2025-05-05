<?php
require_once("./../../controleur/ControleurCommentaire.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumCommentaire'])) {
    header('Location: ./../../index.php');
    exit;
}

$numCommentaire = $_GET['NumCommentaire'];
$nomRole = $_GET['role'];

try {
    $result = ControleurCommentaire::enleverCommentaire($numCommentaire);
    header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $_GET['NumGroupe']);
    exit;
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

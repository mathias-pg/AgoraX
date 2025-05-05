<?php
require_once("./../../controleur/ControleurProposition.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumProposition'])) {
    header('Location: ./../../index.php');
    exit;
}

$numInternaute = $_SESSION['user']['NumInternaute'];
$numProposition = $_GET['NumProposition'];
$nomRole = $_GET['role'];
$result = ControleurProposition::likeOuPasProposition($numInternaute, $numProposition);

if ($result) {
    ControleurProposition::retirerLikeProposition($numInternaute, $numProposition);
} else {
    $dislike = ControleurProposition::dislikeOuPasProposition($numInternaute, $numProposition);
    if($dislike){
        ControleurProposition::retirerDislikeProposition($numInternaute, $numProposition);
    }
    ControleurProposition::mettreLikeProposition($numInternaute, $numProposition);
}

header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $_GET['NumGroupe']);
exit;
?>

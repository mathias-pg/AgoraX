<?php
require_once("./../../controleur/ControleurCommentaire.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumCommentaire'])) {
    header('Location: ./../../index.php');
    exit;
}

$numInternaute = $_SESSION['user']['NumInternaute'];
$numCommentaire = $_GET['NumCommentaire'];
$nomRole = $_GET['role'];
$result = ControleurCommentaire::likeOuPasCommentaire($numInternaute, $numCommentaire);

if ($result) {
    ControleurCommentaire::retirerLikeCommentaire($numInternaute, $numCommentaire);
} else {
    $dislike = ControleurCommentaire::dislikeOuPasCommentaire($numInternaute, $numCommentaire);
    if($dislike){
        ControleurCommentaire::retirerDislikeCommentaire($numInternaute, $numCommentaire);
    }
    ControleurCommentaire::mettreLikeCommentaire($numInternaute, $numCommentaire);
}

header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $_GET['NumGroupe']);
exit;
?>

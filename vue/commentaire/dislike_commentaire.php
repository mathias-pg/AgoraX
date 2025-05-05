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
$result = ControleurCommentaire::dislikeOuPasCommentaire($numInternaute, $numCommentaire);

if ($result) {
    ControleurCommentaire::retirerDislikeCommentaire($numInternaute, $numCommentaire);
} else {
    $like = ControleurCommentaire::likeOuPasCommentaire($numInternaute, $numCommentaire);
    if($like){
        ControleurCommentaire::retirerLikeCommentaire($numInternaute, $numCommentaire);
    }
    ControleurCommentaire::mettreDislikeCommentaire($numInternaute, $numCommentaire);
}

header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $_GET['NumGroupe']);
exit;
?>

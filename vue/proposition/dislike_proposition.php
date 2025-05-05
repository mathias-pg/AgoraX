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
$result = ControleurProposition::dislikeOuPasProposition($numInternaute, $numProposition);

if ($result) {
    ControleurProposition::retirerDislikeProposition($numInternaute, $numProposition);
} else {
    $like = ControleurProposition::likeOuPasProposition($numInternaute, $numProposition);
    if($like){
        ControleurProposition::retirerLikeProposition($numInternaute, $numProposition);
    }
    ControleurProposition::mettreDislikeProposition($numInternaute, $numProposition);
}


header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $_GET['NumGroupe']);
exit;

?>


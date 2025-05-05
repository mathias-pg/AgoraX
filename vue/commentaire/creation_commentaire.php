
<?php
require_once("./../../controleur/ControleurCommentaire.php");
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $numProposition = $_GET['NumProposition'];
    $contenuCommentaire = $_GET['commentaire'];
    $numInternaute = $_SESSION['user']['NumInternaute'];
    $contenuCommentaire = trim($contenuCommentaire);
    $nomRole = $_GET['role'];

    $resultat = ControleurCommentaire::ajouterCommentaire($numProposition, $numInternaute, $contenuCommentaire);

    header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . urlencode($_GET['NumGroupe']));
    exit;

}
?>
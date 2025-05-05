<?php
require_once("./../../controleur/ControleurInternaute.php");

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./../../index.php');
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? null;
    $nom = $_GET['nom'] ?? null;
    $prenom = $_GET['prenom'] ?? null;
    $email = $_GET['email'] ?? null;
    $adresse = $_GET['adresse'] ?? null;
    $motDePasse = $_GET['motDePasse'] ?? null;

    $messageErreur = null;

    if ($action === "Modifier le compte") {
        $isUpdated = ControleurInternaute::modifierCompte($user['NumInternaute'], $nom, $prenom, $email, $adresse, $motDePasse);

        if ($isUpdated) {
            $_SESSION['user'] = [
                'NumInternaute' => $user['NumInternaute'],
                'NomInternaute' => $nom,
                'PrenomInternaute' => $prenom,
                'AdresseMail' => $email,
                'AdressePostal' => $adresse,
                'MotDePasse' => $motDePasse,];
            header("Location: ./../accueil.php");
            exit;
        } 
        else {
            $messageErreur = "Erreur lors de la modification du compte.";
            header("Location: modification_compte_formulaire.php?erreur=" . urlencode($messageErreur));
            exit;
        }
    } 
    elseif ($action === "Supprimer le compte") {
        $isDeleted = ControleurInternaute::supprimerCompte($user['NumInternaute']);

        if ($isDeleted) {
            session_destroy(); 
            header("Location: ./../../index.php");
            exit;
        } else {
            $messageErreur = "Erreur lors de la suppression du compte.";
            header("Location: modification_compte_formulaire.php?erreur=" . urlencode($messageErreur));
            exit;
        }
    }

}
?>

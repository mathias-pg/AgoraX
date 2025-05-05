<?php
require_once("./../../controleur/ControleurInternaute.php");
require_once("./../../controleur/ControleurInvitation.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe'])) {
    header('Location: ./../../index.php');
    exit;
}

$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['roleConnecte'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $email = $_GET['email'];
    $role = $_GET['role'];

    $utilisateur = ControleurInternaute::rechercherParEmail($email);

    if (!$utilisateur) {
        // Si l'utilisateur n'existe pas, générer une invitation pour créer un compte
        $lienInscription = ControleurInvitation::genererLienInscription($email, $numGroupe, $role);

        $envoye = ControleurInvitation::envoyerEmailInvitation($email, $lienInscription);
        if ($envoye) {
            header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $numGroupe);
            exit;
        } 
        else {
            $messageErreur = "Échec de l'envoi de l'invitation par e-mail.";
            header("Location: ./formulaire_ajout_membre.php?NumGroupe=" . $numGroupe . "&erreur=" . urlencode($messageErreur) . "&role=" . urlencode($nomRole));
            exit;
        }
    } else {
        // Si l'utilisateur existe, générer un lien direct pour rejoindre le groupe
        $lienInvitation = ControleurInvitation::genererLienInvitation($email, $numGroupe, $role);

        $envoye = ControleurInvitation::envoyerEmailInvitation($email, $lienInvitation);

        if ($envoye) {
            header("Location: ./../groupeDetails$nomRole.php?NumGroupe=" . $numGroupe);
            exit;
        } 
        else {
            $messageErreur = "Échec de l'envoi de l'invitation par e-mail.";
            header("Location: ./formulaire_ajout_membre.php?NumGroupe=" . $numGroupe . "&erreur=" . urlencode($messageErreur) . "&role=" . urlencode($nomRole));
            exit;
        }
    }
}
?>

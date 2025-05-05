<?php
require_once("./../../controleur/ControleurInternaute.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $nom = $_GET['nom'] ?? null;
    $prenom = $_GET['prenom'] ?? null;
    $email = $_GET['email'] ?? null;
    $adresse = $_GET['adresse'] ?? null;
    $motDePasse = $_GET['motDePasse'] ?? null;
    $motDePasseConfirm = $_GET['motDePasse_confirm'] ?? null;
    $numGroupe = $_GET['numGroupe'] ?? null;
    $role = $_GET['role'] ?? null;

    if ($motDePasse !== $motDePasseConfirm) {
        $messageErreur = "Les mots de passe ne correspondent pas.";
        header("Location: ./creation_compte_formulaire.php?erreur=" . urlencode($messageErreur)."&email=" . urlencode($email) . "&numGroupe=" . urlencode($numGroupe) . "&role=" . urlencode($role));
        exit;
    } else {
        $isCreated = ControleurInternaute::creerCompte($nom, $prenom, $email, $adresse, $motDePasse);
        if ($isCreated) {
            if ($numGroupe && $role) {
                $redirectUrl = "./../accepter_invitation.php?email=" . urlencode($email) . "&numGroupe=" . urlencode($numGroupe) . "&role=" . urlencode($role);
                header("Location: $redirectUrl");
                exit;
            } else {
                header("Location: ./../../index.php");
                exit;
            }
        } 
        else {
            $messageErreur = "Erreur lors de la création du compte. L'email est peut-être déjà utilisé.";
            header("Location: ./creation_compte_formulaire.php?erreur=" . urlencode($messageErreur)."&email=" . urlencode($email) . "&numGroupe=" . urlencode($numGroupe) . "&role=" . urlencode($role));
            exit;
        }
    }
}
?>
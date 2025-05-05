<?php
require_once("./../../controleur/ControleurGroupe.php");
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./../index.php');
    exit;
}

$numInternaute = $_SESSION['user']['NumInternaute'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? null;
    $description = $_POST['description'] ?? null;
    $couleur = $_POST['couleur'] ?? null;
    $themesJson = $_POST['themes'] ?? '[]';
    $montant = $_POST['budget'] ?? null;
    $themes = json_decode($themesJson, true);


    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $messageErreur = "Erreur lors du téléchargement de l'image.";
        header("Location: ./creation_groupe_formulaire.php?erreur=" . urlencode($messageErreur));
        exit;
    }

    $imageTempPath = $_FILES['image']['tmp_name'];
    $imageName = $_FILES['image']['name'];
    $imageSize = $_FILES['image']['size'];
    $imageType = $_FILES['image']['type'];
    $imageBin = file_get_contents($imageTempPath);


    
    $numGroupe = ControleurGroupe::creerGroupe($nom, $description, $couleur, $themes, $montant, $numInternaute);

    if (!$numGroupe) {
        $messageErreur = "Erreur lors de la création du groupe.";
        header("Location: ./creation_groupe_formulaire.php?erreur=" . urlencode($messageErreur));
        exit;
    }

    $imageInseree = ControleurGroupe::ajouterImage($numGroupe, $imageName, $imageSize, $imageType, $imageBin);

    if ($imageInseree) {
        header("Location: ./../accueil.php");
        exit;
    } else {
        $messageErreur = "Erreur lors de l'ajout de l'image.";
        header("Location: ./creation_groupe_formulaire.php?erreur=" . urlencode($messageErreur));
        exit;
    }
}
?>

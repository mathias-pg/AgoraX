<?php
require_once("./../controleur/ControleurGroupe.php");
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./../index.php');
    exit;
}

$numInternaute = $_SESSION['user']['NumInternaute'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $num = $_POST['num'] ?? null;


    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $messageErreur = "Erreur lors du téléchargement de l'image.";
        header("Location: ./formulaire_ajouter_image.php?erreur=" . urlencode($messageErreur));
        exit;
    }

    $imageTempPath = $_FILES['image']['tmp_name'];
    $imageName = $_FILES['image']['name'];
    $imageSize = $_FILES['image']['size'];
    $imageType = $_FILES['image']['type'];
    $imageBin = file_get_contents($imageTempPath);

    $imageInseree = ControleurGroupe::ajouterImage($num, $imageName, $imageSize, $imageType, $imageBin);

    if ($imageInseree) {
        header("Location: ./formulaire_ajouter_image.php");
        exit;
    } else {
        $messageErreur = "Erreur lors de l'ajout de l'image.";
        header("Location: ./formulaire_ajouter_image.php?erreur=" . urlencode($messageErreur));
        exit;
    }
}
?>

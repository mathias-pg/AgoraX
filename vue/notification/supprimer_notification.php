<?php
require_once("./../../controleur/ControleurNotification.php");
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./../../index.php');
    exit;
}

$numNotification = $_GET['NumNotification'];
$numInternaute = $_SESSION['user']['NumInternaute'];

    
$result = ControleurNotification::supprimerNotification($numNotification, $numInternaute);
header('Location: ./affichage_notifications.php');
exit;
?>

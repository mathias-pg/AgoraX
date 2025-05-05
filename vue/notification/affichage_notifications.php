<?php
require_once("./../../controleur/ControleurNotification.php");
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./../../index.php');
    exit;
}

$numInternaute = $_SESSION['user']['NumInternaute'];
$notifications = ControleurNotification::getNotificationsParInternaute($numInternaute);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="./../../css/affichage_notifications.css">
</head>
<body>
    <header>
        <a href="./../accueil.php" class="back-btn">&larr; Retour à l'accueil</a>
        <h1 class="page-title">Notifications</h1>
        <div class="header-right">
            <a href="./parametres_notification.html" class="parametre-btn">
                <img src="./../../image/parametres.png" alt="Paramètres des notifications">
            </a>
        </div>
    </header>

    <div class="content">
        <?php if (empty($notifications)): ?>
            <p class="no-notification">Vous n'avez aucune notification pour le moment.</p>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-card">
                    <div class="notif-content">
                        <h3 class="notif-title">🔔 Nouvelle notification</h3>
                        <p class="notif-text"><?= htmlspecialchars($notification->get('TypeNotification')) ?></p>
                    </div>
                    <div class="notif-details">
                        <span class="notif-date"><?= htmlspecialchars($notification->get('DateNotification')) ?></span>
                        <form action="supprimer_notification.php" method="GET">
                            <input type="hidden" name="NumNotification" value="<?= $notification->get('NumNotification') ?>">
                            <button type="submit" class="mark-read">
                                ✔️
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>


<?php
require_once(__DIR__ ."/../modele/notification.php");

class ControleurNotification {

    public static function getNotificationsParInternaute($numInternaute){
        $tabN = notification::notificationsParInternuate($numInternaute);
        return $tabN;
    }

    public static function supprimerNotification($numNotification, $numInternaute){
        $res = notification::enleverNotification($numNotification, $numInternaute);
        return $res;
    }

    public static function getObjetById($numNotification){
        $n = notification::getObjetById($numNotification);
        return $n;
    }

    public static function getAll(){
        $tabN = notification::getAll();
        return $tabN;
    }
}
?>

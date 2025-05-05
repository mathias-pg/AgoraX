<?php
require_once(__DIR__ ."/../modele/theme.php");

class ControleurTheme {

    public static function getThemesParGroupe($numGroupe){
        $tabT = theme::themesParGroupe($numGroupe);
        return $tabT;
    }

    public static function getObjetById($numTheme){
        $t = theme::getObjetById($numTheme);
        return $t;
    }

    public static function getAll(){
        $tabT = theme::getAll();
        return $tabT;
    }
}
?>
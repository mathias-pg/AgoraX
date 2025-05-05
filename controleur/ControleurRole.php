<?php
require_once(__DIR__ ."/../modele/role.php");

class ControleurRole {

    public static function getRoleParInternauteEtGroupe($numInternaute, $numGroupe){
        $r = role::roleParInternauteEtGroupe($numInternaute, $numGroupe);
        return $r;
    }

    public static function mettreAJourRole($numInternaute, $numGroupe, $nouveauRole){
        $res = role::mettreAJourRole($numInternaute, $numGroupe, $nouveauRole);
        return $res;
    }

    public static function getObjetById($numRole){
        $r = role::getObjetById($numRole);
        return $r;
    }

    public static function getAll(){
        $tabR = role::getAll();
        return $tabR;
    }
}
?>
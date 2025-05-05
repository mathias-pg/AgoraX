<?php
require_once(__DIR__ ."/../modele/groupe.php");

class ControleurGroupe {

    public static function getGroupesParInternaute($numInternaute){
        $tabG = groupe::groupesParInternaute($numInternaute);
        return $tabG;
    }

    public static function getGroupe($numGroupe){
        $g = groupe::getObjetById($numGroupe);
        return $g;
    }

    public static function creerGroupe($nom, $description, $couleur, $themes, $montant, $numInternaute){
        $res = groupe::nouveauGroupe($nom, $description, $couleur, $themes, $montant, $numInternaute);
        return $res;
    }

    public static function ajouterImage($numGroupe, $nomImage, $tailleImage, $typeImage, $binImage){
        $res = groupe::nouvelleImage($numGroupe, $nomImage, $tailleImage, $typeImage, $binImage);
        return $res;
    }
    
    public static function enleverInternauteGroupe($numGroupe, $numInternaute){
        $res = groupe::retirerInternauteGroupe($numGroupe, $numInternaute);
        return $res;
    }

    public static function ajouterInternauteAuGroupe($email, $numGroupe, $role){
        $res = groupe::nouvelInternauteGroupe($email, $numGroupe, $role);
        return $res;
    }

    public static function getObjetById($numGroupe){
        $g = groupe::getObjetById($numGroupe);
        return $g;
    }

    public static function getAll(){
        $tabG = groupe::getAll();
        return $tabG;
    }
}
?>

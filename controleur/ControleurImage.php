<?php
require_once(__DIR__ ."/../modele/image.php");

class ControleurImage {

    public static function getImageGroupe($numGroupe){
        $i = image::imageGroupe($numGroupe);
        return $i;
    }

    public static function getObjetById($numImage){
        $i = image::getObjetById($numImage);
        return $i;
    }

    public static function getAll(){
        $tabI = image::getAll();
        return $tabI;
    }
}
?>

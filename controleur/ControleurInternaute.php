<?php
require_once(__DIR__ . "/../modele/internaute.php");


class ControleurInternaute {

    public static function testerConnexion($email, $mdp){
        $i = internaute::verifierConnexion($email, $mdp);
        return $i;
    }

    public static function getMembresDuGroupe($numGroupe){
        $tabI = internaute::membresDuGroupe($numGroupe);
        return $tabI;
    }

    public static function getAuteurProposition($numProposition){
        $i = internaute::auteurProposition($numProposition);
        return $i;
    }

    public static function rechercherParEmail($email){
        $i = internaute::internauteEmail($email);
        return $i;
    }

    public static function creerCompte($nom, $prenom, $email, $adresse, $motDePasse){
        $res = internaute::nouvelInternaute($nom, $prenom, $email, $adresse, $motDePasse);
        return $res;
    }

    public static function modifierCompte($num, $nom, $prenom, $email, $adresse, $motDePasse){
        $res = internaute::modifierInternaute($num, $nom, $prenom, $email, $adresse, $motDePasse);
        return $res;
    }

    public static function supprimerCompte($num){
        $res = internaute::supprimerInternaute($num);
        return $res;
    }

    public static function getAuteurCommentaire($numCommentaire){
        $i = internaute::auteurCommentaire($numCommentaire);
        return $i;
    }

    public static function getObjetById($numInternaute){
        $i = internaute::getObjetById($numInternaute);
        return $i;
    }

    public static function getAll(){
        $tabI = internaute::getAll();
        return $tabI;
    }
    
}
?>
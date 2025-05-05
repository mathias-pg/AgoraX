<?php
require_once(__DIR__ ."/../modele/proposition.php");

class ControleurProposition {

    public static function getPropositionsParGroupe($numGroupe){
        $tabP = proposition::propositionsParGroupe($numGroupe);
        return $tabP;
    }

    public static function creerProposition($titre, $description, $cout, $numTheme, $numGroupe, $numInternaute){
        $p = proposition::nouvelleProposition($titre, $description, $cout, $numTheme, $numGroupe, $numInternaute);
        return $p;
    }
    
    public static function enleverProposition($num){
        $res = proposition::supprimerProposition($num);
        return $res;
    }

    public static function getPropositionsParUnInternaute($numInternaute){
        $tabP = proposition::propositionsParUnInternaute($numInternaute);
        return $tabP;
    }

    public static function getPropositionsParUnInternauteEtGroupe($numInternaute, $numGroupe){
        $tabP = proposition::propositionsParUnInternauteEtGroupe($numInternaute, $numGroupe);
        return $tabP;
    }

    public static function likeOuPasProposition($numInternaute, $numProposition){
        $res = proposition::likeOuPasProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function dislikeOuPasProposition($numInternaute, $numProposition){
        $res = proposition::dislikeOuPasProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function votePourOuPasProposition($numInternaute, $numProposition){
        $res = proposition::votePourOuPasProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function retirerLikeProposition($numInternaute, $numProposition){
        $res = proposition::retirerLikeProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function retirerDislikeProposition($numInternaute, $numProposition){
        $res = proposition::retirerDislikeProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function retirerVotePourProposition($numInternaute, $numProposition){
        $res = proposition::retirerVotePourProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function mettreLikeProposition($numInternaute, $numProposition){
        $res = proposition::mettreLikeProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function mettreDislikeProposition($numInternaute, $numProposition){
        $res = proposition::mettreDislikeProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function mettreVotePourProposition($numInternaute, $numProposition){
        $res = proposition::mettreVotePourProposition($numInternaute, $numProposition);
        return $res;
    }

    public static function getNombreLikes($numProposition){
        $res = proposition::getNombreLikes($numProposition);
        return $res;
    }

    public static function getNombreDislikes($numProposition){
        $res = proposition::getNombreDislikes($numProposition);
        return $res;
    }

    public static function getPourcentageVotes($numProposition, $numGroupe){
        $res = proposition::getPourcentageVotes($numProposition, $numGroupe);
        return $res;
    }

    public static function getNombreCommentaires($numProposition){
        $res = proposition::getNombreCommentaires($numProposition);
        return $res;
    }
    
    public static function getObjetById($numProposition){
        $p = proposition::getObjetById($numProposition);
        return $p;
    }

    public static function getAll(){
        $tabP = proposition::getAll();
        return $tabP;
    }

    
}
?>
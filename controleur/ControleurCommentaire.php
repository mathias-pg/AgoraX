<?php
require_once(__DIR__ ."/../modele/commentaire.php");

class ControleurCommentaire {

    public static function getCommentairesParProposition($numProposition){
        $tabC = commentaire::commentairesParProposition($numProposition);
        return $tabC;
    }

    public static function ajouterCommentaire($numProposition, $numInternaute, $contenuCommentaire){
        $res = commentaire::nouveauCommentaire($numProposition, $numInternaute, $contenuCommentaire);
        return $res;
    }
    
    public static function enleverCommentaire($num){
        $res = commentaire::supprimerCommentaire($num);
        return $res;
    }

    public static function getCommentairesParUnInternaute($numInternaute){
        $tabC = commentaire::commentairesParUnInternaute($numInternaute);
        return $tabC;
    }

    public static function getCommentairesParUnInternauteEtGroupe($numInternaute, $numGroupe){
        $tabC = commentaire::commentairesParUnInternauteEtGroupe($numInternaute, $numGroupe);
        return $tabC;
    }

    public static function likeOuPasCommentaire($numInternaute, $numCommentaire){
        $res = commentaire::likeOuPasCommentaire($numInternaute, $numCommentaire);
        return $res;
    }

    public static function dislikeOuPasCommentaire($numInternaute, $numCommentaire){
        $res = commentaire::dislikeOuPasCommentaire($numInternaute, $numCommentaire);
        return $res;
    }

    public static function retirerLikeCommentaire($numInternaute, $numCommentaire){
        $res = commentaire::retirerLikeCommentaire($numInternaute, $numCommentaire);
        return $res;
    }

    public static function retirerDislikeCommentaire($numInternaute, $numCommentaire){
        $res = commentaire::retirerDislikeCommentaire($numInternaute, $numCommentaire);
        return $res;
    }

    public static function mettreLikeCommentaire($numInternaute, $numCommentaire){
        $res = commentaire::mettreLikeCommentaire($numInternaute, $numCommentaire);
        return $res;
    }

    public static function mettreDislikeCommentaire($numInternaute, $numCommentaire){
        $res = commentaire::mettreDislikeCommentaire($numInternaute, $numCommentaire);
        return $res;
    }

    public static function getNombreLikes($numCommentaire){
        $res = commentaire::getNombreLikes($numCommentaire);
        return $res;
    }

    public static function getNombreDislikes($numCommentaire){
        $res = commentaire::getNombreDislikes($numCommentaire);
        return $res;
    }

    public static function getObjetById($numCommentaire){
        $c = commentaire::getObjetById($numCommentaire);
        return $c;
    }

    public static function getAll(){
        $tabC = commentaire::getAll();
        return $tabC;
    }

    
}
?>
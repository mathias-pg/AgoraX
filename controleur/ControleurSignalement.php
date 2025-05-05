<?php
require_once(__DIR__ ."/../modele/signalementProposition.php");
require_once(__DIR__ ."/../modele/signalementCommentaire.php");

class ControleurSignalement {

    public static function ajouterSignalementProposition($numProposition, $numInternaute, $motif){
        $res = signalementProposition::signalerProposition($numProposition, $numInternaute, $motif);
        return $res;
    }

    public static function ajouterSignalementCommentaire($numCommentaire, $numProposition, $numInternaute, $motif){
        $res = signalementCommentaire::signalerCommentaire($numCommentaire, $numProposition, $numInternaute, $motif);
        return $res;
    }

    public static function getSignalementsCommentairesGroupe($numInternaute, $numGroupe){
        $tabS = signalementCommentaire::signalementsCommentairesGroupe($numInternaute, $numGroupe);
        return $tabS;
    }

    public static function getSignalementsPropositionsGroupe($numInternaute, $numGroupe){
        $tabS = signalementProposition::signalementsPropositionsGroupe($numInternaute, $numGroupe);
        return $tabS;
    }
}
?>
<?php
require_once(__DIR__ ."/../modele/vote.php");
require_once(__DIR__ ."/../modele/voteSoumis.php");
require_once(__DIR__ ."/../modele/voteSoumisNotation.php");
require_once(__DIR__ ."/../modele/voteMajoritaire.php");
require_once(__DIR__ ."/../modele/typeScrutin.php");

class ControleurVote {

    public static function ajouterNouveauVote($duree, $typeVote, $numGroupe, $numProposition){
        $res = vote::nouveauVote($duree, $typeVote, $numGroupe, $numProposition);
        return $res;
    }

    public static function ajouterNouveauVoteMajoritaire($choix1, $choix2, $choix3, $numVote){
        $res = voteMajoritaire::nouveauVoteMajoritaire($choix1, $choix2, $choix3, $numVote);
        return $res;
    }

    public static function getVotesParGroupe($numGroupe){
        $tabV = vote::votesParGroupe($numGroupe);
        return $tabV;
    }

    public static function getTypeScrutin($numTypeScrutin){
        $t = typeScrutin::getObjetById($numTypeScrutin);
        return $t;
    }

    public static function verifierSiVoteDejaSoumis($numInternaute, $numVote){
        $res = vote::verifierSiVoteDejaSoumis($numInternaute, $numVote);
        return $res;
    }

    public static function getVoteMajoritaire($numVote){
        $v = voteMajoritaire::getVoteMajoritaire($numVote);
        return $v;
    }

    public static function verifierSiVoteProposition($numProposition){
        $res = vote::verifierSiVoteProposition($numProposition);
        return $res;
    }

    public static function soumettreVote($numVote, $numInternaute, $choix){
        $res = voteSoumis::soumettreVote($numVote, $numInternaute, $choix);
        return $res;
    }

    public static function soumettreVoteNotation($numVote, $numInternaute, $notation1, $notation2, $notation3){
        $res = voteSoumisNotation::soumettreVoteNotation($numVote, $numInternaute, $notation1, $notation2, $notation3);
        return $res;
    }

    public static function cloturerVote($numVote){
        $res = vote::cloturerVote($numVote);
        return $res;
    }

    public static function resultatVoteOuiNon($numVote){
        $res = voteSoumis::resultatVoteOuiNon($numVote);
        return $res;
    }

    public static function resultatVotePourContre($numVote){
        $res = voteSoumis::resultatVotePourContre($numVote);
        return $res;
    }

    public static function resultatVoteMajoritaireSimple($numVote){
        $res = voteSoumis::resultatVoteMajoritaireSimple($numVote);
        return $res;
    }

    public static function resultatVoteMajoritaireJugement($numVote){
        $res = voteSoumisNotation::resultatVoteMajoritaireJugement($numVote);
        return $res;
    }

    public static function getVotesSoumisNotation($numVote){
        $tabV = voteSoumisNotation::getVotesSoumisNotation($numVote);
        return $tabV;
    }

    public static function getVotesSoumis($numVote){
        $tabV = voteSoumis::getVotesSoumis($numVote);
        return $tabV;
    }

    public static function getObjetById($numVote){
        $v = vote::getObjetById($numVote);
        return $v;
    }

    public static function getAll(){
        $tabV = vote::getAll();
        return $tabV;
    }
}
?>
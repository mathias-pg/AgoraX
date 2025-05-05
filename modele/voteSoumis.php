<?php
require_once("modele.php");

class VoteSoumis extends Modele {
  public static $objet = "VoteSoumis";
  public static $cle = "NumVoteSoumis";
  public int $NumVoteSoumis;
  public string $Choix;
  public string $DateVote;
  public int $NumInternaute;
  public int $NumVote;

  public static function soumettreVote($numVote, $numInternaute, $choix){
    try {
      $stmtNum = Connexion::pdo()->query("SELECT IFNULL(MAX(NumVoteSoumis), 0) + 1 AS nextNum FROM VoteSoumis");
      $result = $stmtNum->fetch();
      $nextNum = $result['nextNum'];

      $requete = "INSERT INTO VoteSoumis VALUES (:num, :choix, SYSDATE(), :numInternaute, :numVote)";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['num' => $nextNum, 'choix' => $choix, 'numInternaute' => $numInternaute, 'numVote' => $numVote]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function resultatVoteOuiNon($numVote){
    $pdo = Connexion::pdo();
  
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumis WHERE Choix = 'oui' AND NumVote = :numVote");
    $stmt->execute(['numVote' => $numVote]);
    $result1 = $stmt->fetch();
    $nbOui = $result1['total'];
  
    $stmt2 = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumis WHERE Choix = 'non' AND NumVote = :numVote");
    $stmt2->execute(['numVote' => $numVote]);
    $result2 = $stmt2->fetch();
    $nbNon = $result2['total'];
  
    if ($nbOui > $nbNon) {
      return 'Le gagnant est le choix suivant : oui';
    }
    elseif($nbNon > $nbOui){
      return 'Le gagnant est le choix suivant : non';
    }
    else{
      return 'Égalité entre le choix oui et le choix non';
    }
  }

  public static function resultatVotePourContre($numVote){
    $pdo = Connexion::pdo();
  
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumis WHERE Choix = 'pour' AND NumVote = :numVote");
    $stmt->execute(['numVote' => $numVote]);
    $result1 = $stmt->fetch();
    $nbPour = $result1['total'];
  
    $stmt2 = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumis WHERE Choix = 'contre' AND NumVote = :numVote");
    $stmt2->execute(['numVote' => $numVote]);
    $result2 = $stmt2->fetch();
    $nbContre = $result2['total'];
  
    if ($nbPour > $nbContre) {
      return 'Le gagnant est le choix suivant : pour';
    }
    elseif($nbContre > $nbPour){
      return 'Le gagnant est le choix suivant : contre';
    }
    else{
      return 'Égalité entre le choix pour et le choix contre';
    }
  }

  public static function resultatVoteMajoritaireSimple($numVote){
    $pdo = Connexion::pdo();

    $stmt = $pdo->prepare("SELECT Choix1, Choix2, Choix3 FROM VoteMajoritaire WHERE NumVote = :numVote");
    $stmt->execute(['numVote' => $numVote]);
    $result1 = $stmt->fetch();
    $choix1 = $result1['Choix1'];
    $choix2 = $result1['Choix2'];
    $choix3 = $result1['Choix3'];
  
    $stmt2 = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumis WHERE Choix = :choix1 AND NumVote = :numVote");
    $stmt2->execute(['choix1' => $choix1, 'numVote' => $numVote]);
    $result2 = $stmt2->fetch();
    $nbChoix1 = $result2['total'];
  
    $stmt3 = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumis WHERE Choix = :choix2 AND NumVote = :numVote");
    $stmt3->execute(['choix2' => $choix2, 'numVote' => $numVote]);
    $result3 = $stmt3->fetch();
    $nbChoix2 = $result3['total'];

    $stmt4 = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumis WHERE Choix = :choix3 AND NumVote = :numVote");
    $stmt4->execute(['choix3' => $choix3, 'numVote' => $numVote]);
    $result4 = $stmt4->fetch();
    $nbChoix3 = $result4['total'];

    $votes = [$choix1 => $nbChoix1, $choix2 => $nbChoix2, $choix3 => $nbChoix3,];
    arsort($votes);  
    $keys = array_keys($votes);
    $values = array_values($votes);

    if ($values[0] == $values[1] && $values[0] == $values[2]) {
        return "Égalité parfaite entre les trois choix : " . $keys[0] . " | " . $keys[1] . " | " . $keys[3];
    } 
    elseif ($values[0] == $values[1]) {
        return "Égalité entre les deux choix suivants : " . $keys[0] . " | " . $keys[1];
    } 
    else {
        return "Le gagnant est le choix  suivant : " . $keys[0];  
    }
  
  }

  public static function getVotesSoumis($numVote){
    $requete = "SELECT * FROM VoteSoumis
                WHERE NumVote = :numVote";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numVote' => $numVote]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, VoteSoumis::class);
    return $stmt->fetchAll(); 
  }

}
?>

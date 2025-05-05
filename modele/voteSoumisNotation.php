<?php
require_once("modele.php");

class VoteSoumisNotation extends Modele {
  public static $objet = "VoteSoumisNotation";
  public static $cle = "NumVoteSoumisNotation";
  public int $NumVoteSoumisNotation;
  public string $DateVote;
  public int $NotationChoix1;
  public int $NotationChoix2;
  public int $NotationChoix3;
  public int $NumInternaute;
  public int $NumVote;

  public static function soumettreVoteNotation($numVote, $numInternaute, $notation1, $notation2, $notation3){
    try {
      $stmtNum = Connexion::pdo()->query("SELECT IFNULL(MAX(NumVoteSoumisNotation), 0) + 1 AS nextNum FROM VoteSoumisNotation");
      $result = $stmtNum->fetch();
      $nextNum = $result['nextNum'];

      $requete = "INSERT INTO VoteSoumisNotation VALUES (:num, SYSDATE(), :notation1, :notation2, :notation3, :numInternaute, :numVote)";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['num' => $nextNum, 'notation1' => $notation1, 'notation2' => $notation2, 'notation3' => $notation3,'numInternaute' => $numInternaute, 'numVote' => $numVote]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function resultatVoteMajoritaireJugement($numVote){
    $pdo = Connexion::pdo();

    $stmt = $pdo->prepare("SELECT Choix1, Choix2, Choix3 FROM VoteMajoritaire WHERE NumVote = :numVote");
    $stmt->execute(['numVote' => $numVote]);
    $result1 = $stmt->fetch();
    $choix1 = $result1['Choix1'];
    $choix2 = $result1['Choix2'];
    $choix3 = $result1['Choix3'];
  
    $stmt2 = $pdo->prepare("SELECT SUM(NotationChoix1) AS total FROM VoteSoumisNotation WHERE NumVote = :numVote");
    $stmt2->execute(['numVote' => $numVote]);
    $result2 = $stmt2->fetch();
    $nbChoix1 = $result2['total'];
  
    $stmt3 = $pdo->prepare("SELECT SUM(NotationChoix2) AS total FROM VoteSoumisNotation WHERE NumVote = :numVote");
    $stmt3->execute(['numVote' => $numVote]);
    $result3 = $stmt3->fetch();
    $nbChoix2 = $result3['total'];

    $stmt4 = $pdo->prepare("SELECT SUM(NotationChoix3) AS total FROM VoteSoumisNotation WHERE NumVote = :numVote");
    $stmt4->execute(['numVote' => $numVote]);
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

  public static function getVotesSoumisNotation($numVote){
    $requete = "SELECT * FROM VoteSoumisNotation
                WHERE NumVote = :numVote";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numVote' => $numVote]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, VoteSoumisNotation::class);
    return $stmt->fetchAll(); 
  }
}
?>
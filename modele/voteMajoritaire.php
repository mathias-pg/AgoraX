<?php
require_once("modele.php");

class VoteMajoritaire extends Modele {
  public static $objet = "VoteMajoritaire";
  public static $cle = "NumVoteMajoritaire";
  public int $NumVoteMajoritaire;
  public string $Choix1;
  public string $Choix2;
  public string $Choix3;
  public int $NumVote;

  public static function nouveauVoteMajoritaire($choix1, $choix2, $choix3, $numVote){
    try {
        $pdo = Connexion::pdo();
  
        $stmtNum = $pdo->query("SELECT IFNULL(MAX(NumVoteMajoritaire), 0) + 1 AS nextNum FROM VoteMajoritaire");
        $result = $stmtNum->fetch();
        $numVoteMajoritaire = $result['nextNum'];
  
        $requeteInsert = "INSERT INTO VoteMajoritaire VALUES (:num, :choix1, :choix2, :choix3, :numVote)";
        $stmt = $pdo->prepare($requeteInsert);
        $stmt->execute(['num' => $numVoteMajoritaire, 'choix1' => $choix1, 'choix2' => $choix2, 'choix3' => $choix3, 'numVote' => $numVote]);
        return true;
      } catch (Exception $e) {
        echo "Erreur: " . $e->getMessage();
        return false; 
      }
  }

  public static function getVoteMajoritaire($numVote){
    $requete = "SELECT * FROM VoteMajoritaire
                WHERE NumVote = :numVote";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numVote' => $numVote]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, VoteMajoritaire::class);
    return $stmt->fetch(); 
  }

}

?>
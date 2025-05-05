<?php
require_once("modele.php");

class SignalementCommentaire extends Modele {
  public static $objet = "Signalement";
  public static $cle = "NumSignalement";
  public int $NumSignalement;
  public string $Motif;
  public string $DateSignalement;
  public string $StatutSignalement;
  public int $NumInternaute;
  public int $NumCommentaire;
  public int $NumProposition;

  public static function signalerCommentaire($numCommentaire, $numProposition, $numInternaute, $motif){
    try {
      $stmt = Connexion::pdo()->query("SELECT IFNULL(MAX(NumSignalement), 0) + 1 AS nextNum FROM Signalement");
      $result = $stmt->fetch();
      $nextNum = $result['nextNum'];

      $requete = "INSERT INTO Signalement VALUES (:num, :motif, SYSDATE(), 'En attente', :numInternaute, :numCommentaire, :numProposition)";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['num' => $nextNum, 'motif' => $motif, 'numInternaute' => $numInternaute, 'numCommentaire' => $numCommentaire, 'numProposition' => $numProposition]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }


  public static function signalementsCommentairesGroupe($numInternaute, $numGroupe){
    $requete = "SELECT * FROM Signalement S
                INNER JOIN Commentaire C
                ON S.NumCommentaire = C.NumCommentaire
                INNER JOIN Proposition P 
                ON C.NumProposition = P.NumProposition
                WHERE C.NumInternaute = :numInternaute
                AND P.NumGroupe = :numGroupe";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, SignalementCommentaire::class);
    return $stmt->fetchAll(); 
  }
}
?>

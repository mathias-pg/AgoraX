<?php
require_once("modele.php");

class SignalementProposition extends Modele {
  public static $objet = "Signalement";
  public static $cle = "NumSignalement";
  public int $NumSignalement;
  public string $Motif;
  public string $DateSignalement;
  public string $StatutSignalement;
  public int $NumInternaute;
  public int $NumProposition;

  public static function signalerProposition($numProposition, $numInternaute, $motif) {
    try {
      $stmt = Connexion::pdo()->query("SELECT IFNULL(MAX(NumSignalement), 0) + 1 AS nextNum FROM Signalement");
      $result = $stmt->fetch();
      $nextNum = $result['nextNum'];

      $requete = "INSERT INTO Signalement VALUES (:num, :motif, SYSDATE(), 'En attente', :numInternaute, NULL, :numProposition)";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['num' => $nextNum, 'motif' => $motif, 'numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function signalementsPropositionsGroupe($numInternaute, $numGroupe){
    $requete = "SELECT NumSignalement, Motif, DateSignalement, StatutSignalement, S.NumInternaute, S.NumProposition FROM Signalement S
                INNER JOIN Proposition P 
                ON S.NumProposition = P.NumProposition
                WHERE P.NumInternaute = :numInternaute
                AND P.NumGroupe = :numGroupe
                AND S.NumCommentaire IS NULL";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, SignalementProposition::class);
    return $stmt->fetchAll(); 
  }
}
?>

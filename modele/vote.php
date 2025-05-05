<?php
require_once("modele.php");

class Vote extends Modele {
  public static $objet = "Vote";
  public static $cle = "NumVote";
  public int $NumVote;
  public string $DureeVote;
  public string $DateDebutVote;
  public int $EnCours;
  public int $NumGroupe;
  public int $NumProposition;
  public int $NumTypeScrutin;

  public static function nouveauVote($duree, $typeVote, $numGroupe, $numProposition){
    try {
      $pdo = Connexion::pdo();

      $stmtNumVote = $pdo->query("SELECT IFNULL(MAX(NumVote), 0) + 1 AS nextNum FROM Vote");
      $result1 = $stmtNumVote->fetch();
      $numVote = $result1['nextNum'];

      $stmtNumTypeScrutin = $pdo->prepare("SELECT NumTypeScrutin FROM TypeScrutin WHERE NomTypeScrutin = :nomTypeScrutin");
      $stmtNumTypeScrutin->execute(['nomTypeScrutin' => $typeVote]);
      $result2 = $stmtNumTypeScrutin->fetch();
      $numTypeScrutin = $result2['NumTypeScrutin'];

      $requeteInsert = "INSERT INTO Vote VALUES (:num, :duree, SYSDATE(), 1, :numGroupe, :numProposition, :numTypeScrutin)";
      $stmt = $pdo->prepare($requeteInsert);
      $stmt->execute(['num' => $numVote, 'duree' => $duree, 'numGroupe' => $numGroupe, 'numProposition' => $numProposition, 'numTypeScrutin' => $numTypeScrutin]);

      return $numVote;
    } catch (Exception $e) {
      return 0; 
    }
  }

  public static function votesParGroupe($numGroupe){
    $requete = "SELECT * FROM Vote
                WHERE NumGroupe = :numGroupe";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Vote::class);
    return $stmt->fetchAll(); 
  }

  public static function verifierSiVoteDejaSoumis($numInternaute, $numVote){
    $pdo = Connexion::pdo();

    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumis WHERE NumInternaute = :numInternaute AND NumVote = :numVote");
    $stmt->execute(['numInternaute' => $numInternaute, 'numVote' => $numVote]);
    $result1 = $stmt->fetch();

    $stmt2 = $pdo->prepare("SELECT COUNT(*) AS total FROM VoteSoumisNotation WHERE NumInternaute = :numInternaute AND NumVote = :numVote");
    $stmt2->execute(['numInternaute' => $numInternaute, 'numVote' => $numVote]);
    $result2 = $stmt2->fetch();

    if ($result1['total'] > 0 || $result2['total'] > 0) {
      return true;
    }
    else {
      return false;
    }
    
  }

  public static function verifierSiVoteProposition($numProposition){
    $pdo = Connexion::pdo();

    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM Vote WHERE NumProposition = :numProposition");
    $stmt->execute(['numProposition' => $numProposition]);
    $result1 = $stmt->fetch();

    if ($result1['total'] > 0) {
      return true;
    }
    else {
      return false;
    }
    
  }

  public static function cloturerVote($numVote){
    try {
      $requete = "UPDATE Vote SET EnCours = 0 WHERE NumVote = :numVote";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numVote' => $numVote]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

}
?>

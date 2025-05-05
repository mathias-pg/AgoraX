<?php
require_once("modele.php");

class Proposition extends Modele {
  public static $objet = "Proposition";
  public static $cle = "NumProposition";
  public int $NumProposition;
  public string $TitreProposition;
  public string $DescriptionProposition;
  public string $DateSoumission;
  public string $DureeDiscussion;
  public string $EtatProposition;
  public int $CoutProposition;
  public int $NumTheme;
  public int $NumGroupe;
  public int $NumInternaute;


  public static function propositionsParGroupe($numGroupe) {
    $requete = "SELECT * FROM Proposition WHERE NumGroupe = :numGroupe";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Proposition::class);
    return $stmt->fetchAll();
    
  }

  public static function nouvelleProposition($titre, $description, $cout, $numTheme, $numGroupe, $numInternaute) {
    try {
        $pdo = Connexion::pdo();
        $pdo->beginTransaction();

        $stmtNum = $pdo->query("SELECT IFNULL(MAX(NumProposition), 0) + 1 AS nextNum FROM Proposition");
        $result = $stmtNum->fetch();
        $nextNumProposition = $result['nextNum'];

        $requeteProposition = "INSERT INTO Proposition VALUES (:num, :titre, :description, SYSDATE(), '1 mois', 'Acceptee', :cout, :numTheme, :numGroupe, :numInternaute)";
        $stmtProposition = $pdo->prepare($requeteProposition);
        $stmtProposition->execute(['num' => $nextNumProposition, 'titre' => $titre, 'description' => $description, 'cout' => $cout, 'numTheme' => $numTheme, 'numGroupe' => $numGroupe, 'numInternaute' => $numInternaute]);

        $requeteLikeDislike = "INSERT INTO LikeDislikeProposition (NumInternaute, NumProposition, AimeProposition, AimePasProposition) 
                               SELECT NumInternaute, :numProposition, 0, 0 
                               FROM Adherent 
                               WHERE NumGroupe = :numGroupe";
        $stmtLikeDislike = $pdo->prepare($requeteLikeDislike);
        $stmtLikeDislike->execute(['numProposition' => $nextNumProposition, 'numGroupe' => $numGroupe]);

        $requeteVotePour = "INSERT INTO VotePour (NumInternaute, NumProposition, Vote) 
                            SELECT NumInternaute, :numProposition, 0 
                            FROM Adherent 
                            WHERE NumGroupe = :numGroupe";
        $stmtVotePour = $pdo->prepare($requeteVotePour);
        $stmtVotePour->execute(['numProposition' => $nextNumProposition, 'numGroupe' => $numGroupe]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur: " . $e->getMessage();
        return false;
    }
  }

  public static function propositionsParUnInternaute($numInternaute){
    $requete = "SELECT * FROM Proposition
                WHERE NumInternaute = :numInternaute";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Proposition::class);
    return $stmt->fetchAll(); 
  }

  public static function propositionsParUnInternauteEtGroupe($numInternaute, $numGroupe){
    $requete = "SELECT * FROM Proposition
                WHERE NumInternaute = :numInternaute
                AND NumGroupe = :numGroupe";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numGroupe'=> $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Proposition::class);
    return $stmt->fetchAll(); 
  }

  public static function supprimerProposition($num){
    try {
      $pdo = Connexion::pdo(); 
      $pdo->beginTransaction();

      $stmtVotePour = $pdo->prepare("DELETE FROM VotePour WHERE NumProposition = :num");
      $stmtVotePour->execute(['num' => $num]);

      $stmtLikeDislike = $pdo->prepare("DELETE FROM LikeDislikeProposition WHERE NumProposition = :num");
      $stmtLikeDislike->execute(['num' => $num]);

      $stmtVoteSoumis = $pdo->prepare("DELETE FROM VoteSoumis WHERE NumVote IN (SELECT NumVote FROM Vote WHERE NumProposition = :num)");
      $stmtVoteSoumis->execute(['num' => $num]);

      $stmtVoteSoumisNotation = $pdo->prepare("DELETE FROM VoteSoumisNotation WHERE NumVote IN (SELECT NumVote FROM Vote WHERE NumProposition = :num)");
      $stmtVoteSoumisNotation->execute(['num' => $num]);

      $stmtVoteMajoritaire = $pdo->prepare("DELETE FROM VoteMajoritaire WHERE NumVote IN (SELECT NumVote FROM Vote WHERE NumProposition = :num)");
      $stmtVoteMajoritaire->execute(['num' => $num]);

      $stmtVote = $pdo->prepare("DELETE FROM Vote WHERE NumProposition = :num");
      $stmtVote->execute(['num' => $num]);

      $stmtSignalement1 = $pdo->prepare("DELETE FROM Signalement WHERE NumProposition = :num");
      $stmtSignalement1->execute(['num' => $num]);

      $stmtSignalement2 = $pdo->prepare("DELETE FROM Signalement WHERE NumCommentaire IN (SELECT NumCommentaire FROM Commentaire WHERE NumProposition = :num)");
      $stmtSignalement2->execute(['num' => $num]);

      $stmtLikeDislikeCommentaire = $pdo->prepare("DELETE FROM LikeDislikeCommentaire WHERE NumCommentaire IN (SELECT NumCommentaire FROM Commentaire WHERE NumProposition = :num)");
      $stmtLikeDislikeCommentaire->execute(['num' => $num]);

      $stmtCommentaire = $pdo->prepare("DELETE FROM Commentaire WHERE NumProposition = :num");
      $stmtCommentaire->execute(['num' => $num]);

      $stmt = $pdo->prepare("DELETE FROM Proposition WHERE NumProposition = :num");
      $stmt->execute(['num' => $num]);

      $pdo->commit();
      return true;
    } catch (Exception $e) {
      $pdo->rollBack();
      echo "Erreur: " . $e->getMessage();
      return false; 
    }
  }

  public static function likeOuPasProposition($numInternaute, $numProposition){
    $pdo = Connexion::pdo();

    $requete = "SELECT AimeProposition
                FROM LikeDislikeProposition
                WHERE NumInternaute = :numInternaute
                AND NumProposition = :numProposition";
    $stmt = $pdo->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
    $result = $stmt->fetch();
    $aime = $result['AimeProposition'];

    return $aime;
  }

  public static function dislikeOuPasProposition($numInternaute, $numProposition){
    $pdo = Connexion::pdo();

    $requete = "SELECT AimePasProposition
                FROM LikeDislikeProposition
                WHERE NumInternaute = :numInternaute
                AND NumProposition = :numProposition";
    $stmt = $pdo->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
    $result = $stmt->fetch();
    $aime = $result['AimePasProposition'];

    return $aime;
  }

  public static function votePourOuPasProposition($numInternaute, $numProposition){
    $pdo = Connexion::pdo();

    $requete = "SELECT Vote
                FROM VotePour
                WHERE NumInternaute = :numInternaute
                AND NumProposition = :numProposition";
    $stmt = $pdo->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
    $result = $stmt->fetch();
    $vote = $result['Vote'];

    return $vote;
  }

  public static function retirerLikeProposition($numInternaute, $numProposition){
    try {
      $requete = "UPDATE LikeDislikeProposition SET AimeProposition = 0 WHERE NumInternaute = :numInternaute AND NumProposition = :numProposition";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function mettreLikeProposition($numInternaute, $numProposition){
    try {
      $requete = "UPDATE LikeDislikeProposition SET AimeProposition = 1 WHERE NumInternaute = :numInternaute AND NumProposition = :numProposition";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function retirerDislikeProposition($numInternaute, $numProposition){
    try {
      $requete = "UPDATE LikeDislikeProposition SET AimePasProposition = 0 WHERE NumInternaute = :numInternaute AND NumProposition = :numProposition";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
      return true;
    } catch (Exception $e) {
      echo "Erreur: " . $e->getMessage();
      return false; 
    }
  }

  public static function mettreDislikeProposition($numInternaute, $numProposition){
    try {
      $requete = "UPDATE LikeDislikeProposition SET AimePasProposition = 1 WHERE NumInternaute = :numInternaute AND NumProposition = :numProposition";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
      return true;
    } catch (Exception $e) {
      echo "Erreur: " . $e->getMessage();
      return false; 
    }
  }

  public static function retirerVotePourProposition($numInternaute, $numProposition){
    try {
      $requete = "UPDATE VotePour SET Vote = 0 WHERE NumInternaute = :numInternaute AND NumProposition = :numProposition";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
      return true;
    } catch (Exception $e) {
      echo "Erreur: " . $e->getMessage();
      return false; 
    }
  }

  public static function mettreVotePourProposition($numInternaute, $numProposition){
    try {
      $requete = "UPDATE VotePour SET Vote = 1 WHERE NumInternaute = :numInternaute AND NumProposition = :numProposition";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numProposition' => $numProposition]);
      return true;
    } catch (Exception $e) {
      echo "Erreur: " . $e->getMessage();
      return false; 
    }
  }

  public static function getNombreLikes($numProposition) {
    $stmt = Connexion::pdo()->prepare("SELECT COUNT(*) as total FROM LikeDislikeProposition
                                       WHERE NumProposition = :numProposition AND AimeProposition = 1");
    $stmt->execute(['numProposition' => $numProposition]);
    $result = $stmt->fetch();
    return $result['total'];
  }

  public static function getNombreDislikes($numProposition) {
      $stmt = Connexion::pdo()->prepare("SELECT COUNT(*) as total FROM LikeDislikeProposition
                                         WHERE NumProposition = :numProposition AND AimePasProposition = 1");
      $stmt->execute(['numProposition' => $numProposition]);
      $result = $stmt->fetch();
      return $result['total'];
  }

  public static function getPourcentageVotes($numProposition, $numGroupe) {
      $stmtAdherent = Connexion::pdo()->prepare("SELECT COUNT(*) as total FROM Adherent
                                                 WHERE NumGroupe = :numGroupe");
      $stmtAdherent->execute(['numGroupe' => $numGroupe]);
      $result1 = $stmtAdherent->fetch();
      $nbAdherents = $result1['total'];

      $stmtVotePour = Connexion::pdo()->prepare("SELECT COUNT(*) as total FROM VotePour
                                         WHERE NumProposition = :numProposition AND Vote = 1");
      $stmtVotePour->execute(['numProposition' => $numProposition]);
      $result2 = $stmtVotePour->fetch();
      $nbVotes = $result2['total'];

      $pourcentage = ($nbVotes * 100.0)/$nbAdherents;
      return $pourcentage;
  }

  public static function getNombreCommentaires($numProposition) {
      $stmt = Connexion::pdo()->prepare("SELECT COUNT(*) as total FROM Commentaire
                                         WHERE NumProposition = :numProposition");
      $stmt->execute(['numProposition' => $numProposition]);
      $result = $stmt->fetch();
      return $result['total'];
  }
}
?>

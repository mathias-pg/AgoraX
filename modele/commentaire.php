<?php
require_once("modele.php");

class Commentaire extends Modele {
  public static $objet = "Commentaire";
  public static $cle = "NumCommentaire";
  public int $NumCommentaire;
  public string $Contenu;
  public string $DatePublication;
  public int $NumInternaute;
  public int $NumProposition;

  public static function commentairesParProposition($numProposition){
    $requete = "SELECT * FROM Commentaire
                WHERE NumProposition = :numProposition";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numProposition' => $numProposition]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Commentaire::class);
    return $stmt->fetchAll(); 
  }

  public static function commentairesParUnInternaute($numInternaute){
    $requete = "SELECT * FROM Commentaire
                WHERE NumInternaute = :numInternaute";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Commentaire::class);
    return $stmt->fetchAll(); 
  }

  public static function commentairesParUnInternauteEtGroupe($numInternaute, $numGroupe){
    $requete = "SELECT * FROM Commentaire
                WHERE NumInternaute = :numInternaute
                AND NumProposition IN (SELECT NumProposition FROM Proposition WHERE NumGroupe = :numGroupe)";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Commentaire::class);
    return $stmt->fetchAll(); 
  }

  public static function nouveauCommentaire($numProposition, $numInternaute, $contenuCommentaire){
    try {
      $pdo = Connexion::pdo();
      $pdo->beginTransaction();
      
      $stmtNum = $pdo->query("SELECT IFNULL(MAX(NumCommentaire), 0) + 1 AS nextNum FROM Commentaire");
      $result1 = $stmtNum->fetch();
      $nextNumCommentaire = $result1['nextNum'];

      $stmtNum = $pdo->prepare("SELECT NumGroupe FROM Proposition WHERE NumProposition = :numProposition");
      $stmtNum->execute(['numProposition' => $numProposition]);
      $result2 = $stmtNum->fetch();
      $numGroupe = $result2['NumGroupe'];

      $requeteCommentaire = "INSERT INTO Commentaire VALUES (:num, :contenu, SYSDATE(), :numInternaute, :numProposition)";
      $stmtCommentaire = Connexion::pdo()->prepare($requeteCommentaire);
      $stmtCommentaire->execute(['num' => $nextNumCommentaire, 'contenu' => $contenuCommentaire, 'numInternaute' => $numInternaute, 'numProposition' => $numProposition]);

      $requeteLikeDislike = "INSERT INTO LikeDislikeCommentaire (NumInternaute, NumCommentaire, AimeCommentaire, AimePasCommentaire) 
                             SELECT NumInternaute, :numCommentaire, 0, 0 
                             FROM Adherent 
                             WHERE NumGroupe = :numGroupe";
      $stmtLikeDislike = $pdo->prepare($requeteLikeDislike);
      $stmtLikeDislike->execute(['numCommentaire' => $nextNumCommentaire, 'numGroupe' => $numGroupe]);

      $pdo->commit();
      return true;
    } catch (Exception $e) {
      $pdo->rollBack();
      return false;
    }
  }

  public static function supprimerCommentaire($num){
    try {
      $pdo = Connexion::pdo(); 
      $pdo->beginTransaction();

      $stmtSignalement = $pdo->prepare("DELETE FROM Signalement WHERE NumCommentaire = :num");
      $stmtSignalement->execute(['num' => $num]);

      $stmtLikeDislike = $pdo->prepare("DELETE FROM LikeDislikeCommentaire WHERE NumCommentaire = :num");
      $stmtLikeDislike->execute(['num' => $num]);

      $stmtCommentaire = $pdo->prepare("DELETE FROM Commentaire WHERE NumCommentaire = :num");
      $stmtCommentaire->execute(['num' => $num]);

      $pdo->commit();
      return true;
    } catch (Exception $e) {
      $pdo->rollBack();
      return false; 
    }
  }

  public static function likeOuPasCommentaire($numInternaute, $numCommentaire){
    $pdo = Connexion::pdo();

    $requete = "SELECT AimeCommentaire
                FROM LikeDislikeCommentaire
                WHERE NumInternaute = :numInternaute
                AND NumCommentaire = :numCommentaire";
    $stmt = $pdo->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numCommentaire' => $numCommentaire]);
    $result = $stmt->fetch();
    $aime = $result['AimeCommentaire'];

    return $aime;
  }

  public static function dislikeOuPasCommentaire($numInternaute, $numCommentaire){
    $pdo = Connexion::pdo();

    $requete = "SELECT AimePasCommentaire
                FROM LikeDislikeCommentaire
                WHERE NumInternaute = :numInternaute
                AND NumCommentaire = :numCommentaire";
    $stmt = $pdo->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute, 'numCommentaire' => $numCommentaire]);
    $result = $stmt->fetch();
    $aime = $result['AimePasCommentaire'];

    return $aime;
  }

  public static function retirerLikeCommentaire($numInternaute, $numCommentaire){
    try {
      $requete = "UPDATE LikeDislikeCommentaire SET AimeCommentaire = 0 WHERE NumInternaute = :numInternaute AND NumCommentaire = :numCommentaire";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numCommentaire' => $numCommentaire]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function retirerDislikeCommentaire($numInternaute, $numCommentaire){
    try {
      $requete = "UPDATE LikeDislikeCommentaire SET AimePasCommentaire = 0 WHERE NumInternaute = :numInternaute AND NumCommentaire = :numCommentaire";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numCommentaire' => $numCommentaire]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function mettreLikeCommentaire($numInternaute, $numCommentaire){
    try {
      $requete = "UPDATE LikeDislikeCommentaire SET AimeCommentaire = 1 WHERE NumInternaute = :numInternaute AND NumCommentaire = :numCommentaire";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numCommentaire' => $numCommentaire]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function mettreDislikeCommentaire($numInternaute, $numCommentaire){
    try {
      $requete = "UPDATE LikeDislikeCommentaire SET AimePasCommentaire = 1 WHERE NumInternaute = :numInternaute AND NumCommentaire = :numCommentaire";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['numInternaute' => $numInternaute, 'numCommentaire' => $numCommentaire]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function getNombreLikes($numCommentaire) {
    $stmt = Connexion::pdo()->prepare("SELECT COUNT(*) as total FROM LikeDislikeCommentaire
                                       WHERE NumCommentaire = :numCommentaire AND AimeCommentaire = 1");
    $stmt->execute(['numCommentaire' => $numCommentaire]);
    $result = $stmt->fetch();
    return $result['total'];
  }

  public static function getNombreDislikes($numCommentaire) {
    $stmt = Connexion::pdo()->prepare("SELECT COUNT(*) as total FROM LikeDislikeCommentaire
                                       WHERE NumCommentaire = :numCommentaire AND AimePasCommentaire = 1");
    $stmt->execute(['numCommentaire' => $numCommentaire]);
    $result = $stmt->fetch();
    return $result['total'];
  }

}
?>
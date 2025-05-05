<?php
require_once("modele.php");
require_once(__DIR__."/../controleur/ControleurCommentaire.php");
require_once(__DIR__."/../controleur/ControleurProposition.php");

class Groupe extends Modele {
  public static $objet = "Groupe";
  public static $cle = "NumGroupe";
  public int $NumGroupe;
  public string $NomGroupe;
  public string $DescriptionGroupe;
  public string $CouleurGroupe;
  public string $DateCreation;
  public string $MontantTotalDispo;

  public static function groupesParInternaute($numInternaute) {
    $requete = "SELECT * FROM Groupe G
                INNER JOIN Adherent A 
                ON G.NumGroupe = A.NumGroupe
                WHERE A.NumInternaute = :numInternaute;";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Groupe::class);
    return $stmt->fetchAll();
  }

  public static function nouveauGroupe($nom, $description, $couleur, $themes, $montant, $numInternaute) {
    try {
        $pdo = Connexion::pdo(); 

        $pdo->beginTransaction();

        $stmt = Connexion::pdo()->query("SELECT IFNULL(MAX(NumGroupe), 0) + 1 AS nextNum FROM Groupe");
        $result = $stmt->fetch();
        $numGroupe = $result['nextNum'];

        $stmtGroupe = $pdo->prepare("INSERT INTO Groupe VALUES (:num, :nom, :description, :couleur, SYSDATE(), :budget)");
        $stmtGroupe->execute(['num' => $numGroupe, 'nom' => $nom, 'description' => $description, 'couleur' => $couleur, 'budget' => $montant]);

        $stmt2 = Connexion::pdo()->query("SELECT IFNULL(MAX(NumTheme), 0) + 1 AS nextNum FROM Theme");
        $result2 = $stmt2->fetch();
        $numTheme = $result2['nextNum'];

        $stmtTheme = $pdo->prepare("INSERT INTO Theme VALUES (:numTheme, :nomTheme, 0)");
        $stmtSujet = $pdo->prepare("INSERT INTO Sujet VALUES (:numGroupe, :numTheme)");

        foreach ($themes as $theme) {
            $stmtTheme->execute(['numTheme' => $numTheme, 'nomTheme' => $theme]);
            $stmtSujet->execute(['numGroupe' => $numGroupe, 'numTheme' => $numTheme]);
            $numTheme += 1;
        }

        $stmtRole = $pdo->query("SELECT NumRole FROM Role WHERE NomRole = 'Administrateur'");
        $result = $stmtRole->fetch();
        $numRole = $result['NumRole'];

        $stmtAssignation = $pdo->prepare("INSERT INTO Assignation VALUES (:numInternaute, :numRole, :numGroupe)");
        $stmtAssignation->execute(['numInternaute' => $numInternaute, 'numRole' => $numRole, 'numGroupe' => $numGroupe]);

        $stmtAdherent = $pdo->prepare("INSERT INTO Adherent VALUES (:numGroupe, :numInternaute)");
        $stmtAdherent->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);

        $pdo->commit();
        return $numGroupe;

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erreur: " . $e->getMessage();
        return false;
    }
  }

  public static function nouvelleImage($numGroupe, $nomImage, $tailleImage, $typeImage, $binImage) {
    try {
        $stmt = Connexion::pdo()->query("SELECT IFNULL(MAX(NumImage), 0) + 1 AS nextNum FROM Image");
        $result = $stmt->fetch();
        $nextNum = $result['nextNum'];
      
        $stmt = Connexion::pdo()->prepare("INSERT INTO Image VALUES (:num, :nomImage, :tailleImage, :typeImage, :binImage, :numGroupe)");
        $stmt->execute(['num' => $nextNum, 'nomImage' => $nomImage, 'tailleImage' => $tailleImage, 'typeImage' => $typeImage, 'binImage' => $binImage, 'numGroupe' => $numGroupe]);
        return true;

    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
        return false;
    }
  }

  public static function nouvelInternauteGroupe($email, $numGroupe, $role){
    try {
        $pdo = Connexion::pdo(); 
        $pdo->beginTransaction();
        
        $stmtInternaute = $pdo->prepare("SELECT NumInternaute FROM Internaute WHERE AdresseMail = :email");
        $stmtInternaute->execute(['email' => $email]);
        $result1 = $stmtInternaute->fetch();
        $numInternaute = $result1['NumInternaute'];

        $stmtRole = $pdo->prepare("SELECT NumRole FROM Role WHERE NomRole = :role");
        $stmtRole->execute(['role' => $role]);
        $result2 = $stmtRole->fetch();
        $numRole = $result2['NumRole'];

        $stmtAssignation = $pdo->prepare("INSERT INTO Assignation (NumInternaute, NumRole, NumGroupe) VALUES (:numInternaute, :numRole, :numGroupe)");
        $stmtAssignation->execute(['numInternaute' => $numInternaute, 'numRole' => $numRole, 'numGroupe' => $numGroupe]);

        $stmtAdherent = $pdo->prepare("INSERT INTO Adherent (NumGroupe, NumInternaute) VALUES (:numGroupe, :numInternaute)");
        $stmtAdherent->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);

        $requeteLikeDislikeCommentaire = "INSERT INTO LikeDislikeCommentaire (NumInternaute, NumCommentaire, AimeCommentaire, AimePasCommentaire) 
                               SELECT :numInternaute, C.NumCommentaire, 0, 0 
                               FROM Commentaire C
                               INNER JOIN Proposition P
                               ON C.NumProposition = P.NumProposition
                               WHERE NumGroupe = :numGroupe";
        $stmtLikeDislikeCommentaire = $pdo->prepare($requeteLikeDislikeCommentaire);
        $stmtLikeDislikeCommentaire->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);

        $requeteLikeDislikeProposition = "INSERT INTO LikeDislikeProposition (NumInternaute, NumProposition, AimeProposition, AimePasProposition) 
                               SELECT :numInternaute, P.NumProposition, 0, 0 
                               FROM Proposition P 
                               WHERE NumGroupe = :numGroupe";
        $stmtLikeDislikeProposition = $pdo->prepare($requeteLikeDislikeProposition);
        $stmtLikeDislikeProposition->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);

        $requeteVotePour = "INSERT INTO VotePour (NumInternaute, NumProposition, Vote) 
                            SELECT :numInternaute, P.NumProposition, 0 
                            FROM Proposition P
                            WHERE NumGroupe = :numGroupe";
        $stmtVotePour = $pdo->prepare($requeteVotePour);
        $stmtVotePour->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur: " . $e->getMessage();
        return false;
    }
  }


  public static function retirerInternauteGroupe($numGroupe, $numInternaute) {
    try {
        $pdo = Connexion::pdo(); 
        $pdo->beginTransaction();

        $commentaires = ControleurCommentaire::getCommentairesParUnInternauteEtGroupe($numInternaute, $numGroupe);
        $propositions = ControleurProposition::getPropositionsParUnInternauteEtGroupe($numInternaute, $numGroupe);

        foreach($commentaires as $commentaire) {
          $numCommentaire = $commentaire->get('NumCommentaire');

          $stmtLikeDislikeCommentaire = $pdo->prepare("DELETE FROM LikeDislikeCommentaire WHERE NumCommentaire = :num");
          $stmtLikeDislikeCommentaire->execute(['num' => $numCommentaire]);
      
          $stmtSignalement = $pdo->prepare("DELETE FROM Signalement WHERE NumCommentaire = :num");
          $stmtSignalement->execute(['num' => $numCommentaire]);
      
          $stmtCommentaire = $pdo->prepare("DELETE FROM Commentaire WHERE NumCommentaire = :num");
          $stmtCommentaire->execute(['num' => $numCommentaire]);
        }

        foreach($propositions as $proposition) {
            $numProposition = $proposition->get('NumProposition');
            
            $stmtVotePour = $pdo->prepare("DELETE FROM VotePour WHERE NumProposition = :num");
            $stmtVotePour->execute(['num' => $numProposition]);
      
            $stmtLikeDislike = $pdo->prepare("DELETE FROM LikeDislikeProposition WHERE NumProposition = :num");
            $stmtLikeDislike->execute(['num' => $numProposition]);
      
            $stmtVoteSoumis = $pdo->prepare("DELETE FROM VoteSoumis WHERE NumVote IN (SELECT NumVote FROM Vote WHERE NumProposition = :num)");
            $stmtVoteSoumis->execute(['num' => $numProposition]);
      
            $stmtVoteSoumisNotation = $pdo->prepare("DELETE FROM VoteSoumisNotation WHERE NumVote IN (SELECT NumVote FROM Vote WHERE NumProposition = :num)");
            $stmtVoteSoumisNotation->execute(['num' => $numProposition]);
      
            $stmtVoteMajoritaire = $pdo->prepare("DELETE FROM VoteMajoritaire WHERE NumVote IN (SELECT NumVote FROM Vote WHERE NumProposition = :num)");
            $stmtVoteMajoritaire->execute(['num' => $numProposition]);
      
            $stmtVote = $pdo->prepare("DELETE FROM Vote WHERE NumProposition = :num");
            $stmtVote->execute(['num' => $numProposition]);
      
            $stmtSignalement1 = $pdo->prepare("DELETE FROM Signalement WHERE NumProposition = :num");
            $stmtSignalement1->execute(['num' => $numProposition]);
      
            $stmtSignalement2 = $pdo->prepare("DELETE FROM Signalement WHERE NumCommentaire IN (SELECT NumCommentaire FROM Commentaire WHERE NumProposition = :num)");
            $stmtSignalement2->execute(['num' => $numProposition]);
      
            $stmtLikeDislikeCommentaire = $pdo->prepare("DELETE FROM LikeDislikeCommentaire WHERE NumCommentaire IN (SELECT NumCommentaire FROM Commentaire WHERE NumProposition = :num)");
            $stmtLikeDislikeCommentaire->execute(['num' => $numProposition]);
      
            $stmtCommentaire = $pdo->prepare("DELETE FROM Commentaire WHERE NumProposition = :num");
            $stmtCommentaire->execute(['num' => $numProposition]);
      
            $stmt = $pdo->prepare("DELETE FROM Proposition WHERE NumProposition = :num");
            $stmt->execute(['num' => $numProposition]);
        }

        $stmtLikeDislikeCommentaire = $pdo->prepare("DELETE FROM LikeDislikeCommentaire WHERE NumInternaute = :num AND NumCommentaire IN (SELECT C.NumCommentaire FROM Commentaire C
                                                                                                                                          INNER JOIN Proposition P 
                                                                                                                                          ON C.NumProposition = P.NumProposition 
                                                                                                                                          WHERE P.NumGroupe = :numGroupe)");
        $stmtLikeDislikeCommentaire->execute(['num' => $numInternaute, 'numGroupe' => $numGroupe]);

        $stmtLikeDislikeProposition = $pdo->prepare("DELETE FROM LikeDislikeProposition WHERE NumInternaute = :num AND NumProposition IN (SELECT NumProposition FROM Proposition WHERE NumGroupe = :numGroupe)");
        $stmtLikeDislikeProposition->execute(['num' => $numInternaute, 'numGroupe' => $numGroupe]);

        $stmtVotePour = $pdo->prepare("DELETE FROM VotePour WHERE NumInternaute = :num AND NumProposition IN (SELECT NumProposition FROM Proposition WHERE NumGroupe = :numGroupe)");
        $stmtVotePour->execute(['num' => $numInternaute, 'numGroupe' => $numGroupe]);

        $stmtVoteSoumis = $pdo->prepare("DELETE FROM VoteSoumis WHERE NumInternaute = :num AND NumVote IN (SELECT NumVote FROM Vote WHERE NumGroupe = :numGroupe)");
        $stmtVoteSoumis->execute(['num' => $numInternaute, 'numGroupe' => $numGroupe]);

        $stmtVoteSoumisNotation = $pdo->prepare("DELETE FROM VoteSoumisNotation WHERE NumInternaute = :num AND NumVote IN (SELECT NumVote FROM Vote WHERE NumGroupe = :numGroupe)");
        $stmtVoteSoumisNotation->execute(['num' => $numInternaute, 'numGroupe' => $numGroupe]);

        $stmtSignalement1 = $pdo->prepare("DELETE FROM Signalement WHERE NumInternaute = :numInternaute AND NumCommentaire IN (SELECT C.NumCommentaire FROM Commentaire C
                                                                                                                               INNER JOIN Proposition P 
                                                                                                                               ON C.NumProposition = P.NumProposition 
                                                                                                                               WHERE P.NumGroupe = :numGroupe)");
        $stmtSignalement1->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);

        $stmtSignalement2 = $pdo->prepare("DELETE FROM Signalement WHERE NumInternaute = :numInternaute AND NumProposition IN (SELECT NumProposition FROM Proposition
                                                                                                                               WHERE NumGroupe = :numGroupe)");
        $stmtSignalement2->execute(['numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);

        $stmtAdherent = $pdo->prepare("DELETE FROM Adherent WHERE NumGroupe = :numGroupe AND NumInternaute = :numInternaute");
        $stmtAdherent->execute(['numGroupe' => $numGroupe, 'numInternaute' => $numInternaute]);

        $stmtAssignation = $pdo->prepare("DELETE FROM Assignation WHERE NumGroupe = :numGroupe AND NumInternaute = :numInternaute");
        $stmtAssignation->execute(['numGroupe' => $numGroupe, 'numInternaute' => $numInternaute]);

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erreur: " . $e->getMessage();
        return false; 
    }
  }


}
?>
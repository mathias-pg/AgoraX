<?php
require_once("modele.php");
require_once(__DIR__."/../controleur/ControleurCommentaire.php");
require_once(__DIR__."/../controleur/ControleurProposition.php");

class Internaute extends Modele {
  public static $objet = "Internaute";
  public static $cle = "NumInternaute";
  public int $NumInternaute;
  public string $NomInternaute;
  public string $PrenomInternaute;
  public string $AdresseMail;
  public string $MotDePasse;
  public string $AdressePostal;
  public string $DateInscription;

  public static function verifierConnexion($email, $mdp) {
    $requete = "SELECT * FROM Internaute WHERE AdresseMail = :email AND MotDePasse = :mdp;";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['email' => $email, 'mdp' => $mdp]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Internaute::class);
    return $stmt->fetch();
  }

  public static function auteurCommentaire($numCommentaire){
    $requete = "SELECT * FROM Internaute I
                INNER JOIN Commentaire C
                ON I.NumInternaute = C.NumInternaute
                WHERE NumCommentaire = :numCommentaire";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numCommentaire' => $numCommentaire]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Internaute::class);
    return $stmt->fetch();
  }

  public static function auteurProposition($numProposition){
    $requete = "SELECT * FROM Internaute I
                INNER JOIN Proposition P
                ON I.NumInternaute = P.NumInternaute
                WHERE NumProposition = :numProposition";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numProposition' => $numProposition]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Internaute::class);
    return $stmt->fetch();
  }

  public static function internauteEmail($email){
    $requete = "SELECT * FROM Internaute 
                WHERE AdresseMail = :email";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['email' => $email]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Internaute::class);
    return $stmt->fetch();
  }

  public static function membresDuGroupe($numGroupe) {
    $requete = "SELECT * FROM Internaute I
                INNER JOIN Assignation A
                ON I.NumInternaute = A.NumInternaute
                INNER JOIN Role R
                ON A.NumRole = R.NumRole
                WHERE A.NumGroupe = :numGroupe;";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Internaute::class);
    return $stmt->fetchAll(); 
  }

  public static function nouvelInternaute($nom, $prenom, $email, $adresse, $motDePasse) {
    try {
      $stmt = Connexion::pdo()->query("SELECT IFNULL(MAX(NumInternaute), 0) + 1 AS nextNum FROM Internaute");
      $result = $stmt->fetch();
      $nextNum = $result['nextNum'];

      $requete = "INSERT INTO Internaute VALUES (:num, :nom, :prenom, :email, :motDePasse, :adresse, SYSDATE())";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['num' => $nextNum, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'adresse' => $adresse, 'motDePasse' => $motDePasse]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function modifierInternaute($num, $nom, $prenom, $email, $adresse, $motDePasse) {
    try {
      $requete = "UPDATE Internaute SET NomInternaute = :nom, PrenomInternaute = :prenom, AdresseMail = :email, MotDePasse = :motDePasse, AdressePostal = :adresse WHERE NumInternaute = :num";
      $stmt = Connexion::pdo()->prepare($requete);
      $stmt->execute(['num' => $num, 'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'adresse' => $adresse, 'motDePasse' => $motDePasse]);
      return true;
    } catch (Exception $e) {
      return false; 
    }
  }

  public static function supprimerInternaute($num){
    try {
      $pdo = Connexion::pdo(); 
      $pdo->beginTransaction();

      $commentaires = ControleurCommentaire::getCommentairesParUnInternaute($num);
      $propositions = ControleurProposition::getPropositionsParUnInternaute($num);

      foreach($commentaires as $commentaire){
        $numCommentaire = $commentaire->get('NumCommentaire');
        
        $stmtSignalement = $pdo->prepare("DELETE FROM Signalement WHERE NumCommentaire = :num");
        $stmtSignalement->execute(['num' => $numCommentaire]);

        $stmtLikeDislikeCommentaire = $pdo->prepare("DELETE FROM LikeDislikeCommentaire WHERE NumCommentaire = :num");
        $stmtLikeDislikeCommentaire->execute(['num' => $numCommentaire]);

        $stmtCommentaire = $pdo->prepare("DELETE FROM Commentaire WHERE NumCommentaire = :num");
        $stmtCommentaire->execute(['num' => $numCommentaire]);
      }

      foreach($propositions as $proposition){
        $numProposition = $proposition->get('NumProposition');
        
        $stmtVotePour = $pdo->prepare("DELETE FROM VotePour WHERE NumProposition = :num");
        $stmtVotePour->execute(['num' => $numProposition]);
        
        $stmtLikeDislikeProposition = $pdo->prepare("DELETE FROM LikeDislikeProposition WHERE NumProposition = :num");
        $stmtLikeDislikeProposition->execute(['num' => $numProposition]);

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

      $stmtRecevoir = $pdo->prepare("DELETE FROM Recevoir WHERE NumInternaute = :num");
      $stmtRecevoir->execute(['num' => $num]);

      $stmtAdherent = $pdo->prepare("DELETE FROM Adherent WHERE NumInternaute = :num");
      $stmtAdherent->execute(['num' => $num]);

      $stmtAssignation = $pdo->prepare("DELETE FROM Assignation WHERE NumInternaute = :num");
      $stmtAssignation->execute(['num' => $num]);

      $stmtSignalement = $pdo->prepare("DELETE FROM Signalement WHERE NumInternaute = :num");
      $stmtSignalement->execute(['num' => $num]);

      $stmtLikeDislikeCommentaire = $pdo->prepare("DELETE FROM LikeDislikeCommentaire WHERE NumInternaute = :num");
      $stmtLikeDislikeCommentaire->execute(['num' => $num]);

      $stmtLikeDislikeProposition = $pdo->prepare("DELETE FROM LikeDislikeProposition WHERE NumInternaute = :num");
      $stmtLikeDislikeProposition->execute(['num' => $num]);

      $stmtVotePour = $pdo->prepare("DELETE FROM VotePour WHERE NumInternaute = :num");
      $stmtVotePour->execute(['num' => $num]);

      $stmtVoteSoumis = $pdo->prepare("DELETE FROM VoteSoumis WHERE NumInternaute = :num");
      $stmtVoteSoumis->execute(['num' => $num]);

      $stmtVoteSoumisNotation = $pdo->prepare("DELETE FROM VoteSoumisNotation WHERE NumInternaute = :num");
      $stmtVoteSoumisNotation->execute(['num' => $num]);

      $stmtInternaute = $pdo->prepare("DELETE FROM Internaute WHERE NumInternaute = :num");
      $stmtInternaute->execute(['num' => $num]);

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
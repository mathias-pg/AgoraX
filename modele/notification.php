<?php
require_once("modele.php");

class Notification extends Modele {
  public static $objet = "Notification";
  public static $cle = "NumNotification";
  public int $NumNotification;
  public string $TypeNotification;
  public string $DateNotification;
  public string $StatutNotification;
  public int $NumGroupe;

  public static function notificationsParInternuate($numInternaute) {
    $requete = "SELECT * FROM Notification N
                INNER JOIN Recevoir R
                ON N.NumNotification = R.NumNotification
                WHERE R.NumInternaute = :numInternaute";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Notification::class);
    return $stmt->fetchAll(); 
  }

  public static function enleverNotification($numNotification, $numInternaute){
    try {
      $pdo = Connexion::pdo(); 
      $pdo->beginTransaction();

      $stmtRecevoir = $pdo->prepare("DELETE FROM Recevoir WHERE NumNotification = :numNotification AND NumInternaute = :numInternaute");
      $stmtRecevoir->execute(['numNotification' => $numNotification, 'numInternaute' => $numInternaute]);

      $pdo->commit();
      return true;
    } catch (Exception $e) {
      $pdo->rollBack();
      return false; 
    }
  }
}
?>

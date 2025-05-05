<?php
require_once("modele.php");

class Theme extends Modele {
  public static $objet = "Theme";
  public static $cle = "NumTheme";
  public int $NumTheme;
  public string $NomTheme;
  public int $MontantTheme;

  public static function themesParGroupe($numGroupe) {
    $requete = "SELECT * FROM Theme T
                INNER JOIN Sujet S
                ON T.NumTheme = S.NumTheme
                WHERE NumGroupe = :numGroupe";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Theme::class);
    return $stmt->fetchAll(); 
  }
}
?>

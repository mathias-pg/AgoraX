<?php
require_once("modele.php");

class Image extends Modele {
  public static $objet = "Image";
  public static $cle = "NumImage";
  public int $NumImage;
  public string $NomImage;
  public string $TailleImage;
  public string $TypeImage;
  public string $Bin;
  public int $NumGroupe;

  public static function imageGroupe($numGroupe){
    $requete = "SELECT * FROM Image
                WHERE NumGroupe = :numGroupe";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Image::class);
    return $stmt->fetch();
  }

}


?>
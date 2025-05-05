<?php
include("connexion.php");
Connexion::connect();

class Modele {
    public function get($attribut) {
        return $this->$attribut;
    }

    public function set($attribut, $valeur) {
        $this->$attribut = $valeur;
    }

    public function __construct($donnees = null) {
        if (!is_null($donnees)) {
            foreach ($donnees as $attribut => $valeur) {
                $this->set($attribut, $valeur);
            }
        }
    }

    public static function getAll() {
        $table = static::$objet;
        $requete = "SELECT * FROM $table;";
        $resultat = Connexion::pdo()->query($requete);
        $resultat->setFetchMode(PDO::FETCH_CLASS, static::class);
        return $resultat->fetchAll();
    }

    public static function getObjetById($id) {
        $cle = static::$cle;
        $table = static::$objet;
        $requete = "SELECT * FROM $table WHERE $cle = :id;";
        $stmt = Connexion::pdo()->prepare($requete);
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }
}
?>
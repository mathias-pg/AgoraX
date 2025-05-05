<?php
require_once("modele.php");

class Role extends Modele {
  public static $objet = "Role";
  public static $cle = "NumRole";
  public int $NumRole;
  public string $NomRole;

  public static function roleParInternauteEtGroupe($numInternaute, $numGroupe) {
    $requete = "SELECT * FROM Role R
                INNER JOIN Assignation A ON R.NumRole = A.NumRole
                WHERE A.NumInternaute = :numInternaute AND A.NumGroupe = :numGroupe;";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['numInternaute' => $numInternaute,'numGroupe' => $numGroupe]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, Role::class);
    return $stmt->fetch(); 
  }

  public static function mettreAJourRole($numInternaute, $numGroupe, $nouveauRole) {
    try {
        $pdo = Connexion::pdo();

        $stmtRole = $pdo->prepare("SELECT NumRole FROM Role
                                   WHERE NomRole = :nouveauRole");
        $stmtRole->execute(['nouveauRole' => $nouveauRole]);
        $result = $stmtRole->fetch();
        $numRole = $result['NumRole'];

        $stmtAssignation = $pdo->prepare("UPDATE Assignation SET NumRole = :numRole WHERE NumInternaute = :numInternaute AND NumGroupe = :numGroupe");
        $stmtAssignation->execute(['numRole' => $numRole, 'numInternaute' => $numInternaute, 'numGroupe' => $numGroupe]);
        return true;
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
        return false;
    }
  }
}
?>

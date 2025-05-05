<?php
require_once("../modele/connexion.php");
Connexion::connect();

try {
    $numGroupe = $_GET["NumGroupe"];
    $budget = $_GET["MontantTotalDispo"];

    $requete = "UPDATE Groupe SET MontantTotalDispo = :budget WHERE NumGroupe = :numGroupe";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['budget' => $budget, 'numGroupe' => $numGroupe]);

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();;
}
?>
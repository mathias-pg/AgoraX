<?php
require_once("../modele/connexion.php");
Connexion::connect();

try {
    $numProposition = $_GET["NumProposition"];
    $budget = $_GET["CoutProposition"];

    $requete = "UPDATE Proposition SET CoutProposition = :budget WHERE NumProposition = :numProposition";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['budget' => $budget, 'numProposition' => $numProposition]);

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>
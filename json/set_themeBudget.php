<?php
require_once("../modele/connexion.php");
Connexion::connect();

try {
    $numTheme = $_GET["NumTheme"];
    $budget = $_GET["MontantTheme"];

    $requete = "UPDATE Theme SET MontantTheme = :budget WHERE NumTheme = :numTheme";
    $stmt = Connexion::pdo()->prepare($requete);
    $stmt->execute(['budget' => $budget, 'numTheme' => $numTheme]);

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage(); 
}
?>
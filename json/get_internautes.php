<?php
require_once("../modele/connexion.php");
Connexion::connect();

try {

    $stmt = Connexion::pdo()->query("SELECT * FROM Internaute");
    $internautes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($internautes);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
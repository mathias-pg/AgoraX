<?php
require_once("../modele/connexion.php");
Connexion::connect();

try {
    $numProposition = $_GET["NumProposition"];
    $stmt = Connexion::pdo()->query("SELECT * FROM Theme T
                                     INNER JOIN Proposition P
                                     ON T.NumTheme = P.NumTheme
                                     WHERE NumProposition = $numProposition");
    $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($themes);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
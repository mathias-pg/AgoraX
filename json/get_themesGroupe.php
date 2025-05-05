<?php
require_once("../modele/connexion.php");
Connexion::connect();

try {
    $numGroupe = $_GET["NumGroupe"];
    $stmt = Connexion::pdo()->query("SELECT * FROM Theme T
                                     INNER JOIN Sujet S
                                     ON T.NumTheme = S.NumTheme
                                     WHERE NumGroupe = $numGroupe");
    $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($themes);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
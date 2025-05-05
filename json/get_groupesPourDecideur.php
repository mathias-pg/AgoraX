<?php
require_once("../modele/connexion.php");
Connexion::connect();

try {
    $numDecideur = $_GET["NumInternaute"];
    $stmt = Connexion::pdo()->query("SELECT G.NumGroupe, NomGroupe, DescriptionGroupe, CouleurGroupe, DateCreation, MontantTotalDispo, (SELECT COUNT(*) FROM Adherent WHERE NumGroupe = G.NumGroupe) AS NbInternautes
                                    FROM Groupe G
                                    INNER JOIN Assignation A 
                                    ON G.NumGroupe = A.NumGroupe
                                    INNER JOIN Role R
                                    ON A.NumRole = R.NumRole 
                                    WHERE A.NumInternaute = $numDecideur
                                    AND R.NomRole = 'Decideur'");
    $groupes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($groupes);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
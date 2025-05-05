<?php
require_once("../modele/connexion.php");
Connexion::connect();

try {
    $numgroupe = $_GET["NumGroupe"];
    $stmt = Connexion::pdo()->query("SELECT P.NumProposition, TitreProposition, DescriptionProposition, DateSoumission, DureeDiscussion, EtatProposition, CoutProposition, P.NumInternaute, NumGroupe, NumTheme,
                                        (SELECT COUNT(*) FROM LikeDislikeProposition LD WHERE LD.NumProposition = P.NumProposition AND AimeProposition = 1) AS NbLikes,
                                        (SELECT COUNT(*) FROM VotePour V WHERE V.NumProposition = P.NumProposition AND Vote = 1) AS NbVotesPour
                                    FROM Proposition P
                                    WHERE NumGroupe = $numgroupe");
    $propositions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($propositions);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
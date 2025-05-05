<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un vote</title>
    <link rel="stylesheet" href="./../../css/ajout_membre.css">
</head>
<body>
    <div class="container">
        <h1>Déclencher un vote</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>
        
        <form action="ajouter_vote.php" method="GET">
            <input type="hidden" name="NumGroupe" value="<?= $_GET['NumGroupe'] ?>">
            <input type="hidden" name="NumProposition" value="<?= $_GET['NumProposition'] ?>">
            <input type="hidden" name="role" value="<?= $_GET['role'] ?>">

            <label for="duree">Durée du vote :</label>
            <input type="text" id="duree" name="duree" placeholder="Entrez la durée du vote" required>

            <label for="typeVote">Type de scrutin :</label>
            <select id="typeVote" name="vote" required>
                <option value="" disabled selected>Choisir un type de vote</option>
                <option value="oui/non">Réponse Oui / Non</option>
                <option value="pour/contre">Réponse Pour / Contre</option>
                <option value="majoritaire_liste">Scrutin à jugement majoriatire avec liste d'évaluation associé</option>
                <option value="majoritaire_simple">Scrutin majoriatire simple</option>
            </select>

            <div class="buttons">
                <input type="submit" value="Continuer">
            </div>
        </form>

        <a href="./../groupeDetails<?= $_GET['role'] ?>.php?NumGroupe=<?= $_GET['NumGroupe'] ?>">Retour au groupe</a>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un membre</title>
    <link rel="stylesheet" href="./../../css/ajout_membre.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un internaute</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>
        
        <form action="ajout_membre.php" method="GET">
            <input type="hidden" name="NumGroupe" value="<?= $_GET['NumGroupe'] ?>">
            <input type="hidden" name="roleConnecte" value="<?= $_GET['role'] ?>">

            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" placeholder="Entrez l'adresse e-mail" required>

            <label for="role">Rôle :</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Choisir un rôle</option>
                <option value="Membre">Membre</option>
                <option value="Moderateur">Modérateur</option>
                <option value="Assesseur">Assesseur</option>
                <option value="Scrutateur">Scrutateur</option>
                <option value="Decideur">Décideur</option>
            </select>

            <div class="buttons">
                <input type="submit" value="Envoyer l'invitation">
            </div>
        </form>

        <a href="./../groupeDetails<?= $_GET['role'] ?>.php?NumGroupe=<?= $_GET['NumGroupe'] ?>">Retour au groupe</a>
    </div>
</body>
</html>
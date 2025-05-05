<?php
require_once("./../../controleur/ControleurTheme.php");
require_once("./../../controleur/ControleurGroupe.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe'])) {
    header('Location: ./../index.php');
    exit;
}

$numGroupe = $_GET['NumGroupe'];
$nomRole = $_GET['role'];
$themes = ControleurTheme::getThemesParGroupe($numGroupe);
$groupe = ControleurGroupe::getObjetById($numGroupe);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Proposition</title>
    <link rel="stylesheet" href="./../../css/creation_proposition.css">
</head>
<body>
    <div class="container">
        <h1>Créer une Proposition</h1>

        <?php if (isset($_GET['erreur']) && !empty($_GET['erreur'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['erreur']) ?></p>
        <?php endif; ?>

        <form method="GET" action="creation_proposition.php">
            <input type="hidden" name="NumGroupe" value="<?= htmlspecialchars($numGroupe) ?>">
            <input type="hidden" name="role" value="<?= $nomRole ?>">

            <div class="form-group">
                <label for="titre">Titre de la Proposition :</label>
                <input type="text" id="titre" name="titre" required>
            </div>

            <div class="form-group">
                <label for="description">Description :</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="cout">Coût :</label>
                <input type="number" id="cout" name="cout" min="0" required>
            </div>

            <div class="form-group">
                <label for="theme">Thème associé :</label>
                <select id="theme" name="NumTheme" required>
                    <option value="" disabled selected>Choisissez un thème</option>
                    <?php foreach ($themes as $theme): ?>
                        <option value="<?= htmlspecialchars($theme->get('NumTheme')) ?>">
                            <?= htmlspecialchars($theme->get('NomTheme')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="button-container">
                <button type="submit" class="submit-btn">Créer Proposition</button>
            </div>
        </form>

        <a href="../groupeDetails<?= $nomRole ?>.php?NumGroupe=<?php echo $numGroupe ?>">Retour à l'accueil</a>
    </div>
</body>
</html>

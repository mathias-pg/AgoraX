<?php
require_once("../controleur/ControleurInternaute.php");
require_once("../controleur/ControleurProposition.php");
require_once("../controleur/ControleurRole.php");
require_once("../controleur/ControleurGroupe.php");
require_once("../controleur/ControleurCommentaire.php");
require_once("../controleur/ControleurVote.php");
require_once("../controleur/ControleurTheme.php");
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['NumGroupe'])) {
    header('Location: ./../index.php');
    exit;
}

$numGroupe = $_GET['NumGroupe'];
$propositions = ControleurProposition::getPropositionsParGroupe($numGroupe);
$votes = ControleurVote::getVotesParGroupe($numGroupe);
$membres = ControleurInternaute::getMembresDuGroupe($numGroupe);
$groupe = ControleurGroupe::getGroupe($numGroupe);
$nomGroupe = $groupe->get("NomGroupe");
$couleur = $groupe->get('CouleurGroupe');
$role = ControleurRole::getRoleParInternauteEtGroupe($_SESSION['user']['NumInternaute'], $numGroupe);
$nomRole = $role->get('NomRole');

$emojis = [
    '😊', '😂', '🤣', '😃', '😄', '😁', '😆', '😅', '😋', '😎', '🤓', '😍', '😘', '😗', '😙', '😚', 
    '😜', '😝', '😛', '🤩', '🥳', '😏', '😒', '🙄', '😬', '😔', '😪', '🤭', '🤫', '🤔', '😕', '😟', 
    '😲', '😳', '😧', '😨', '😰', '😥', '😓', '😩', '😢', '😭', '😤', '😡', '😠', '🤬', '🤯', '😱', 
    '😖', '😷', '🤒', '🤕', '🥺', '😣', '😌', '😇', '🤗', '😤', '😠', '😒', '😑', '🙄', '😒', '🤯', 
    '😳', '😎', '❤️', '👍', '🎉', '🔥', '🙌','👍', '👎', '👌', '✌️', '🤞', '🤙', '💪', 
    '👏', '🙏', '🎶', '✨', '🔥', '💡'
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Groupe</title>
    <link rel="stylesheet" href="../css/groupeDetails.css">
    <script src="js/lecture_commentaire.js" defer></script>
</head>
<body>
    <header style="background-color: <?= htmlspecialchars($couleur) ?>;">
        <a href="accueil.php" class="back-btn">&larr; Retour à l'accueil</a>
        <h1 class="group-name"><?= htmlspecialchars($nomGroupe) ?></h1>
    </header>

    <div class="main-content">
        <div class="left-panel">
            <h1>| Propositions</h1>
            <a href="./proposition/creation_proposition_formulaire.php?NumGroupe=<?php echo $numGroupe ?>&role=<?= $nomRole ?>" class="add-proposition-btn">
                <img src="./../image/proposition.png" alt="Ajouter une proposition">
            </a>
            <?php if (count($propositions) > 0): ?>
                <ul>
                    <?php foreach ($propositions as $proposition): ?>
                        <li class="proposition-item">
                            <?php
                                $auteurProposition = ControleurInternaute::getAuteurProposition($proposition->get('NumProposition'));
                                $prenomAuteur = $auteurProposition->get('PrenomInternaute');
                                $nomAuteur = $auteurProposition->get('NomInternaute');
                                $theme = ControleurTheme::getObjetById($proposition->get('NumTheme')); 
                                $nomTheme = $theme->get('NomTheme');
                            ?>
                            <div class="author">
                            <img src="./../image/profil.png" alt="Photo de profil">
                                <span class="nom"><?= htmlspecialchars($prenomAuteur . ' ' . $nomAuteur . " | Thème : " . $nomTheme) ?></span>
                            </div>
                            <h3><?= htmlspecialchars($proposition->get('TitreProposition')) ?></h3>
                            <p><?= htmlspecialchars($proposition->get('DescriptionProposition')) ?></p>

                            <a href="./signalement/formulaire_signalement.php?NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="signalement-link">
                                <img src="./../image/signalement.png" alt="Signaler une proposition">
                            </a>

                            <div class="proposition-actions">
                                <?php
                                    $like = ControleurProposition::likeOuPasProposition($_SESSION['user']['NumInternaute'], $proposition->get('NumProposition'));
                                    $dislike = ControleurProposition::dislikeOuPasProposition($_SESSION['user']['NumInternaute'], $proposition->get('NumProposition'));
                                    $vote = ControleurProposition::votePourOuPasProposition($_SESSION['user']['NumInternaute'], $proposition->get('NumProposition'));

                                    $totalLikes = ControleurProposition::getNombreLikes($proposition->get('NumProposition'));
                                    $totalDislikes = ControleurProposition::getNombreDislikes($proposition->get('NumProposition'));
                                    $pourcentageVotes = ControleurProposition::getPourcentageVotes($proposition->get('NumProposition'), $numGroupe);
                                    $nbCommentaires = ControleurProposition::getNombreCommentaires($proposition->get('NumProposition'));
                                ?>
                                
                                <div class="action-container">
                                    <button onclick="toggleComments(<?= $proposition->get('NumProposition') ?>)" class="comments-btn">
                                        <img src="./../image/commentaire.png" alt="Afficher les commentaires">
                                    </button>
                                    <span class="comments-count"><?= $nbCommentaires ?></span>
                                </div>

                                <div class="action-container">
                                    <?php if($like): ?>
                                        <a href="./proposition/like_proposition.php?NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="like-btn">
                                            <img src="./../image/like_vert.png" alt="Like">
                                        </a>
                                    <?php else: ?>
                                        <a href="./proposition/like_proposition.php?NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="like-btn">
                                            <img src="./../image/like.png" alt="Like">
                                        </a>
                                    <?php endif; ?>
                                    <span class="like-count"><?= $totalLikes ?></span>
                                </div>

                                <div class="action-container">
                                    <?php if($dislike): ?>
                                        <a href="./proposition/dislike_proposition.php?NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="dislike-btn">
                                            <img src="./../image/dislike_rouge.png" alt="Dislike">
                                        </a>
                                    <?php else: ?>
                                        <a href="./proposition/dislike_proposition.php?NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="dislike-btn">
                                            <img src="./../image/dislike.png" alt="Dislike">
                                        </a>
                                    <?php endif; ?>
                                    <span class="dislike-count"><?= $totalDislikes ?></span>
                                </div>

                                <div class="action-container">
                                    <?php if($vote): ?>
                                        <a href="./proposition/vote_proposition.php?NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="vote-btn">
                                            <img src="./../image/vote_vert.png" alt="Vote">
                                        </a>
                                    <?php else: ?>
                                        <a href="./proposition/vote_proposition.php?NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="vote-btn">
                                            <img src="./../image/vote_rouge.png" alt="Vote">
                                        </a>
                                    <?php endif; ?>
                                    <span class="vote-count"><?= $pourcentageVotes ?>%</span>
                                </div>
                                <a href="./vote/formulaire_ajouter_vote.php?NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="trigger-vote-btn">Déclencher un vote</a>
                            </div>
                            
                            <div id="comments-<?= $proposition->get('NumProposition') ?>" class="comments-section">
                                <ul>
                                    <?php
                                    $commentaires = ControleurCommentaire::getCommentairesParProposition($proposition->get('NumProposition'));
                                    if ($commentaires && is_array($commentaires)):
                                        foreach ($commentaires as $commentaire):
                                            $internaute = ControleurInternaute::getAuteurCommentaire($commentaire->get('NumCommentaire'));
                                            if ($internaute):
                                                $nomInternaute = $internaute->get('NomInternaute');
                                                $prenomInternaute = $internaute->get('PrenomInternaute');
                                                ?>
                                                <li class="comment-item">
                                                    <div class="comment-content">
                                                        <strong><?= htmlspecialchars($nomInternaute." ".$prenomInternaute." : ") ?></strong>
                                                        <?= htmlspecialchars($commentaire->get('Contenu') ?? '') ?>
                                                    </div>

                                                    <?php
                                                        $likeCommentaire = ControleurCommentaire::likeOuPasCommentaire($_SESSION['user']['NumInternaute'], $commentaire->get('NumCommentaire'));
                                                        $dislikeCommentaire = ControleurCommentaire::dislikeOuPasCommentaire($_SESSION['user']['NumInternaute'], $commentaire->get('NumCommentaire'));

                                                        $totalLikesCommentaire = ControleurCommentaire::getNombreLikes($commentaire->get('NumCommentaire'));
                                                        $totalDislikesCommentaire = ControleurCommentaire::getNombreDislikes($commentaire->get('NumCommentaire'));
                                                    ?>

                                                    <div class="comment-actions">
                                                        <div class="action-container-comments">
                                                            <?php if($likeCommentaire): ?>
                                                                <a href="./commentaire/like_commentaire.php?NumCommentaire=<?= $commentaire->get('NumCommentaire') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="like-btn">
                                                                    <img src="./../image/like_vert.png" alt="Like commentaire">
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="./commentaire/like_commentaire.php?NumCommentaire=<?= $commentaire->get('NumCommentaire') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="like-btn">
                                                                    <img src="./../image/like.png" alt="Like commentaire">
                                                                </a>
                                                            <?php endif; ?>
                                                            <span class="like-count-comments"><?= $totalLikesCommentaire ?></span>
                                                        </div>

                                                        <div class="action-container-comments">
                                                            <?php if($dislikeCommentaire): ?>
                                                                <a href="./commentaire/dislike_commentaire.php?NumCommentaire=<?= $commentaire->get('NumCommentaire') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="dislike-btn">
                                                                    <img src="./../image/dislike_rouge.png" alt="Dislike commentaire">
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="./commentaire/dislike_commentaire.php?NumCommentaire=<?= $commentaire->get('NumCommentaire') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="dislike-btn">
                                                                    <img src="./../image/dislike.png" alt="Dislike commentaire">
                                                                </a>
                                                            <?php endif; ?>
                                                            <span class="dislike-count-comments-member"><?= $totalDislikesCommentaire ?></span>
                                                        </div>

                                                        <a href="./signalement/formulaire_signalement.php?NumCommentaire=<?= $commentaire->get('NumCommentaire') ?>&NumProposition=<?= $proposition->get('NumProposition') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="signalement-link">
                                                            <img src="./../image/signalement.png" alt="Signaler un commentaire">
                                                        </a>
                                                    </div>
                                                </li>
                                                <?php
                                            endif;
                                        endforeach;
                                    else: ?>
                                        <li>Aucun commentaire pour cette proposition.</li>
                                    <?php endif; ?>
                                </ul>
                                <form method="GET" action="./commentaire/creation_commentaire.php">
                                    <input type="hidden" name="NumGroupe" value="<?= $numGroupe ?>">
                                    <input type="hidden" name="role" value="<?= $nomRole ?>">
                                    <input type="hidden" name="NumProposition" value="<?= $proposition->get('NumProposition') ?>">

                                    <div class="add-comment">
                                        <input id="commentaire-<?= $proposition->get('NumProposition') ?>" type="text" name="commentaire" placeholder="Ajoutez un commentaire...">
                                        <button type="button" onclick="toggleEmojiPicker(this)" class="emoji-btn">😀</button>
                                        <button class="publish-btn" type="submit">Publier</button>
                                    </div>
                                    <div class="emoji-picker hidden">
                                        <?php
                                        foreach ($emojis as $emoji) {
                                            echo "<span class='emoji' onclick=\"addEmoji('$emoji', this)\">$emoji</span>";
                                        }
                                        ?>
                                    </div>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucune proposition dans ce groupe.</p>
            <?php endif; ?>

            <h1>| Votes </h1>
            <?php if (count($votes) > 0): ?>
                <ul>
                    <?php foreach ($votes as $vote): ?>
                        <li class="vote-item">
                            <?php 
                            $proposition = ControleurProposition::getObjetById($vote->get('NumProposition'));
                            $typeScrutin = ControleurVote::getTypeScrutin($vote->get('NumTypeScrutin'));
                            $aVote = ControleurVote::verifierSiVoteDejaSoumis($_SESSION['user']['NumInternaute'], $vote->get('NumVote'));
                            ?>
                            <div class="vote-header">
                                <h3>Titre proposition : <?= htmlspecialchars($proposition->get('TitreProposition')) ?></h3>
                            </div>
                            <p>Description proposition : <?= htmlspecialchars($proposition->get('DescriptionProposition')) ?></p>
                            <p>Date de début du vote : <?= htmlspecialchars($vote->get('DateDebutVote')) ?></p>
                            <p>Durée du vote : <?= htmlspecialchars($vote->get('DureeVote')) ?></p>

                            <?php if ($vote->get('EnCours') == 0): ?>
                                <p class="vote-closed">Le vote est clôturé.</p>
                                <div class = "resultat">
                                    <?php if ($typeScrutin->get('NomTypeScrutin') == 'pour/contre'): ?>
                                        <?php $resultat = ControleurVote::resultatVotePourContre($vote->get('NumVote')); ?>
                                        <p><?= $resultat ?>
                                    <?php elseif ($typeScrutin->get('NomTypeScrutin') == 'oui/non'): ?>
                                        <?php $resultat = ControleurVote::resultatVoteOuiNon($vote->get('NumVote')); ?>
                                        <p><?= $resultat ?>
                                    <?php elseif ($typeScrutin->get('NomTypeScrutin') == 'majoritaire_simple'): ?>
                                        <?php $resultat = ControleurVote::resultatVoteMajoritaireSimple($vote->get('NumVote')); ?>
                                        <p><?= $resultat ?>
                                    <?php elseif ($typeScrutin->get('NomTypeScrutin') == 'majoritaire_liste'): ?>
                                        <?php $resultat = ControleurVote::resultatVoteMajoritaireJugement($vote->get('NumVote')); ?>
                                        <p><?= $resultat ?>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <?php if ($aVote): ?>
                                    <p class="vote-done">Vous avez déjà voté. Les résultats seront disponibles après la clôture.</p>
                                <?php else: ?>
                                    <form action="./vote/soumettre_vote.php" method="GET" class="form-vote">
                                        <input type="hidden" name="NumVote" value="<?= $vote->get('NumVote') ?>">
                                        <input type="hidden" name="typeScrutin" value="<?= $typeScrutin->get('NomTypeScrutin') ?>">
                                        <input type="hidden" name="NumGroupe" value="<?= $numGroupe ?>">
                                        <input type="hidden" name="role" value="<?= $nomRole ?>">

                                        <?php if ($typeScrutin->get('NomTypeScrutin') == 'pour/contre'): ?>
                                            <label><input type="radio" name="choix" value="pour" required> Pour</label>
                                            <label><input type="radio" name="choix" value="contre"> Contre</label>

                                        <?php elseif ($typeScrutin->get('NomTypeScrutin') == 'oui/non'): ?>
                                            <label><input type="radio" name="choix" value="oui" required> Oui</label>
                                            <label><input type="radio" name="choix" value="non"> Non</label>

                                        <?php elseif ($typeScrutin->get('NomTypeScrutin') == 'majoritaire_simple'): ?>
                                            <?php 
                                                $voteMajoritaireSimple = ControleurVote::getVoteMajoritaire($vote->get('NumVote'));
                                            ?>
                                            <label><input type="radio" name="choix" value="<?= $voteMajoritaireSimple->get('Choix1')?>" required> <?= $voteMajoritaireSimple->get('Choix1')?></label>
                                            <label><input type="radio" name="choix" value="<?= $voteMajoritaireSimple->get('Choix2')?>"> <?= $voteMajoritaireSimple->get('Choix2')?></label>
                                            <label><input type="radio" name="choix" value="<?= $voteMajoritaireSimple->get('Choix3')?>"> <?= $voteMajoritaireSimple->get('Choix3')?></label>

                                        <?php elseif ($typeScrutin->get('NomTypeScrutin') == 'majoritaire_liste'): ?>
                                            <?php 
                                                $voteMajoritaireListe = ControleurVote::getVoteMajoritaire($vote->get('NumVote'));
                                            ?>
                                            <label for="notation1"><?= $voteMajoritaireListe->get('Choix1')?> : </label>
                                            <select name="notation1" required>
                                                <option value="" disabled selected>Choisir une notation : </option>
                                                <option value="5">Très bien</option>
                                                <option value="4">Bien</option>
                                                <option value="3">Passable</option>
                                                <option value="2">Insuffisant</option>
                                                <option value="1">À rejeter</option>
                                            </select>
                                            <label for="notation2"><?= $voteMajoritaireListe->get('Choix2')?> : </label>
                                            <select name="notation2" required>
                                                <option value="" disabled selected>Choisir une notation : </option>
                                                <option value="5">Très bien</option>
                                                <option value="4">Bien</option>
                                                <option value="3">Passable</option>
                                                <option value="2">Insuffisant</option>
                                                <option value="1">À rejeter</option>
                                            </select>
                                            <label for="notation3"><?= $voteMajoritaireListe->get('Choix3')?> : </label>
                                            <select name="notation3" required>
                                                <option value="" disabled selected>Choisir une notation : </option>
                                                <option value="5">Très bien</option>
                                                <option value="4">Bien</option>
                                                <option value="3">Passable</option>
                                                <option value="2">Insuffisant</option>
                                                <option value="1">À rejeter</option>
                                            </select>
                                        <?php endif; ?>
                                        <button type="submit">Soumettre votre vote</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun vote en cours pour ce groupe.</p>
            <?php endif; ?>
        </div>

        <div class="right-panel">
            <h1>| Internautes du Groupe</h1>
            <ul class="member-list">
                <?php foreach ($membres as $membre): ?>
                    <?php
                    $role = ControleurRole::getRoleParInternauteEtGroupe($membre->get('NumInternaute'), $numGroupe);
                    ?>
                    <li class="member-item">
                        <div class="member-content">
                            <div class="member-text">
                                <strong><?= htmlspecialchars($membre->get('PrenomInternaute')) ?> <?= htmlspecialchars($membre->get('NomInternaute')) ?></strong>
                                <br>
                                <em>Rôle : <?= htmlspecialchars($role->get('NomRole')) ?></em>
                            </div>
                            <div class="member-infos">
                                <a href="./infosInternaute/infosInternaute.php?NumInternaute=<?= $membre->get('NumInternaute') ?>&NumGroupe=<?= $numGroupe ?>&role=<?= $nomRole ?>" class="info-link">
                                    <img src="./../image/info.png" alt="Info" class="info-icon">
                                </a>
                             </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>

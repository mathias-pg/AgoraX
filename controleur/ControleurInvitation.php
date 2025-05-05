
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class ControleurInvitation
{
    // Générer un lien pour un email existant
    public static function genererLienInvitation($email, $numGroupe, $role)
    {
        $baseUrl = "https://projets.iut-orsay.fr/saes3-dteixei/ProjetS3/vue/accepter_invitation.php";

        $lien = $baseUrl . "?email=" . $email . "&numGroupe=" . $numGroupe . "&role=" . $role;

        return $lien;
    }

    // Générer un lien pour un nouvel utilisateur
    public static function genererLienInscription($email, $numGroupe, $role)
    {
        $baseUrl = "https://projets.iut-orsay.fr/saes3-dteixei/ProjetS3/vue/compte/creation_compte_formulaire.php";

        $lien = $baseUrl . "?email=" . $email . "&numGroupe=" . $numGroupe . "&role=" . $role;

        return $lien;
    }

    // Envoyer l'invitation par email avec PHPMailer
    public static function envoyerEmailInvitation($email, $lien)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Remplacez par votre hôte SMTP
            $mail->SMTPAuth = true;
            $mail->Username = ''; // Votre adresse email
            $mail->Password = ''; // Votre mot de passe
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Expéditeur et destinataire
            $mail->setFrom('agorax.democraty@hostinger-tutorials.fr', 'AgoraX');
            $mail->addAddress($email);

            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = "Invitation pour rejoindre AgoraX";
            $mail->Body = "
                <p>Bonjour,</p>
                <p>Vous avez ete invite a rejoindre un groupe sur la plateforme <b>AgoraX</b>.</p>
                <p><a href='$lien'>Cliquez ici</a> pour accepter ou refuser l'invitation.</p>
                <p>Si vous ne reconnaissez pas cette invitation, ignorez simplement cet email.</p>
                <p>Cordialement,<br>L'équipe AgoraX.</p>
            ";
            $mail->AltBody = "Bonjour,\n\nVous avez ete invite a rejoindre un groupe sur la plateforme AgoraX.\n\n" .
                             "Cliquez sur le lien suivant pour accepter ou refuser l'invitation :\n$lien\n\n" .
                             "Si vous ne reconnaissez pas cette invitation, ignorez simplement cet email.\n\n" .
                             "Cordialement,\nL'équipe AgoraX.";

            // Envoi de l'email
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
            return false;
        }
    }
}

























/*
class ControleurInvitation
{

    public static function genererLienInvitation($email, $numGroupe, $role)
    {
        $baseUrl = "https://webdev.iut-orsay.fr/~mpetibo/Projet/ProjetS3/vue/accepter_invitation.php";

        $lien = $baseUrl . "?email=" . urlencode($email) 
                          . "&numGroupe=" . urlencode($numGroupe) 
                          . "&role=" . urlencode($role);

        return $lien;
    }


    public static function genererLienInscription($email, $numGroupe, $role)
    {
        $baseUrl = "https://webdev.iut-orsay.fr/~mpetibo/Projet/ProjetS3/vue/compte/creation_compte.php";

        $lien = $baseUrl . "?email=" . urlencode($email) 
                          . "&numGroupe=" . urlencode($numGroupe) 
                          . "&role=" . urlencode($role);

        return $lien;
    }


    public static function envoyerEmailInvitation($email, $lien)
    {
        $sujet = "Invitation à rejoindre un groupe";
        $message = "Bonjour,\n\nVous avez été invité à rejoindre un groupe.\n\nCliquez sur le lien suivant pour accepter ou refuser l'invitation :\n$lien\n\nCordialement.";
        $headers = "From: agorax.democraty@hostinger-tutorials.fr";

        return mail($email, $sujet, $message, $headers);
    }
}
*/
?>

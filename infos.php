<?php
include_once('header.php');
require_once('connection.php');

$message = false;

if (isset($_GET['id'])) {
    $infoId = $_GET['id'];
} else {
    // Rediriger vers une autre page si l'identifiant n'est pas spécifié
    header('Location: compte.php');
    exit();
}

// Requête SQL pour récupérer les détails de la voiture correspondante à partir de l'identifiant
$sql = "SELECT * FROM prets WHERE id = :infoId";
$query = $pdo->prepare($sql);
$query->bindParam(':infoId', $infoId);
$query->execute();

$info = $query->fetch();

if (!$info) {
    // Rediriger vers une autre page si la voiture n'existe pas
    header('Location: compte.php');
    exit();
}

$email = $info['mail_emp'];
$prenom = $info['prenom_emp'];
$nom = $info['nom_emp'];
$id_emp = $info['id_emp'];
$id_pre = $info['id_pre'];


// Inclusion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer-refus'])) {
    // Récupérer la raison du refus
    $raisonRefus = $_POST['raison'];

    // Envoyer l'e-mail avec la raison du refus
    if (!empty($raisonRefus)) {
        // Créer l'objet PHPMailer ici
        $mail = new PHPMailer();
        // Configuration de l'envoi via SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chlomo.freoua@gmail.com'; // Remplacez cette ligne par votre adresse email
        $mail->Password = 'lysvjszruhsufdxh'; // Remplacez cette ligne par votre mot de passe
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configuration de l'email
        $mail->setFrom('reponse@gmah-du-Raincy.fr', 'Ohr Meir');
        $mail->addAddress($email, $prenom." ".$nom);
        $mail->Subject = 'Vous avez reçu une réponse';
        $mail->isHTML(true);
        $mail->Body = "
            <meta charset='UTF-8'>
            <div style='font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5; text-align: center;'>
            <img src='https://usualcom.net/wp-content/uploads/2017/09/12364849-Planet-Earth-and-human-eye-Stock-Photo.jpg' width='50' height='50'>
            <p>Bonjour c'est le Gma'h du Raincy,</p>
            <p>Votre demande pour l'annonce a bien été envoyée au prêteur</p>
            <p>Malheureusement, celui-ci a refusé de vous prêter cet article</p>
            <p>La raison de ce refus est : $raisonRefus</p>
            </div>
            ";

        // Envoyer l'e-mail
        if (!$mail->send()) {
            echo 'Erreur lors de l\'envoi de l\'email : ' . $mail->ErrorInfo;
        } else {
            $message = true;
        }
    } else {
        echo 'Veuillez fournir une raison pour le refus.';
    }
}
?>

<style>
    .light {
        background-color: grey;
        font-weight: bolder;
        font-style: italic;
    }

    .largeur {
        width: 95%;
        margin-right: 2.5%;
        margin-left: 2.5%;
    }
</style>

<div class="card text-center largeur">
    <ul class="list-group list-group-flush">
        <li class="list-group-item light">Nom :</li>
        <li class="list-group-item"><?= $info['nom_emp'] ?></li>

        <li class="list-group-item light">Prenom :</li>
        <li class="list-group-item"><?= $info['prenom_emp'] ?></li>

        <li class="list-group-item light">Message :</li>
        <li class="list-group-item"><?= $info['message_emp'] ?></li>

        <li class="list-group-item light">Mail :</li>
        <li class="list-group-item"><?= $info['mail_emp'] ?></li>

        <li class="list-group-item light">Numéro de téléphone :</li>
        <li class="list-group-item"><?= $info['num_emp'] ?></li>
    </ul>
    <div class="card-footer">
    <a href="message.php?id_article=<?= $info['article_id']?>&id_emp=<?= $id_emp?>&id_pre=<?= $id_pre ?>"><button class="btn btn-success">Chatter avec l'emprunteur</button></a>
        <!-- Avertir le prêteur qu'un mail sera envoyé à l'emprunteur pour lui avertir de son refus -->
        <a href="refus.php?id_article=<?= $info['article_id']?>&id_emp=<?= $id_emp?>&id_pre=<?= $id_pre ?>"><button class="btn btn-danger">Refuser le pret</button></a>
                    
        <?php 
        if($message){
            echo'<p>Message de refus envoyé</p>';
        }
        ?>
        <form method="post" id="form-refuser">
            <label for="raison">Veuillez détailler la raison de votre refus</label>
            <textarea name="raison" id="raison" cols="30" rows="3"></textarea>
            <br>
            <button type="submit" name="envoyer-refus" id="envoyer-refus">Envoyer</button>
            <br>
            <span><i>En cliquant sur envoyer, l'emprunteur recevra un mail lui avertissant de votre refus avec la raison que vous avez renseignée</i></span>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('#form-refuser').hide();
        $('#refuser').click(function(){
            $('#form-refuser').show();
        });

        $('#envoyer-refus').click(function(){
           alert('Message de refus envoyé')
        });
    });
</script>

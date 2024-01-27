<?php
// Inclusion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require_once 'PHPMailer-master\PHPMailer-master\src\SMTP.php';

//Mail et nom 
$adressemail = $_POST["email"];
$nom = $_POST["name"];
$prenom = $_POST["firstname"];

// Création d'une instance de PHPMailer
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
$mail->setFrom('inscription@gmahorhmeir.fr', 'Ohr Meir'); // Remplacez cette ligne par votre adresse email et votre nom
$mail->addAddress($adressemail, $prenom." ".$nom);
$mail->Subject = 'Inscription';
$mail->isHTML(true);
$mail->Body = "
<div style='font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5; text-align: center;'>
    <img src='https://usualcom.net/wp-content/uploads/2017/09/12364849-Planet-Earth-and-human-eye-Stock-Photo.jpg width: 50; height: 50;>
    <p>Bonjour et bienvenue au Gma'h Ohr Meir, $prenom!</p>
    <p>Nous vous confirmons votre inscription chez nous.</p>
    <p>Nous espérons que vous serez ravi de votre expérience et que vous nous aiderez à avancer dans le hessed authentique.</p>
    <p>Si vous avez besoin de quoi que ce soit ou si même vous souhaitez nous aider, n'hésitez pas à envoyer un mail à <a href='mailto:gmahkahilat@gmail.com'>gmahkahilat@gmail.com</a>.</p>
    <p>Merci pour tout!</p>
</div>
";


// Envoi de l'email
if($mail->send()) {
    echo 'Un mail de confirmation vient de vous être envoyé !';
} else {
    echo 'Une erreur est survenue lors de l\'envoi de l\'e-mail : '.$mail->ErrorInfo;
}
?>

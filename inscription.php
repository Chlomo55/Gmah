<?php
session_start();
require_once('connection.php'); // Assurez-vous que ce fichier contient les informations de connexion à la base de données

// Inclusion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

// Vérification si le formulaire a bien été envoyé
if (!empty($_POST)) {
    // Vérification que tous les champs requis sont remplis
    if (isset($_POST["name"], $_POST["firstname"], $_POST["email"], $_POST["pass"]) && !empty($_POST["name"]) && !empty($_POST["firstname"]) && !empty($_POST["email"]) && !empty($_POST["pass"])) {
        // Formulaire complet

        // Protection des données
        $nom = strip_tags($_POST["name"]);
        $prenom = strip_tags($_POST["firstname"]);
        $email = $_POST["email"];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Adresse mail invalide");
        }

        // Hashage du mot de passe
        $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);

        // Enregistrement en BDD
        $sql = "INSERT INTO `users` (`nom`, `prenom`, `mail`, `pass`) VALUES (:name, :firstname, :email, :pass)";
        $query = $pdo->prepare($sql);
        $query->bindValue(":name", $nom, PDO::PARAM_STR);
        $query->bindValue(":firstname", $prenom, PDO::PARAM_STR);
        $query->bindValue(":email", $email, PDO::PARAM_STR);
        $query->bindValue(":pass", $pass, PDO::PARAM_STR);

        if($query->execute()){
            // Récupération de l'id de l'utilisateur
            $id = $pdo->lastInsertId();

            // Stockage des informations dans $_SESSION
            $_SESSION["user"] = [
                "id" => $id,
                "name" => $nom,
                "prenom" => $prenom,
                "email" => $email,
            ];

            // Envoi de l'email
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
            $mail->setFrom('inscription@gmah-du-Raincy.fr', 'Ohr Meir'); // Remplacez cette ligne par votre adresse email et votre nom
            $mail->addAddress($email, $prenom." ".$nom);
            $mail->Subject = 'Inscription';
            $mail->isHTML(true);
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5; text-align: center;'>
            <img src='https://usualcom.net/wp-content/uploads/2017/09/12364849-Planet-Earth-and-human-eye-Stock-Photo.jpg width: 50; height: 50;>
            <p>Bonjour et bienvenue au Gma'h du Raincy, $prenom!</p>
            <p>Nous vous confirmons votre inscription chez nous.</p>
            <p>Vous pouvez dès à présent vous connecter <a href='https://07d2-62-35-85-52.ngrok-free.app/Gmah_du_raincy/connexion.php'>ici</a><p>
            <p>Nous espérons que vous serez ravi de votre expérience et que vous nous aiderez à avancer dans le hessed authentique.</p>
            <p>Si vous avez besoin de quoi que ce soit ou si même vous souhaitez nous aider, n'hésitez pas à envoyer un mail à <a href='mailto:gmahkehilat@gmail.com'>gmahkehilat@gmail.com</a>.</p>
            <p>Merci pour tout!</p>
            </div>
            ";
            if(!$mail->send()) {
                echo 'Erreur lors de l\'envoi de l\'email : '.$mail->ErrorInfo;
            } else {
                echo 'Un mail de confirmation a été envoyé.';
                // Redirection
                header('Location: compte.php');
                exit;
            }
        } else {
            echo 'Erreur lors de l\'inscription.';
        }
    }
}

include_once('header.php');
?>


<style>
.eye-icon {
position: absolute;
top: 63%;
right: 10px;
transform: translateY(-50%);
cursor: pointer;
height: 20px;
color: #928E8E;
}
</style>
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post" id="myForm" enctype="multipart/form-data" class="p-4 border rounded">
                    <h1 class="h3 mb-3 text-center">Inscription</h1>

                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" name="name" class="form-control" id="nom" required>
                    </div>

                    <div class="form-group">
                        <label for="firstname">Prénom</label>
                        <input type="text" name="firstname" class="form-control" id="prenom" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse mail</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                    </div>

                    <div class="form-group position-relative">
                        <label for="pass">Mot de passe</label>
                        <input type="password" class="form-control" name="pass" id="pass" required>
                        <span class="eye-icon" onclick="togglePasswordVisibility()">
                            <i data-feather="eye"></i>
                        </span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- jQuery -->
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        feather.replace();

        function togglePasswordVisibility() {
            let passInput = document.getElementById('pass');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                feather.replace({ 'eye': 'eye-off' });
            } else {
                passInput.type = 'password';
                feather.replace({ 'eye-off': 'eye' });
            }
        }
    </script>
<?php include_once('footer.php') ?>



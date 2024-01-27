<?php
ob_start();
session_start();
require_once('header.php');
require_once('connection.php');

// Inclusion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require_once 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/PHPMailer-master/src/SMTP.php'; // Assurez-vous que ce chemin est correct

// Fonction pour obtenir le code du département en fonction du nom
function getCodeDepartement($nomDepartement, $pdo) {
    $stmt = $pdo->prepare('SELECT departement_code FROM departement WHERE departement_nom_uppercase = :nom');
    $stmt->bindParam(':nom', $nomDepartement);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Fonction pour obtenir le nom du département en fonction du code
function getNomDepartement($codeDepartement, $pdo) {
    $stmt = $pdo->prepare('SELECT departement_nom_uppercase FROM departement WHERE departement_code = :code');
    $stmt->bindParam(':code', $codeDepartement);
    $stmt->execute();
    return $stmt->fetchColumn();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['title']) && !empty($_POST['details'])
        && !empty($_POST['mail']) && !empty($_POST['num']) && !empty($_FILES['photo'])
        && !empty($_POST['departement'])
    ) {
        $titre = $_POST['title'];
        $details = $_POST['details'];
        $caution = $_POST['caution'];
        $userEmail = $_POST['mail'];
        $num = $_POST['num'];
        if (isset($_FILES['photo']['tmp_name']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
            $photo = file_get_contents($_FILES['photo']['tmp_name']);
        } else {
            // Gérez le cas où aucun fichier n'a été téléchargé
            echo 'Aucun fichier n\'a été téléchargé.';
            // Vous pouvez également rediriger l'utilisateur vers une page d'erreur ou faire autre chose selon vos besoins.
            exit;
        }        $nomDepartement = $_POST['departement'];

        // Récupérer le code du département
        $codeDepartement = getCodeDepartement($nomDepartement, $pdo);

        // Insérer l'annonce avec le code du département
        $insertStmt = $pdo->prepare('INSERT INTO articles (nom, details, departement, caution, `mail`, num, `image`) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $insertStmt->bindParam(1, $titre);
        $insertStmt->bindParam(2, $details);
        $insertStmt->bindParam(3, $codeDepartement);
        $insertStmt->bindParam(4, $caution);
        $insertStmt->bindParam(5, $userEmail);
        $insertStmt->bindParam(6, $num);
        $insertStmt->bindParam(7, $photo, PDO::PARAM_LOB);

        if ($insertStmt->execute()) {
            // Envoi de l'email avec PHPMailer
            $mailer = new PHPMailer(true);

            try {
                // Configuration de l'envoi
                $mailer->isSMTP();
                $mailer->Host = 'smtp.gmail.com';
                $mailer->SMTPAuth = true;
                $mailer->Username = 'chlomo.freoua@gmail.com'; // Votre adresse email
                $mailer->Password = 'lysvjszruhsufdxh'; // Votre mot de passe
                $mailer->SMTPSecure = 'tls';
                $mailer->Port = 587;

                // Configuration de l'email
                $mailer->setFrom('inscription@gmah-du-Raincy.fr', 'Ohr Meir');
                $mailer->addAddress($userEmail);

                // Contenu de l'e-mail
                $mailer->isHTML(true);
                $mailer->Subject = 'Confirmation de votre demande de dépôt';
                $mailer->Body = "
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f6f6f6;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .email-content {
            padding: 20px;
        }
        .email-footer {
            text-align: center;
            padding-top: 20px;
            font-size: 0.8em;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='email-header'>
            <h2>Ohr Meir</h2>
        </div>
        <div class='email-content'>
            <p>Bonjour,</p>
            <p>Votre demande avec les détails suivants a été reçue :</p>
            <p><b>Titre:</b> $titre</p>
            <p><b>Détails:</b> $details</p>
            <p><b>Numéro:</b> $num</p>
            <p>Votre demande va être traitée. Vous recevrez un mail une fois celle-ci validée.</p>
        </div>
        <div class='email-footer'>
            <p>Merci de nous avoir contactés.</p>
            <p>© 2023 Ohr Meir</p>
        </div>
    </div>
</body>
</html>";

                $mailer->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mailer->ErrorInfo}";
            }

            header('Location: confirm.php');
            exit;
        }
    }
}
?>

<style>
    label {
        font-weight: 500;
        padding-bottom: 10px;
    }
</style>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <form method="POST" id="formContact" enctype="multipart/form-data" class="text-center">
                <div class="form-row">
                    <div class="form-group mb-4">
                        <label for="title">Titre :</label>
                        <input type="text" class="form-control" name="title" required="">
                    </div>
                    <div class="form-group mb-4">
                        <label for="details">Détails :</label>
                        <textarea class="form-control" name="details" rows="4" required=""></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group mb-4">
                        <label for="caution">Caution :</label>
                        <input type="number" class="form-control" name="caution" required="" placeholder="Montant de la caution que vous désirez demander">
                    </div>
                    <div class="form-group mb-4">
                        <label for="departement">Votre département :</label>
                        <select class="form-control" name="departement" required="">
                            <?php
                            $departementsQuery = $pdo->query('SELECT departement_code, departement_nom_uppercase FROM departement');
                            while ($departement = $departementsQuery->fetch(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?= $departement['departement_code']?>"><?= $departement['departement_code'].'-'.$departement['departement_nom_uppercase']?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group mb-4">
                        <label for="mail">Adresse mail :</label>
                        <input type="email" class="form-control" name="mail" placeholder="Email" required="">
                    </div>
                    <div class="form-group mb-4">
                        <label for="num">Téléphone :</label>
                        <input type="tel" class="form-control" name="num" placeholder="Numéro de téléphone" required="">
                    </div>
                </div>
                <div class="form-group my-2">
                    <label for="photo" class="my-2 text-center">Ajoutez une photo :</label>
                    <input type="file" name="photo">
                </div>
                <button type="submit" class="btn btn-success my-3">Demander l'ajout</button>
            </form>
        </div>
    </div>
</div>

<?php require_once('footer.php') ?>

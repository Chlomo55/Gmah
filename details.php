<?php
ob_start();
session_start();
 require_once('header.php');
 require_once('connection.php');


// Récupérer l'identifiant de la voiture depuis l'URL
if (isset($_GET['id'])) {
    $articleId = $_GET['id'];
} else {
    // Rediriger vers une autre page si l'identifiant n'est pas spécifié
    header('Location: index.php');
    exit();
}

// Requête SQL pour récupérer les détails de la voiture correspondante à partir de l'identifiant
$sql = "SELECT * FROM articles WHERE id = :articleId";
$query = $pdo->prepare($sql);
$query->bindParam(':articleId', $articleId);
$query->execute();

$article = $query->fetch();

if (!$article) {
    // Rediriger vers une autre page si la voiture n'existe pas
    header('Location: index.php');
    exit();
};

$mail_preteur = $article['mail'];
$imageData = $article['image'];
$titre = $article['nom'];
$details = $article['details'];

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $nom_emp = $_POST['name'];
$prenom_emp = $_POST['firstname'];
$mail_emp = $_POST['mail'];
$num_emp = $_POST['tel'];
$message_emp = $_POST['message'];

$id_emprunteur = $pdo->prepare('SELECT id FROM users WHERE mail = :mail_emp');
$id_emprunteur->bindParam(':mail_emp', $mail_emp);
$id_emprunteur->execute();
$row_emprunteur = $id_emprunteur->fetch(PDO::FETCH_ASSOC);
$id_emp = $row_emprunteur['id'];

$id_preteur = $pdo->prepare('SELECT id FROM users WHERE mail = :mail_preteur');
$id_preteur->bindParam(':mail_preteur', $mail_preteur);
$id_preteur->execute();
$row_preteur = $id_preteur->fetch(PDO::FETCH_ASSOC);
$id_pre = $row_preteur['id'];

// Maintenant, vous avez $id_emp contenant l'ID de l'emprunteur
// et $id_pre contenant l'ID du prêteur

$insertSQL = 'INSERT INTO prets (`article_id`, `mail_preteur`, `id_pre`, `id_emp`, `nom_emp`, 
`prenom_emp`, `mail_emp`, `num_emp`, `message_emp`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

$insertPrets = $pdo->prepare($insertSQL);
$insertPrets->bindParam(1, $articleId);
$insertPrets->bindParam(2, $mail_preteur);
$insertPrets->bindParam(3, $id_pre);
$insertPrets->bindParam(4, $id_emp);
$insertPrets->bindParam(5, $nom_emp);
$insertPrets->bindParam(6, $prenom_emp);
$insertPrets->bindParam(7, $mail_emp);
$insertPrets->bindParam(8, $num_emp);
$insertPrets->bindParam(9, $message_emp);
$insertPrets->execute();
}
?>



<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">        
        <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3 text-center"><?= $article['nom']?></h1>

      <div class="col-10 col-sm-8 col-lg-6">
        <img src="data:image/jpeg;base64,
            <?php echo base64_encode($imageData); ?>"
             alt="Article" id="image-details" class="d-block mx-lg-auto img-fluid">
      </div>
      <div class="col-lg-6">
        <p class="lead"><?= $article['details']?></p>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
          <button type="button" class="btn btn-primary btn-lg px-4 me-md-2" id="buttonShow">Je suis intéressé</button>
        </div>

        <!-- FORM -->

        <div class="col-lg-6 col-md-8 col-sm-12" id="divForm">
        <form method="post" class="text-center">
        <div class="form-row">
          <div class="form-group mb-4">
            <label for="name">Nom</label>
            <input type="text" class="form-control" name="name">
          </div>
          <div class="form-group mb-4">
            <label for="firstname">Prénom</label>
            <input type="text" class="form-control" name="firstname">  
          </div>
        </div>
        <div class="form-row">
          <div class="form-group mb-4">
            <label for="mail">Adresse mail</label>
            <input type="email" class="form-control" name="mail">  
          </div>
          <div class="form-group mb-4">
            <label for="tel">Numéro de téléphone</label>
            <input type="tel" class="form-control" name="tel">   
          </div>
        </div>
        <div class="form-row">
          <div class="form-group mb-4">
            <label for="message">Détaillez votre demande</label>
            <textarea name="message" placeholder="Veuillez aussi ajouter les dates que vous souhaiter"></textarea>          </div>
        </div>
        <button type="submit" class="btn btn-success my-3">Envoyer</button>
        </form>
        </div>
        
      </div>
    </div>
  </div>
<?php require_once('footer.php');?>
  <style>
    .noms{
        text-align: center;
        width: 49%;
    }
    .border-div{
        margin: 5%;
    }
    #image-details{ 
        height: 180px;
        width: 200px;
        margin-left: 20%;
        margin-right: 20%;
        border-radius: 15px;
    }
  </style>
  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(() => {
            $('#divForm').hide();

            $('#buttonShow').click(function(){
                $('#divForm').fadeIn(500);
                $('#buttonShow').hide();
            });
        });
    </script>
<?php
ob_start();
session_start();

include_once('header.php');
require_once('connection.php');

if (!$_SESSION['user']) {
    header('Location: index.php');
}

// Assurez-vous que l'utilisateur est connecté
if (isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];

    // Préparez la requête SQL
    $sql_users = "SELECT * FROM users WHERE mail = :email";

    // Préparation de la requête
    $stmt = $pdo->prepare($sql_users);

    // Liaison des paramètres
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Exécution de la requête
    $stmt->execute();

    // Récupération des données
    $dataUser = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
<!-- faudrait récuperer l'id de session qui est aussi celle de l'emprunteur, l'id de l'article, et du preteur -->
    <div>
        <h4>Notifications</h4>
        <?php include_once('notif.php');?>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="text-center">
                <!-- Détails de l'utilisateur -->
                <h2 id="nomPrenom"><?= htmlspecialchars($dataUser['prenom']) . ' ' . htmlspecialchars($dataUser['nom']); ?></h2>
                <!-- Autres informations de l'utilisateur -->
                <p>Email: <?= htmlspecialchars($dataUser['mail']); ?></p>
                <!-- Boutons -->
                <div>
                    <button class="btn btn-primary my-4">
                        <a href="modifier_informations.php" style="text-decoration: none; color: #fff;">Modifier mes informations</a>
                    </button>
                    <button class="btn btn-secondary">
                        <a href="ajouter_article.php" style="text-decoration: none; color: #fff;">Déposer une annonce</a>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

<h3 class="text-center my-3">Vos annonces</h3>

<style>
    .img_article {
        height: 200px;
        width: 200px;
        margin-left: auto;
        margin-right: auto;
        border-radius: 15px;
    }

    @media screen and (min-width: 768px) {
        .my-4 {
            margin-left: 10%;
            margin-right: 10%;
        }
    }
</style>

<?php
$sql_articles = "SELECT * FROM articles WHERE mail = :email";

$query = $pdo->prepare($sql_articles);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$trouve = false;

while ($annonce = $query->fetch(PDO::FETCH_ASSOC)) {
    $trouve = true;
    $imageAnnonce = base64_encode($annonce['image']);
    $date = new DateTime($annonce['date']);
    $articleId = $annonce['id'];
    ?>

    <div class="container">
        <div class="card text-center my-4">
            <div class="card-header">
                <h5>Titre: <?= $annonce['nom'] ?></h5>
            </div>
            <div class="card-body">
        <div>
            <img src="data:image/jpeg;base64,<?= $imageAnnonce ?>" class="card-img-center img_article mb-4" alt="Coussin de Brit Mila">
        </div>
        <p class="card-text"><strong>Détails:</strong> <?= $annonce['details'] ?></p>
        <p><strong>Statut de l'annonce :</strong>
            <?php
            switch ($annonce['approuve']) {
                case 0:
                    echo 'En attente';
                    break;
                case 1:
                    echo 'Approuvé';
                    break;
                case 2:
                    echo 'Refusé à cause de';
                    break;
            }
            ?>
        </p>
        <p class="card-text"><strong>Envoyé le : </strong><?= $date->format('d/m/Y à H:i') ?></p>
        <br>
        <?php 
        $affich = $pdo->prepare('SELECT COUNT(*) as lignes FROM prets WHERE article_id = :id_article');
        $affich->bindParam(':id_article', $articleId, PDO::PARAM_STR);
        $affich->execute();

        $lignes = $affich->fetch(PDO::FETCH_ASSOC);?>
        <p><?= $lignes['lignes']?> demande(s) concernant cette article</p>

        <?php
        if($lignes['lignes'] >= 1){ 
            echo'<button id="button_ligne">Afficher</button>';
        }
        $demande = $pdo->prepare('SELECT * FROM prets WHERE article_id = :id_article AND mail_preteur = :mail');
        $demande->bindParam(':mail', $email, PDO::PARAM_STR);
        $demande->bindParam(':id_article', $articleId, PDO::PARAM_STR);
        $demande->execute();
        ?>

<div class="table-responsive" id="tableau">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Message</th>
                <th>Détails</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($demande as $e) {
                // Si le message est vide alors afficher aucun message trouvé sinon afficher le message
                if (empty($e['message_emp'])) {
                    echo '<p>Aucun message trouvé</p>';
                } else {
                    ?>
                    <tr>
                        <td><?= $e['nom_emp'] ?></td>
                        <td><?= $e['prenom_emp'] ?></td>
                        <td><?= $e['message_emp'] ?></td>
                        <td><a href='infos.php?id=<?= $e['id']?>'>Détails</a></td>
                    </tr>
                <?php } } ?>
        </tbody>
    </table>
</div>

                <br>
                <a href="#" class="btn btn-warning" id="button-show">Modifier ou supprimer</a>
                <div id="div-hide">
                    <form method="post">
                        <select name="" id="">
                            <option value="modifier-titre">Modifier le titre</option>
                            <option value="modifier-details">Modifier les détails</option>
                            <option value="modifier-mail">Modifier le mail</option>
                            <option value="modifier-num">Modifier le numéro de téléphone</option>
                            <option value="supprimer">Supprimer</option>
                        </select>
                        <br>
                        <textarea name="" id="" cols="30" rows="10" placeholder="Détaillez votre démarche" required=""></textarea>
                        <br>
                        <button id="envoyer">Envoyer</button>
                    </form>
                </div>
            </div>
            <div class="card-footer text-body-secondary">
                Pour préserver le site, toute modification ou suppression n'est pas immédiate
            </div>
        </div>
    </div>

<?php } ?>

<?php
if (!$trouve) {
    echo "<div class='container mt-4 btn btn-danger' style='width: 60%; margin-right: 20%; margin-left: 20%;'>
        <h5 class='card-text text-center my-3'>Aucune annonce trouvée avec le mail $email </h5>
        </div>";
}

include_once('footer.php');
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        $('#div-hide').hide();
        $('#envoyer').hide();

        $('#button-show').click(function () {
            $('#div-hide').show();
            $('#button-show').hide();
            $('#envoyer').show();
        })
        //Masque le tableau et l'affiche au clic sur le bouton
        $('#tableau').hide();
        $('#button_ligne').click(function(){
            $('#tableau').show();
            $('#button_ligne').hide();
        })
    })
</script>

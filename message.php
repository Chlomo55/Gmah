<?php 
session_start();
include_once('header.php');
require_once('connection.php');

// On récupére l'id de la session
$id_session = $_SESSION['user']['id'];

// On récupère l'id de l'article
$id_article = $_GET['id_article'];

// On récupère l'id de l'emprunteur
$id_emp = $_GET['id_emp'];

// On récupère l'id du preteur
$id_preteur = $_GET['id_pre'];

// Définir la valeur de $getid
$getid = $id_article;

// Récupérer l'id du prêteur chacun déjà stocké dans la table prets 
$recups = $pdo->prepare('SELECT * FROM prets WHERE article_id = :id');
$recups->bindParam(':id', $getid);
$recups->execute();

foreach($recups as $r){
    $libre = $r['libre'];
}


//On insert dans la table message le message, 
//l'id de l'article concerné et celui du preteur et de l'emprunteur

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = $_POST['message'];
    if(!empty($message)){
    $insertMessage = $pdo->prepare('INSERT INTO `message`(`message`, `id_article`, `id_preteur`, `id_emprunteur`, `id_session`) VALUES (?, ?, ?, ?, ?)');
    $insertMessage->bindParam(1, $message);
    $insertMessage->bindParam(2, $id_article);
    $insertMessage->bindParam(3, $id_preteur);
    $insertMessage->bindParam(4, $id_emp);
    $insertMessage->bindParam(5, $id_session);
    $insertMessage->execute();
    } else{
        echo 'Veuillez écrire un message';
    }
    
}


//On affiche les derniers messages qui sont dans la table message
//Et qui ont le meme id_article, le meme id_pre et le meme id_emp
//Car plusieurs articles peuvent être dans les messages
//Et plusieurs personnes peuvent s'intérresser à cet article

//Déja il faudrait regrouper tous les utilisateurs intérréssé par cet article
//Et le preteur en cliquant sur un des emprunteurs sera rediriger vers les messages entre eux 2


$messages = $pdo->prepare('SELECT * FROM `message` WHERE id_article = :id_article 
AND id_preteur = :id_preteur AND id_emprunteur = :id_emprunteur');
$messages->bindParam(':id_article', $id_article);
$messages->bindParam(':id_preteur', $id_preteur);
$messages->bindParam(':id_emprunteur', $id_emp);
$messages->execute(); 

$titre = $pdo->prepare('SELECT * FROM articles WHERE id = :id_article');
$titre->bindParam(':id_article', $id_article);
$titre->execute();
foreach($titre as $t){
     $nom = $t['nom'];
}
?>
<style>
    .body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        display: flex;
        justify-content: center;
    }

    .container-msg {
        width: 100%;
        max-width: 600px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
        height: fit-content;
    }

    .message-container {
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
    }

    .message {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        word-wrap: break-word;
        max-width: 80%;
       
    }

    .emp {
        background-color: #4CAF50;
        color: #fff;
        border-radius: 10px;
        margin-left: 400px;
        width: fit-content;
    }
    @media screen and (max-width: 800px) {
        .emp{
            margin-left: 250px;
        }
    }

    .preteur {
        background-color: #0084FF;
        color: #fff;
        border-radius: 10px;
        margin-right: 10px;
        width: fit-content;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px;
    }

    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: none;
    }

    .button-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
    }
</style>
  <h3 class="text-center">Message pour <?= $nom?></h3>
  <div class="body">

 
<div class="container-msg">
  

    <div class="message-container">
        <?php 
        foreach($messages as $recents){
            if($recents['id_article'] === $id_article 
            && $recents['id_preteur'] === $id_preteur
            && $recents['id_emprunteur'] === $id_emp){ ?>
                <?php if($recents['id_session'] === $id_emp){ ?>
                <p class="message emp"><?=$recents['message'] ?></p> 
                <?php 
                } else { ?>
                <p class="message preteur"><?=$recents['message'] ?></p> 
                <?php } ?>
            <?php 
            }
        }
        ?>
    </div>

    <form method="post">
        <textarea name="message" placeholder="Écrivez votre message ici..." rows="3"></textarea>
                    <button type="submit"><img src="envoyer.png" width="25" height="25"></button>

        <div class="button-container">
            <?php
            
            if($libre == 1){ ?>
            <button class="btn btn-success">Conclure le prêt</button>
            <button>Signaler le prêt</button>
            <?php }
            else{ ?>
            <button type="button" id="commencer-pret">Commencer le pret </button>
            <a href="refus.php?id_article=<?= $id_article?>&id_emp=<?= $id_emp?>&id_pre=<?= $id_preteur ?>" class="btn btn-danger">Refuser le pret</a>
            <?php 
            }
            ?>
            <br>
            <div id="approuver">
                <input type="checkbox" name="caution" id="caution">
                <p>Je reconnais que l'article a bien été prété et je m'engage à le restituer à la date convenu</p>
            </div>
        </div>
    </form>
</div>
 </div>
 <?php include_once('footer.php')?>
 <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Récupérez l'élément de la liste des messages
        var messageContainer = document.querySelector('.message-container');

        // Faites défiler jusqu'au bas de la liste des messages
        messageContainer.scrollTop = messageContainer.scrollHeight;
    });
    // $(document).ready(() =>{
    //     $('#approuver').hide();
    //     $('#commencer-pret').click(function(){
    //         $('#approuver').show();
    //         console.log('Apparait');
    //         $('.button-container').hide();
    //     })
    // })
</script>

 
<?php 
// Assurez-vous que la session est démarrée
// ob_start();
// session_start();

require_once('connection.php');

$id_session = $_SESSION['user']['id'];

// Sélectionnez le dernier message de la conversation
$lastMessageQuery = $pdo->prepare('SELECT * FROM message WHERE id_emprunteur = :id ORDER BY date_envoi DESC LIMIT 1');
$lastMessageQuery->bindParam(':id', $id_session);
$lastMessageQuery->execute();

// Vérifiez s'il y a des messages
if ($lastMessageQuery->rowCount() > 0) {
    $lastMessage = $lastMessageQuery->fetch();

    // Récupérez les informations du dernier message
    $id_emp = $lastMessage['id_emprunteur'];
    $id_pre = $lastMessage['id_preteur'];
    $id_article = $lastMessage['id_article'];
    $message = $lastMessage['message'];
    $messageId = $lastMessage['id'];  // Utilisez l'ID du dernier message

    // Récupérez le titre de l'article associé à l'id
    $title = $pdo->prepare('SELECT nom FROM articles WHERE id = :id');
    $title->bindParam(':id', $id_article);
    $title->execute();
    $nom = $title->fetch()['nom'];

    // Affichez la notification du dernier message
    if ($_SESSION['user']['id'] === $id_emp) {
        ?>
        <div>
            <h3><?= $nom ?></h3>
            <p><?= $message ?></p>
            <a href="message.php?id_article=<?= $id_article?>&id_emp=<?= $_SESSION['user']['id']?>&id_pre=<?= $id_pre?>">Voir</a>
        </div>
        <?php 
    }
} else {
    // Aucun message
    echo 'Aucun message';
}
?>


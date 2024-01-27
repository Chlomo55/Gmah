<?php
session_start();

include_once('header.php');
require_once('connection.php');

$id_article = $_GET['id_article'];
$id_emp = $_GET['id_emp'];
$id_pre = $_GET['id_pre'];



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!empty($_POST['raison'])){
        $raison = $_POST['raison'];
        $refus = $pdo->prepare('INSERT INTO refus (id_pre, id_emp, 
        id_article, raison) VALUES(:id_pre, :id_emp, :id_article, :raison)');
        $refus->bindParam(':id_pre', $id_pre);
        $refus->bindParam(':id_emp', $id_emp);
        $refus->bindParam(':id_article', $id_article);
        $refus->bindParam(':raison', $raison);
        $refus->execute();
    } else{
        echo 'Veuillez renseigner la raison';
    }
}
?>

<form method='post' class="text-center">
    <label for="raison">Détaillez la raison </label>
    <br>
    <textarea name="raison" id="raison" cols="20" rows="2"></textarea>
    <br>
    <button type="submit">Envoyer</button>
</form>


<?php 
include_once('footer.php');
?>
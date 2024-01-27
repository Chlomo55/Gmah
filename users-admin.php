<!-- Il faut pouvoir afficher tous les users et pour chacun modifier ou supprimer -->

<?php 
include_once('header.php');
include_once('connection.php');

$sql = 'SELECT * FROM users';

$users = $pdo->query($sql);
?>

<div class="container">
    <h3 class="text-center">Liste de tous les utilisateurs</h3>
    <div class="row text-center">
        <?php
        foreach($users as $user){ 
            ?>
           <div class="row">
  <div class="col-sm-6 mb-3 mb-sm-0">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title"><?= $user['nom'].' '.$user['prenom'] ?></h5>
        <p class="card-text">Adresse mail: <?= $user['mail']?></p>
        <a href="#" class="btn btn-warning">Modifier</a>
        <a href="#" class="btn btn-danger">Supprimer</a>

      </div>
    </div>
  </div>
           </div>
           
        <?php } 
        ?>
    </div>
</div>


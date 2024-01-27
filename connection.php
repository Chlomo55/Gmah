<?php 

// Connexion entre le PHP et la BDD
// PDO est le pont entre le php et la base de donnée
try{
$pdo = new PDO('mysql:host=localhost;dbname=gmah', 'root', '');   

} catch(PDOException){
    echo '<div class="card text-center"><h3>Impossible de se connecter à la base</h3></div>';
}
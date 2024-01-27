<?php 
session_start();
require_once('connection.php');
if (!empty($_POST)) {
    // Le formulaire a été envoyé
    // On vérifie que TOUS les champs requis sont remplis
    if (isset($_POST["email"], $_POST["pass"]) && !empty($_POST["email"]) && !empty($_POST["pass"])) {
        $mail = $_POST['email'];
        $pass = $_POST['pass'];
      // Le formulaire est complet
        // On se connecte maintenant à la bdd

    

$sql_Recherche = "SELECT * FROM users WHERE mail = :email";

$connexion = $pdo->prepare($sql_Recherche);
$connexion->execute(['email' => $mail]);
$user = $connexion->fetch();

if ($user && password_verify($pass, $user['pass'])) {
    session_start();
    $_SESSION["user"] = [
        "email" => $_POST["email"],
        "prenom" => $user['prenom'],
        "name" => $user['nom'],
        "id" => $user['id']
    ];
    header('Location: compte.php');
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
        <h1 class="h3 mb-3 text-center">Connexion</h1>

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

        <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
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
<?php
ob_start();
session_start();
include_once('header.php');
require_once('connection.php');

// Assurez-vous que l'utilisateur est connecté
if (isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];

    // Préparez la requête SQL pour récupérer les informations de l'utilisateur
    $sql = "SELECT * FROM users WHERE mail = :email";

    // Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Exécution de la requête
    $stmt->execute();

    // Récupération des données
    $dataUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dataUser) {
        // Traitez le formulaire de modification ici
        if (!empty($_POST)) {
            // Récupérez les données soumises par le formulaire
            $newName = $_POST["name"];
            $newFirstName = $_POST["firstname"];

            // Assurez-vous de gérer la validation des données ici
            // Vous pouvez ajouter des vérifications pour le format des données, etc.

            // Préparez la requête SQL pour mettre à jour les informations de l'utilisateur
            $updateSql = "UPDATE users SET nom = :newName, prenom = :newFirstName WHERE mail = :email";

            // Préparation de la requête de mise à jour
            $updateStmt = $pdo->prepare($updateSql);

            // Liaison des paramètres
            $updateStmt->bindParam(':newName', $newName, PDO::PARAM_STR);
            $updateStmt->bindParam(':newFirstName', $newFirstName, PDO::PARAM_STR);
            $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);

            // Exécution de la requête de mise à jour
            if ($updateStmt->execute()) {
                // Mise à jour réussie
                // Vous pouvez afficher un message de succès ou rediriger l'utilisateur
                header('Location: compte.php');}
                exit; // Assurez-vous de terminer le script ici
        }
        ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h2 class="text-center">Modifier Mes Informations</h2>
                    <form method="post">
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($dataUser['nom']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="firstname">Prénom</label>
                            <input type="text" name="firstname" class="form-control" id="firstname" value="<?= htmlspecialchars($dataUser['prenom']); ?>" required>
                        </div>
                        <!-- Ajoutez d'autres champs pour les informations à modifier -->
                        <button type="submit" class="btn btn-primary btn-block">Enregistrer les modifications</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}

include_once('footer.php');
?>

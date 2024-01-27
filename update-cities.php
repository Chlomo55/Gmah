<?php
// Assurez-vous d'avoir la connexion à la base de données ici (similaire à votre script principal)
require_once('connection.php');

if (isset($_POST['dept'])) {
    $selectedDept = $_POST['dept'];

    // Requête pour récupérer les villes du département sélectionné
    $selectedDeptVillesQuery = $pdo->prepare('SELECT DISTINCT a.ville, v.ville_nom FROM articles a
        JOIN villes v ON a.ville = v.ville_code
        WHERE a.departement = :selectedDept');
    $selectedDeptVillesQuery->bindParam(':selectedDept', $selectedDept);
    $selectedDeptVillesQuery->execute();
    $selectedDeptVilles = $selectedDeptVillesQuery->fetchAll(PDO::FETCH_ASSOC);

    // Générer les options pour la liste déroulante des villes
    $options = '<option value="" selected>Toutes les villes</option>';
    foreach ($selectedDeptVilles as $ville) {
        $options .= '<option value="' . $ville['ville'] . '">' . $ville['ville_nom'] . '</option>';
    }

    // Retourner les options à JavaScript
    echo $options;

    // Ajoutez une requête pour récupérer les articles en fonction de la ville sélectionnée
    if (isset($_POST['ville'])) {
        $selectedVille = $_POST['ville'];

        $selectedVilleArticlesQuery = $pdo->prepare('SELECT a.* FROM articles a
            JOIN villes v ON a.ville = v.ville_code
            WHERE a.departement = :selectedDept AND a.ville = :selectedVille');
        $selectedVilleArticlesQuery->bindParam(':selectedDept', $selectedDept);
        $selectedVilleArticlesQuery->bindParam(':selectedVille', $selectedVille);
        $selectedVilleArticlesQuery->execute();
        $selectedVilleArticles = $selectedVilleArticlesQuery->fetchAll(PDO::FETCH_ASSOC);

        // Afficher les articles en fonction de la ville sélectionnée
        foreach ($selectedVilleArticles as $article) {
            echo '<div>' . $article['nom'] . '</div>';
            // Affichez d'autres détails de l'article selon vos besoins
        }
    }
}
?>

<?php
// Requête pour récupérer les départements existants dans la table articles, triés par ordre alphabétique
$departementsQuery = $pdo->query('SELECT DISTINCT a.departement, d.departement_nom FROM articles a
    JOIN departement d ON a.departement = d.departement_code
    ORDER BY d.departement_nom');
$departementsInTable = $departementsQuery->fetchAll(PDO::FETCH_ASSOC);

// Filtrage des articles en fonction du département sélectionné
$departement = isset($_POST['dept']) ? $_POST['dept'] : '';
$searchTerm = isset($_POST['search']) ? '%' . $_POST['search'] . '%' : '';

// Vérifiez si le bouton de réinitialisation a été cliqué
if (isset($_POST['reset'])) {
    $searchTerm = '';
}

if (!empty($departement)) {
    $sql = 'SELECT a.* FROM articles a
        JOIN departement d ON a.departement = d.departement_code
        WHERE a.departement = :dpt';
} else {
    $sql = 'SELECT a.* FROM articles a JOIN departement d ON a.departement = d.departement_code';
}

// Ajoutez le champ de recherche dans la requête SQL
if (!empty($searchTerm)) {
    $sql .= " AND (a.nom LIKE :search OR a.details LIKE :search)";
}

// Finalisez la requête SQL et effectuez la requête
$sql .= ' ORDER BY d.departement_nom';
$query = $pdo->prepare($sql);

if (!empty($departement)) {
    $query->bindParam(':dpt', $departement);
}

if (!empty($searchTerm)) {
    $query->bindParam(':search', $searchTerm, PDO::PARAM_STR);
}

$query->execute();
$articles = $query->fetchAll();
?>


<div class="mx-4 mb-2 my-5">
    <form method="post">
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1" onclick="document.getElementById('search').value = ''">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </span>
            <input type="text" name="search" id="search" class="form-control" value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>" placeholder="Rechercher" aria-label="Rechercher" aria-describedby="basic-addon1">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

<div class="filtre">
    <form method="post">
    <p class="text-center mb-2">Filtrer par département</p>
        <select name="dept" id="filtres" class="form-select" aria-label="Default select example">
            <option value="" <?= empty($departement) ? 'selected' : '' ?>>
                Tous les départements
            </option>
            <?php foreach ($departementsInTable as $dept): ?>
                <option value="<?= $dept['departement'] ?>" <?= $departement == $dept['departement'] ? 'selected' : '' ?>>
                    <?= $dept['departement_nom'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="text-center">
            <button type="submit" class="btn btn-primary my-2">Filtrer</button>
        </div>
    </form>

    <?php
    $selectedDeptName = '';

    // Trouver le nom du département associé au code de département sélectionné
    foreach ($departementsInTable as $dept) {
        if ($departement == $dept['departement']) {
            $selectedDeptName = $dept['departement_nom'];
            break;
        }
    }
    ?>
<div class="text-center mb-2">
    <button class="btn btn-secondary"> 
        <p>Résultats pour <?= empty($departement) ? 'Tous les départements' : $selectedDeptName ?></p>
</button>
</div>
</div>
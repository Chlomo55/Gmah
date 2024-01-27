<?php
include_once('header.php');
require_once('connection.php');
require_once('filtres2.php');

?>
<div class="container" id="articles">
    <div class="row text-center">
        <?php
        foreach ($articles as $article) {
            $photoAffiche = base64_encode($article['image']);
            if ($article['approuve'] == 1) {
                ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= $article['nom'] ?></h5>
                            <img src="data:image/jpeg;base64,<?= $photoAffiche ?>" class="card-img-center img_article mb-4"
                                alt="<?= $article['nom']?>">
                        </div>
                        <p class="card-text"><?= $article['details'] ?></p>
                        <a href="details.php?id=<?= $article['id'] ?>">
                            <button type="submit" class="btn btn-primary btn-style">Voir plus</button>
                        </a>
                    </div>
                </div>
            <?php
            }
        }
        ?>
    </div>
</div>

<style>
    .img_article {
        height: 180px;
        width: 200px;
        margin-left: auto;
        margin-right: auto;
        border-radius: 15px;
    }

    .btn-style {
        width: 50% !important;
        margin-left: 25%;
        margin-right: 25%;
        margin-bottom: 15px;
    }

    .card-title {
        border: solid 2px #E6E6E6;
        border-radius: 20px;
        margin-bottom: 25px;
        padding: 5px;
    }

    .card {
        border-radius: 15px;
    }
</style>

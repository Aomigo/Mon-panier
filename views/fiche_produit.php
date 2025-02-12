<?php
require_once '../inc/init.inc.php';

/**
 * 2- TRAITEMENT PHP
 */
// variables d'affichage
$panier = '';
$suggestion = '';

    if (!internauteEstConnecte()) {
        header('location: connexion.php');
        exit();
    }

// 2.1- vérifier l'existence du produit en BDD
if (isset($_GET['id_plat'])) {
    $ingredienting = executeRequete("SELECT i.ingredients, j.nom_recette  
	                                            FROM `recettes-ingredient` r     
		                                            LEFT JOIN ingredient i ON i.id_ingredient = r.id_ingredient     
		                                            INNER JOIN recettes j ON j.id_plat = r.id_plat     
	                                            WHERE j.id_plat = :id_plat", array(':id_plat' => $_GET['id_plat']));
    $resultat = executeRequete("SELECT * FROM recettes WHERE id_plat = :id_plat", array(
        ':id_plat' => $_GET['id_plat']
    ));

    if ($resultat->rowCount() === 0) {
        header('location: ../index.php');
        exit();
    }

// 2.2- afficher les infos du produit
    $produit = $resultat->fetch(PDO::FETCH_ASSOC); // on ne fait pas de while car on n'a qu'une seule ligne

    extract($produit); // extract() crée autant de variables que d'indices dans l'array $produit, les variables ont le nom des indices de l'array

// 2.3- formulaire d'ajout au panier
    // on met le bouton du panier
    $panier .= '<hr><form method="post" action="visu_panier.php" class="d-grid gap-3">';
    $panier .= '<input type="hidden" name="id_produit" value="' . $id_plat . '">'; // ajoute l'id du produit au panier

    // select de quantite
    $panier .= '<select name="quantite" class="form-select col-sm-2">';
    for ($i = 1; $i <= 5; $i++) {
        $panier .= '<option>' . $i . '</option>';
    }
    $panier .= '</select>';

    $panier .= '<input type="submit" name="ajout_panier" value="Ajouter au panier" class="btn btn-outline-success">';

    $panier .= '</form><hr>';
} else { // if(isset($_GET['id_produit'])
    header('location: ../index.php');
    exit();
}

/**
 * 1- AFFICHAGE
 */
require_once '../inc/haut.inc.php';
?>

    <!-- div.row.mt-3>div.col-12>h1|c -->
    <div class="row mt-3">
        <div class="col-12">
            <h1><?php echo ucfirst($nom_recette); ?></h1>
        </div>
        <!-- /.col-12 -->

        <!-- .col-md-8>img.img-fluid -->
        <div class="col-md-8">
            <img style="height: 100%; width: 100%" src="<?php echo '../' . $photo; ?>" alt="<?php echo $nom_recette; ?>" class="img-fluid">
        </div>

        <!--.col-md-4>h3{Description}+p+h3{Détails}+ul>(li*3)^+h4-->
        <div class="col-md-4">
            <h3>Description</h3>
            <p><?php echo ucfirst($description); ?></p>
            <h3>Détails</h3>
            <ul>
                <li> Ingrédients : <?php
                    echo '<div class="d-flex flex-column">';
                        while ($ingr = $ingredienting->fetch(PDO::FETCH_ASSOC)) {
                            echo '<p class="mb-0">- '.ucfirst($ingr['ingredients']).'</p>';
                        }
                    echo '</div>';
                ?></li>
                <li>Origine : <?php echo $origine; ?></li>
                <li>Type de recette : <?php echo $type_recette; ?></li>
                <li>Type de plat : <?php echo $type_plat; ?></li>
            </ul>
            <h4>Prix : <?php echo number_format($prix, 2, ',', ' '); ?> &euro;</h4>

            <?php echo $panier; ?>

            <!--    p.d-grid>a.btn.btn-outline-primary>span-->
            <p class="d-grid">
                <a href="../index.php" class="btn btn-outline-primary">Retour au choix de plat</a>
            </p>
        </div>
    </div>
    <!-- /.row mt-3 -->
<?php
$incr = 0;
$resultat = executeRequete("SELECT * FROM recettes WHERE type_plat = :type_plat AND type_recette = :type_recette AND id_plat != :id_plat ORDER BY RAND() LIMIT 2", array(":type_plat" => $type_plat, ":type_recette" => $type_recette, ":id_plat" => $id_plat));
if ($resultat->rowCount() > 0) {
    ?>
    <hr>
    <h1>Suggestions de plats similaires</h1>
    <div class="suggestion" style="display: flex; flex-direction: row;">
    <?php
    while ($var = $resultat->fetch(PDO::FETCH_ASSOC)) {
        $suggesting = '<a style="text-decoration: none;" href="?id_plat=' . $var['id_plat'] . '">';
        $suggesting .= '<div class="inner-suggestion" style="display: flex; flex-direction: column; width: 200px; height: 210px; margin-right: 45px; border: 1px solid gray; border-radius: 5px;">';
        $suggesting .= '<img style="height: 150px; width: 100%" src="' . RACINE_SITE . $var['photo'] . '">';
        $suggesting .= '<p style="color: black" ; >' . ucfirst($var['nom_recette']) . '</p>';
        $suggesting .= '</div></a>';
        echo $suggesting;
    }
}
?>
    </div>
    <!--affichage des suggestions-->


<?php
require_once '../inc/bas.inc.php';

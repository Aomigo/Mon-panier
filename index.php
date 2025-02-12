<?php
//INSERT INTO recettes (nom_recette, poids, origine, prix, ingredients, type_recette, type_plat, photo) VALUES
//('Nikuman', '200g', 'Japon', '8', 'brioche-vapeur viande', 'normal', 'plat', 'photo/nikuman.jpeg'),
//('Bo bun', '1.2kg', 'Vietnam', '14', 'vermicelles pousse-soja carotte nems menthe salade', 'vegetarien', 'plat', 'photo/bo-bun.jpg'),
//('Rouleaux de printemps', '150g', 'Japon', '3', 'crevette menthe pousse-soja salade sauce-nem', 'vegetarien', 'entrée', 'photo/rouleau.jpg');
require_once 'inc/init.inc.php';
require_once 'inc/haut.inc.php';
//debug($_SESSION);
if (isset($_SESSION['membre'])) {
    unset($_SESSION['membre']);
    exit();
}
$option = executeRequete("SELECT ingredients FROM ingredient");
$origines = executeRequete("SELECT DISTINCT origine FROM recettes");

$contenu_main .= '<form method="post">';
$contenu_main .= '<label class="form-label" for="ingredient-form">Liste des plats contenant l\'ingrédient suivant :</label>';
$contenu_main .= '<select class="form-control" name="ingredient-form" id="ingredient-form">';
$contenu_main .= '<option value="">Tous</option>';
while ($var = $option->fetch(PDO::FETCH_ASSOC)) {
    $contenu_main .= '<option value="'. $var['ingredients'] .'">'. ucfirst($var['ingredients']) .'</option>';
}
$contenu_main .='</select> <br>';
$contenu_main .= '<label class="form-label" for="desc-form">Liste des plats contenant le mot suivant :</label>';
$contenu_main .= '<input class="form-control" name="desc-form" id="desc-form" placeholder="Tarte"> <br>';
$contenu_main .= '<p>Liste des plats par fourchette de prix et origine :</p>';
$contenu_main .= '<div class="row">';
$contenu_main .= '<div class="col-md-6 mb-3">';
$contenu_main .= '<label class="form-label" for="prix-mini">Choisir prix mini</label>';
$contenu_main .= '<input class="form-control" name="prix-mini" id="prix-mini">';
$contenu_main .= '</div><div class="col-md-6 mb-3">';
$contenu_main .= '<label class="form-label" for="prix-maxi">Choisir prix maxi</label>';
$contenu_main .= '<input class="form-control" name="prix-maxi" id="prix-maxi"> <br>';
$contenu_main .= '</div></div>';
$contenu_main .= '<label class="form-label" for="origine-form">Origine</label>';
$contenu_main .= '<select class="form-control" name="origine-form" id="origine-form">';
$contenu_main .= '<option value="">Tous</option>';
while ($var = $origines->fetch(PDO::FETCH_ASSOC)) {
    $contenu_main .= '<option value="'. $var['origine'] .'">'. ucfirst($var['origine']) .'</option>';
}
$contenu_main .= '</select>';
$contenu_main .= '<input class="btn btn-success mt-3" type="submit" value="Filtrer !">';
$contenu_main .= '</form>';

//A FAIRE !!!!!!! filtre propre qui se mélange
//On affiche les catégories :
if ($_POST) {
    //debug($_POST);
    $flag = false;
    //if (isset($_POST['origine-form']) && isset($_POST['origine-form']))
    //Select de l'ingredient
    if ((isset($_POST['ingredient-form'])&& $_POST['ingredient-form'] !== '') && (isset($_POST['origine-form']) && $_POST['origine-form'] !== '')) {
        $resultat = executeRequete("SELECT j.*
	                                        FROM `recettes-ingredient` r
                                                LEFT JOIN recettes j ON j.id_plat = r.id_plat
                                                INNER JOIN ingredient i ON i.id_ingredient = r.id_ingredient
                                        WHERE i.ingredients = :ingredient AND j.origine = :origine",
                                        [':ingredient' => $_POST['ingredient-form'], ':origine' => $_POST['origine-form']]);

        if ($resultat->rowCount() === 0) {
            $contenu_main .= '<div class="alert alert-danger mt-5"> Il n\'y a pas de recettes correspondant au filtre</div>';
        }
        $flag = true;
    } else {
        if (isset($_POST['ingredient-form']) && $_POST['ingredient-form'] !== '') {
            $resultat = executeRequete("SELECT j.*
	                                        FROM `recettes-ingredient` r
                                                LEFT JOIN recettes j ON j.id_plat = r.id_plat
                                                INNER JOIN ingredient i ON i.id_ingredient = r.id_ingredient
                                        WHERE i.ingredients = :ingredient", [':ingredient' => $_POST['ingredient-form']]);
            $flag = true;
        }

        //Select de l'origine
        if (isset($_POST['origine-form']) && $_POST['origine-form'] !== '') {
            $resultat = executeRequete("SELECT DISTINCT j.* 
	                                            FROM `recettes-ingredient` r
		                                            INNER JOIN recettes j ON j.id_plat = r.id_plat
   	                                            WHERE j.origine = :origine", [':origine' => $_POST['origine-form']]);
            $flag = true;
        }
        //Prix mini et prix maxi vont ensemble
        if (!empty($_POST['prix-mini']) && !empty($_POST['prix-maxi'])) {
            $resultat = executeRequete("SELECT *
	                                                FROM recettes
                                                    WHERE recettes.prix BETWEEN :prix_min AND :prix_max
                                                    ORDER BY recettes.prix ASC", array(':prix_min' => $_POST['prix-mini'], ':prix_max' => $_POST['prix-maxi']));
            $flag = true;
        } else  {
            //Sinon on fait un > ou un < en fonction du défini
            if (!empty($_POST['prix-mini'])) {
                $resultat = executeRequete("SELECT *
	                                                FROM recettes
                                                    WHERE recettes.prix > :prix_min 
                                                    ORDER BY recettes.prix ASC", array(':prix_min' => $_POST['prix-mini']));
                $flag = true;
            }
            if (!empty($_POST['prix-maxi'])) {
                $resultat = executeRequete("SELECT *
	                                                FROM recettes
                                                    WHERE recettes.prix < :prix_max 
                                                    ORDER BY recettes.prix ASC", array(':prix_max' => $_POST['prix-maxi']));
                $flag = true;
            }
        }
        //description (nom plat)
        if (!empty($_POST['desc-form'])) {
            $resultat = executeRequete("SELECT *
                                                    FROM recettes
                                                    WHERE nom_recette LIKE :form ", [':form' => '%'. $_POST['desc-form'] .'%']);
            $flag = true;
        }
    }
    if (!$flag) {
            $resultat = executeRequete("SELECT * FROM recettes");
    }
} else {
    $resultat = executeRequete("SELECT * FROM recettes");
}


    while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) {
        $contenu_main .= '<div class="col-lg-2 col-md-6 mb-4">';
        $contenu_main .= '<div class="card h-100">';
        // Image cliquable
        $contenu_main .= '<a href="'. RACINE_SITE. 'views/fiche_produit.php?id_plat=' . htmlspecialchars($produit['id_plat']) . '">
                            <img src="' . htmlspecialchars($produit['photo']) . '" alt="' . htmlspecialchars($produit['nom_recette']) . '" class="card-img-top" style="width:25vh; height:25vh; object-fit:cover; width:100%">
                        </a>';
        // Infos du produit
        $contenu_main .= '<div class="card-body">';
        $contenu_main .= '<h5 class="card-title">' . ucfirst($produit['nom_recette']) . '</h5>';
        $contenu_main .= '<h6 class="card-subtitle mb-2 text-muted">' . number_format($produit['prix'], 2, ',', '') . ' €</h6>';
        //number_format (nombre, nombre de décimales, séparateur des décimales, séparateur des milliers)
        $contenu_main .= '<p class="card-text">' . $produit['origine'] . '</p>';
        $contenu_main .= '<p class="card-subtitle text-muted">' . $produit['type_recette'] . '</p>';
        $contenu_main .= '</div>'; // fin <div class="card-body">
        $contenu_main .= '</div>'; // fin <div class="card h-100">
        $contenu_main .= '</div>'; // fin <div class="col-lg-4 col-md-6 mb-4">
    }

?>

    <div class="col-lg-15">
        <div class="row">
            <?php echo $contenu_main; // pour afficher les produits ?>
        </div>
    </div>
    </div><!-- .row -->


<?php
require_once 'inc/bas.inc.php';
//debug($_SESSION);

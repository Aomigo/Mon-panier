<?php
require_once '../inc/init.inc.php';

//1-verif des droits admin
if (!internauteEstConnecteEtAdmin()) {
    header('location: ../views/connexion.php');
    exit();
}

//2-initialisation des variables pour le formulaire
//creation et met toutes les variables a undefined
$id_plat = $type_plat = $description = $type_recette = $origine = $nom_recette = $photo = $prix = $poids = $id_ingredient = "";
$ingredient = executeRequete("SELECT * FROM ingredient");
//3-si on est sur le UPDATE on récupère les données en BDD
if (isset($_GET['action']) && $_GET['action'] === 'modifier' && isset($_GET['id_produit'])) {
    $resultat = executeRequete("SELECT * FROM recettes WHERE id_plat = :id_produit", array(':id_produit' => $_GET['id_produit']));
    $produit = $resultat->fetch(PDO::FETCH_ASSOC);

    $ingr = executeRequete("SELECT i.ingredients
	                                    FROM `recettes-ingredient` r 
                                        LEFT JOIN ingredient i ON i.id_ingredient = r.id_ingredient   
                                        WHERE r.id_plat = :id_plat", array(':id_plat' => $_GET['id_produit']));
    $ingredient_recette = $ingr->fetchAll(PDO::FETCH_ASSOC);

    if ($produit) {
        extract($produit);
    } else {
        $contenu .= '<div class="alert alert danger">Erreur : le produit n\'est pas disponible</div>';
    }
}

//4-traitement du formulaire en POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //variable pour la photo
    $photo_bdd = $photo ?? ""; //Terner, soit la photo soit ""

    //gestion du fichier photo si on insère une nouvelle image
    if (!empty($_FILES['photo']['name'])) {
        $nom_photo = $_FILES['photo']['name']; //nom du fichier
        $photo_bdd = "photo/$nom_photo"; //stocké en base, chemin relatif
        $photo_dossier = $_SERVER['DOCUMENT_ROOT']. RACINE_SITE . $photo_bdd; //chemin absolu sur le serveur

        //vérification de l'extension du fichier
        $extension_autorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower(pathinfo($nom_photo, PATHINFO_EXTENSION));

        //si extension pas ok -> message erreur
        if (!in_array($extension, $extension_autorisees)) {
            $contenu .= '<div class="alert alert-danger">Erreur lors du chargement de la photo.</div>';
        } else {//déplace le dossier télechargé vers le dossier ciblé
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_dossier)) {
                $contenu .= '<div class="alert alert-danger">Erreur lors du téléchargement de la photo.</div>';
            }
        }
    }

    // Vérification si c'est un modification ou un ajout
    if (isset($_GET['action']) && $_GET['action'] === 'modifier') {
        $requete = "UPDATE recettes SET type_plat = :type_plat, nom_recette = :nom_recette, description = :description, type_recette = :type_recette, poids  = :poids, photo = :photo, prix = :prix, origine = :origine WHERE id_plat = :id_plat";
        $params = [
            ':type_plat' => $_POST['type_plat'],
            ':nom_recette' => $_POST['nom_recette'],
            ':description' => $_POST['description'],
            ':type_recette' => $_POST['type_recette'],
            ':poids' => $_POST['poids'],
            ':photo' => $photo_bdd,
            ':prix' => $_POST['prix'],
            ':origine' => $_POST['origine'],
            ':id_plat' => $_POST['id_plat']
        ];

        $remove_ingredients = executeRequete("DELETE FROM `recettes-ingredient` WHERE id_plat = :id_plat", array(':id_plat' => $_GET['id_produit']));
        if (isset($_POST['ingredient'])) {
            for ($i = 0; $i <  count($_POST['ingredient']); $i++) {
                $recette_ingredients = executeRequete("INSERT INTO `recettes-ingredient` (id_plat, id_ingredient) VALUES
                                                               (:id_plat, :id_ingredient)",
                                                                array(':id_plat' => $_GET['id_produit'], ':id_ingredient' => $_POST['ingredient'][$i]));
            }
        }

    } else {
        // Ajout d'un nouveau produit
        $requete = "INSERT INTO recettes (type_plat, nom_recette, description, type_recette, poids, photo, prix, origine) VALUES 
            (:type_plat, :nom_recette, :description, :type_recette, :poids, :photo, :prix, :origine)";

        $params = [
            ':type_plat' => $_POST['type_plat'],
            ':nom_recette' => $_POST['nom_recette'],
            ':description' => $_POST['description'],
            ':type_recette' => $_POST['type_recette'],
            ':poids' => $_POST['poids'],
            ':photo' => $photo_bdd,
            ':prix' => $_POST['prix'],
            ':origine' => $_POST['origine']
        ];
    }

    //execution de la requete préparée
    $resultat = executeRequete($requete, $params);
    if ($resultat) {
        $contenu .= '<div class="alert alert-success">Produit enregistré.</div>';
    } else {
        $contenu .= '<div class="alert alert-danger">Produit non enregistré.</div>';
    }
    //debug($_POST);
    /*if (isset($_POST['ingredient'])) {
        debug(count($_POST['ingredient']));
    }*/
    header('location: gestion_boutique.php');
}




require_once '../inc/haut.inc.php';
?>

<h1 class="mt-4">
    <?php echo ucfirst(isset($_GET['action']) ? $_GET['action'] : 'ajouter'); ?> un produit
</h1>

<!-- Formulaire d'ajout/modification de produit -->
<form method="POST" enctype="multipart/form-data" class="mt-4">
    <div class="mb-3">
        <label for="reference" class="form-label">Nom de la recette</label>
        <input type="text" name="nom_recette" id="reference" class="form-control" value="<?php echo htmlspecialchars($nom_recette); ?>" required>
    </div>
    <div class="mb-3">
        <label for="categorie" class="form-label">Poids</label>
        <input type="text" name="poids" id="categorie" class="form-control" value="<?php echo htmlspecialchars($poids); ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($description); ?></textarea>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="type_plat" class="form-label">Type du plat</label>
            <select class="form-control" name="type_plat" id="type_plat">
                <?php if ($type_plat === 'entrée') {?>
                <option value="entree">Entrée</option>
                <?php }else {?>
                <option value="entree">Entrée</option>
                <?php } if ($type_plat === 'plat') {?>
                <option value="plat" selected>Plat</option>
                <?php }else {?>
                <option value="plat">Plat</option>
                <?php } if ($type_plat === 'dessert') {?>
                <option value="dessert" selected>Dessert</option>
                <?php }else {?>
                <option value="dessert">Dessert</option>
                <?php }?>
            </select>
            <!--<input type="text" name="type_plat" id="couleur" class="form-control" value="<?php echo htmlspecialchars($type_plat); ?>" required>-->
        </div>
        <div class="col-md-6 mb-3">
            <label for="type_recette" class="form-label">Type de la recette</label>
            <select class="form-control" name="type_recette" id="type_recette">
                <?php if ($type_recette === 'normal') {?>
                <option value="normal" selected>Normal</option>
                <?php }else {?>
                <option value="normal">Normal</option>
                <?php } if ($type_recette === 'vegetarien') {?>
                <option value="vegetarien" selected>Végétarien</option>
                <?php }else {?>
                <option value="vegetarien">Végétarien</option>
                <?php }?>
            </select>
            <!--<input type="text" name="type_recette" id="taille" class="form-control" value="<?php echo htmlspecialchars($type_recette); ?>" required>-->
        </div>
    </div>
    <?php if (isset($ingredient_recette)) {?>
    <p class="font-weight-bold">Ingredients :</p>
    <div class="form-switch d-flex flex-wrap flex-row" style="padding-left: 0;">
        <?php  while ($var = $ingredient->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="mr-5 px-5 mb-2 form-check" style="width: 230px; display: flex; align-items: center">
                    <?php $checked = false;
                        foreach($ingredient_recette AS $value){ if ($value['ingredients']  === $var['ingredients']) {
                            $checked = true;
                        }}if ($checked) {?>
                    <input class="form-check-input " type="checkbox" id="<?php $var['id_ingredient'] ?>" name="ingredient[]" value="<?php echo $var['id_ingredient']?>" checked>
                    <?php } else{?>
                    <input class="form-check-input" type="checkbox" id="<?php $var['id_ingredient'] ?>" name="ingredient[]" value="<?php echo $var['id_ingredient']?>">
                    <?php }?>
                    <label class="form-check-label ml-1" for="ingredient[]"><?php echo ucfirst($var['ingredients'])?> </label>
                </div>
        <?php }?>
    </div>
    <?php }?>
    <div class="mb-3">
        <label for="photo" class="form-label">Photo</label>
        <input type="file" name="photo" id="photo" class="form-control">
        <!-- pour éviter les erreurs on n'affiche la photo que s'il y en a une -->
        <?php if (!empty($photo)) : ?>
            <p class="form-text">Actuelle : <img src="../<?php echo $photo; ?>" style="width:100px; height:100px;" alt="Photo actuelle"></p>
        <?php endif; ?>
    </div>



    <div class="mb-3">
        <label for="stock" class="form-label">Origine</label>
        <input type="text" name="origine" id="stock" class="form-control" value="<?php echo $origine; ?>" required>
    </div>

    <input type="hidden" name="id_plat" value="<?php echo $id_plat; ?>">

    <div class="d-grid">
        <button type="submit" class="btn btn-outline-success">
            <?php echo isset($_GET['action']) && $_GET['action'] == 'modifier' ? 'Modifier' : 'Ajouter'; ?> le produit
        </button>
    </div>
</form>

<?php
require_once '../inc/bas.inc.php';
?>

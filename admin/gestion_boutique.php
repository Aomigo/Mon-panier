<?php
require_once '../inc/init.inc.php';

/**
 * Traitement
 */

// 1- Si l'utilisateur n'est pas admin on redirige
if (!internauteEstConnecteEtAdmin()) {
    header('location: ../views/connexion.php');
    exit();
}

// 4- suppression d'un produit
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id_produit'])) {
    $id_produit = intval($_GET['id_produit']); // sécurisation de l'id

    // supprime d'abord la photo (le fichier)
    $produit = executeRequete("SELECT photo FROM recettes WHERE id_plat = :id_plat", [':id_plat' => $id_produit])->fetch(PDO::FETCH_ASSOC);
    //debug($produit);

    if($produit) {
        if(!empty($produit['photo']) && file_exists('../' . $produit['photo'])) {
            unlink('../' . $produit['photo']);
        }

        // supprime l'enregistrement en BDD
        $resultat = executeRequete("DELETE FROM recettes WHERE id_plat = :id_plat", [':id_plat' => $id_produit]);

        // si on a un résultat, on affiche le message
        if($resultat->rowCount() === 1) {
            header('location: gestion_boutique.php');
            exit();
        }
    } else { // if($produit
        $contenu .= '<div class="alert alert-danger">Erreur : le produit n°' . $id_produit . ' n\'existe pas.</div>';
    }
}

// 3- affichage des données en BDD
$resultat = executeRequete("SELECT id_plat AS 'ID', type_recette AS 'Type de recette', nom_recette AS 'Nom du plat', type_plat AS 'Type du plat', description AS 'Description', poids AS 'Poids', prix AS 'Prix', photo AS 'Photo', origine AS 'Origine' FROM recettes");

// debug($total_stock);

$contenu .= '<p class="text-end">Nombre de références : ' . $resultat->rowCount() . '</p>';

// tableau d'affichage des produits
$contenu .= '<table class="table table-dark table-hover">';
$contenu .= '<thead><tr>';

// entêtes dynamiques
for ($i=0; $i < $resultat->columnCount(); $i++) {
    $colonne = $resultat->getColumnMeta($i);
    $contenu .= '<th>' . htmlspecialchars($colonne['name']) . '</th>';
}
$contenu .= '<th>Actions</th>';
$contenu .= '</tr></thead><tbody>';

// Lignes du tableau
while ($row = $resultat->fetch(PDO::FETCH_ASSOC)) {
    $contenu .= '<tr>';
    foreach ($row as $key => $value) {
        if ($key === 'Photo' && !empty($value)) {
            $contenu .= '<td><img src="../' .htmlspecialchars($value) . '" alt="Produit" class="img-thumbnail cart-thumbnail"></td>';
        } else if ($key === 'Prix') {
            $contenu .= '<td>' . htmlspecialchars($value) . '€</td>';
        } else {
            $contenu .= '<td>' . htmlspecialchars($value) . '</td>';
        }
    }
    $contenu .= '<td class="d-grid gap-4 pt-3 pb-3">
                       <a href="ajout_modif_produit.php?action=modifier&id_produit=' . $row['ID'] . '" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-wrench fa-xl"></i></a>
                       <a href="?action=supprimer&id_produit=' . $row['ID'] . '" class="btn btn-outline-warning btn-sm" onclick="return confirm(\'Confirmez-vous la suppression ?\')"><i class="fa-solid fa-trash-can fa-xl"></i></a>
                   </td>';
    $contenu .= '</tr>';
}
$contenu .= '</tbody></table>';

/**
 * *************** 2- AFFICHAGE ******************
 */
require_once '../inc/haut.inc.php';
?>
    <style>
        .cart-thumbnail {
            height: 100px;
            width: 150px;
            object-fit: cover;
        }
    </style>
    <!-- Interface -->
    <h1 class="mt-4">Gestion Boutique</h1>
    <ul class="nav nav-pills mb-4 gap-2">
        <li class="nav-item">
            <a class="btn btn-outline-primary btn-lg" role="button" href="gestion_boutique.php">Affichage des produits</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-outline-danger btn-lg" role="button" href="ajout_modif_produit.php">Ajout d'un produit</a>
        </li>
        <li class="nav-item">
            <a class="btn btn-outline-info btn-lg" role="button" href="gestion_user.php">Gestion des utilisateurs</a>
        </li>
    </ul>

<?php
echo $contenu;

require_once '../inc/bas.inc.php';
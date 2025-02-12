<?php
require_once '../inc/init.inc.php';
$contenu = '';

if (!internauteEstConnecte()) {
    header('location: connexion.php');
    exit();
}
//obtention du panier resilient depuis la bdd
$panier = executeRequete("SELECT p.*, r.prix, r.nom_recette
	                                FROM paniers p
                                    LEFT JOIN recettes r ON r.id_plat = p.id_plat
                                    WHERE p.id_user = :id_user", [':id_user' => $_SESSION['user']['id_user']]);
$panier_fetched = $panier->fetchAll(PDO::FETCH_ASSOC);
//-------------- AJOUT PANIER ----------------//
if (isset($_POST['ajout_panier'])) {
    //pour reset $_POST et enlever le rajout constant de quantite lors du refresh
    header('location: visu_panier.php');
    $flag = false;
    $resultat = executeRequete("SELECT * FROM recettes WHERE id_plat = :id_produit", [':id_produit' => $_POST['id_produit']]);
    $produit = $resultat->fetch(PDO::FETCH_ASSOC);
    //Requête pour checker si l'id produit est = a un id produit déjà dans le panier correspondant a un user
    $check = executeRequete("SELECT p.id_user, p.quantite, r.id_plat
                                        FROM paniers p 
                                        LEFT JOIN recettes r ON r.id_plat = p.id_plat
                                        WHERE p.id_user = :id_user AND p.id_plat = :id_plat",
                                        array(':id_user' => $_SESSION['user']['id_user'], ':id_plat' => $_POST['id_produit']));
    $checking = $check->fetch(PDO::FETCH_ASSOC);
    //Si c'est vrai, alors flag = true
    if ($panier) {
        for ($i = 0; $i < $panier->rowCount(); $i++) {
            if ($checking['id_plat'] === $panier_fetched[$i]['id_plat']) {
                $flag = true;
            }
        }
    }
    //requête fetch pour ajouter seulement de la quantite ou bien une ligne
    if ($flag === true) {
        $fetch = executeRequete("UPDATE paniers SET quantite = :quantite WHERE id_user = :id_user AND id_plat = :id_plat",
            array(':id_plat' => $_POST['id_produit'], ':id_user' => $_SESSION['user']['id_user'], ':quantite' => $_POST['quantite'] + $checking['quantite']));
    } else {
        $fetch = executeRequete("INSERT INTO paniers (id_plat, id_user, quantite) VALUES (:id_plat, :id_user, :quantite)",
            array(':id_plat' => $_POST['id_produit'], ':id_user' => $_SESSION['user']['id_user'], ':quantite' => $_POST['quantite']));
    }
}

//----- SUPPRESSION PRODUIT -----//
if (isset($_GET['action']) && $_GET['action'] === 'suppression' && isset($_GET['id_produit'])) {
    $suppression = executeRequete("DELETE FROM paniers WHERE id_user = :id_user AND id_plat = :id_plat", [':id_user' => $_SESSION['user']['id_user'], ':id_plat' => $_GET['id_produit']]);

    $contenu .= '<div class="alert alert-success">Le produit a été supprimé du panier.</div>';
}

//-------------- VIDER PANIER ----------------//
if (isset($_GET['action']) && $_GET['action'] === 'vider') {
    $vidange = executeRequete("DELETE FROM paniers WHERE id_user = :id_user", [':id_user' => $_SESSION['user']['id_user']]);
    $contenu .= '<div class="alert alert-warning">Votre panier a été vidé.</div>';
}

//-------------- PAIEMENT ----------------//
if (isset($_POST['payer'])) {
    if (!isset($_SESSION['user'])) {
        header('Location: connexion.php');
        exit();
    }
    if ($panier->rowCount() === 0) {
        $contenu .= "<div class='alert alert-danger'>Votre panier est vide.</div>";
    } else {
        try {
            $pdo->beginTransaction();
            // Insertion commande
            $stmt = $pdo->prepare("INSERT INTO commandes (id_user, montant, date_commande) VALUES (:id_user, :montant, NOW())");
            $stmt->execute([
                ':id_user' => $_SESSION['user']['id_user'],
                ':montant' => montantTotal($panier_fetched)
            ]);
            $id_commande = $pdo->lastInsertId();

            $pdo->commit();
            $vidange = executeRequete("DELETE FROM paniers WHERE id_user = :id_user", [':id_user' => $_SESSION['user']['id_user']]);
            $contenu .= "<div class='alert alert-success'>Commande validée ! Votre numéro de commande est <strong>$id_commande</strong>.</div>";
        } catch (Exception $e) {
            $pdo->rollBack();
            $contenu .= "<div class='alert alert-danger'>Erreur : {$e->getMessage()}</div>";
        }
    }
}

if (isset($_POST['nombre'])) {
    $know = false;
    if ($_POST['nombre'] < 0) {
        $contenu .= '<div class="alert alert-danger">Vous ne pouvez pas prendre cette quantitée</div>';
        $know = true;
    }
    if ($_POST['nombre'] === '0') {
        $suppression = executeRequete("DELETE FROM paniers WHERE id_user = :id_user AND id_plat = :id_plat", [':id_user' => $_SESSION['user']['id_user'], ':id_plat' => $_POST['id']]);
        $know = true;
    }
    if ($_POST['nombre'] > 5) {
        $contenu .= '<div class="alert alert-danger">Vous dépassez la quantitée maximale</div>';
        $know = true;
    }
    if ($know === false) {
        $request = executeRequete("UPDATE paniers SET quantite = :quantite WHERE id_plat = :id_plat AND id_user = :id_user",
            array(':quantite' => $_POST['nombre'], ':id_user' => $_SESSION['user']['id_user'], ':id_plat' => $_POST['id']));
    }
    
}
$panier = executeRequete("SELECT p.*, r.prix, r.nom_recette
	                                FROM paniers p
                                    LEFT JOIN recettes r ON r.id_plat = p.id_plat
                                    WHERE p.id_user = :id_user", [':id_user' => $_SESSION['user']['id_user']]);
$panier_fetched = $panier->fetchAll(PDO::FETCH_ASSOC);



// Affichage du haut de page
require_once '../inc/haut.inc.php';

echo $contenu;
?>

    <!-- Affichage du panier -->
    <div class="container my-4">
        <h2>Votre Panier</h2>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Produit</th>
                <th>Prix Unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($panier->rowCount() === 0) : ?>
                <tr>
                    <td colspan="5" class="text-center">Votre panier est vide.</td>
                </tr>
            <?php else : ?>
                <?php for ($i = 0; $i < count($panier_fetched); $i++) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($panier_fetched[$i]['nom_recette']); ?></td>
                        <td><?php echo number_format($panier_fetched[$i]['prix'], 2); ?> €</td>
                        <td>
                                <form action="visu_panier.php" method="post">
                                    <div class="input-group mb-3">
                                    <input class="form-control" type="number" name="nombre" value="<?php echo $panier_fetched[$i]['quantite'];?>">
                                    <input class="d-none" type="text" value="<?php echo $panier_fetched[$i]['id_plat']; ?>" name="id">
                                    <input class="btn btn-success" type="submit" value="Actualiser">
                                    </div>
                                </form>
                        </td>
                        <td><?php echo number_format($panier_fetched[$i]['quantite'] * $panier_fetched[$i]['prix'], 2); ?> €</td>
                        <td>
                            <a href="?action=suppression&id_produit=<?php echo $panier_fetched[$i]['id_plat']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endfor; ?>
                <tr>
                    <td colspan="3" class="text-end">Total :</td>
                    <td colspan="2"><?php echo number_format(montantTotal($panier_fetched), 2); ?> €</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <a href="?action=vider" class="btn btn-warning">Vider le panier</a>
            <form method="POST">
                <button type="submit" name="payer" class="btn btn-success">Payer</button>
            </form>
        </div>
    </div>

<?php
require_once '../inc/bas.inc.php';

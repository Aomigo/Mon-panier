<?php
require_once '../inc/init.inc.php';

//On redirige si on est pas admin
if (!internauteEstConnecteEtAdmin()) {
    header('location: ../views/connexion.php');
    exit();
}
$request = 'SELECT * FROM utilisateurs ';
//on fait une requête pour récup tout les users en fonction de params
if ($_GET) {
    if (isset($_GET['action']) && $_GET['action'] == 'id') {
        $request .= 'ORDER BY id_user ASC';
    }
    if (isset($_GET['action']) && $_GET['action'] == 'name') {
        $request .= 'ORDER BY nom ASC';
    }
    if (isset($_GET['action']) && $_GET['action'] == 'admin') {
        $request .= 'ORDER BY statut ASC';
    }
} else {
    $request .= 'ORDER BY id_user ASC';
}
$user = executeRequete($request);

if (isset($_GET['action']) && $_GET['action'] === 'suppression') {
    $requete = executeRequete("DELETE FROM utilisateurs WHERE id_user = :id_user", [':id_user' => $_GET['id_user']]);
}

require_once  '../inc/haut.inc.php';
?>

    <!-- Affichage du panier -->
    <div class="container my-4">
        <h2>Votre Panier</h2>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th><a class="text-dark text-decoration-none" href="?action=id">Identifiant utilisateur <i class="fa-solid fa-sort-down"></i></a></th>
                <th><a class="text-dark text-decoration-none" href="?action=name">Nom <i class="fa-solid fa-sort-down"></i></a></th>
                <th><a class="text-dark text-decoration-none" href="?action=admin">Administrateur ? <i class="fa-solid fa-sort-down"></i></a></th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($var = $user->fetch(PDO::FETCH_ASSOC)){?>
                    <tr>
                        <td><?php echo $var['id_user'];?></td>
                        <td><?php echo htmlspecialchars($var['nom']); ?></td>
                        <td><?php if ($var['statut'] === 1) {?>
                            Administrateur
                            <?php } else {?>
                            Utilisateur
                            <?php }?>
                        </td>
                        <td>
                            <a href="?action=suppression&id_user=<?php echo $var['id_user']; ?>" class="btn btn-danger btn-sm"
                                <?php echo 'onclick="return confirm(\'Confirmez-vous la suppression ?\')"'?> >Supprimer L'utilisateur</a>
                        </td>
                    </tr>
            <?php }?>
            </tbody>
        </table>
    </div>

<?php
require_once '../inc/bas.inc.php';
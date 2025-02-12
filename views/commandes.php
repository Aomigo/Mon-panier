<?php
require_once '../inc/init.inc.php';

//on récupère les infos des commandes sur l'utilisateur
$commande = executeRequete("SELECT u.nom, c.* 
	                                    FROM commandes c
                                        LEFT JOIN utilisateurs u ON u.id_user = c.id_user
                                        WHERE c.id_user = :id_user", [':id_user' => $_SESSION['user']['id_user']]);

require_once '../inc/haut.inc.php';
//et on l'ajoute au tableau
?>

    <div class="container my-4">
        <h2>Vos commandes</h2>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Identifiant de la commande</th>
                <th>Date de la commance</th>
                <th>Montant de la commande</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($commande->rowCount() === 0) {?>
                <tr>
                    <td colspan="5" class="text-center">Vous n'avez pas de commandes</td>
                </tr>
            <?php } else { while ($var = $commande->fetch(PDO::FETCH_ASSOC)){?>
                    <tr>
                        <td><?php echo $var['id_commande'];?></td>
                        <td><?php echo $var['date_commande']?></td>
                        <td><?php echo $var['montant']. '€'?></td>
                    </tr>
            <?php }}?>
            </tbody>
        </table>
    </div>

<?php
require_once '../inc/bas.inc.php';
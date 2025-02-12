<?php
require_once '../inc/init.inc.php';

$flag = true; // afficher le formulaire tant que l'inscription n'est pas réalisée
$contenu = '';

// 1-Traitement du formulaire
// Vérification de la soumission du formulaire
if ($_POST) {
    $is_valid = true;
    //validation de chaque camp et ajout d'erreurs au contenu si nécessaire
    if (!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 20) {
        $contenu .= '<div class="alert alert-danger"> Le pseudo doit contenir entre 4 et 20 caractères.</div>';
        $is_valid = false;
    }
    if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 4 || strlen($_POST['mdp']) > 20) {
        $contenu .= '<div class="alert alert-danger"> Le mot de passe doit contenir entre 4 et 20 caractères.</div>';
        $is_valid = false;
    }

    if ($is_valid) {
        //Le pseudo est libre ?
        $membre = executeRequete("SELECT * FROM utilisateurs WHERE nom = :pseudo", array(':pseudo' => $_POST['pseudo']));

        if ($membre->rowCount() > 0) {
            $contenu .= '<div class="alert alert-danger"> Le pseudo est déjà pris.</div>';
        } else {
            executeRequete("INSERT INTO utilisateurs (nom, mdp, statut) VALUES (:pseudo, :mdp, 0)", array(
                ':pseudo' => $_POST['pseudo'],
                ':mdp' => password_hash($_POST['mdp'], PASSWORD_DEFAULT)
            ));

            $contenu .= '<div class="alert alert-success">Vous êtes inscrit. <a href="connexion.php">Se connecter.</a></div>';
            $flag = false;
        }
    }
}



//2-Affichage du site
require_once '../inc/haut.inc.php';
?>

    <h1 class="mt-4">Inscription</h1>

<?php
echo $contenu;

if ($flag) : // Affiche le formulaire si l'utilisateur n'est pas encore inscrit
    ?>

    <p>Veuillez renseigner le formulaire pour vous inscrire.</p>

    <form action="" method="post">
        <div class="row">
            <div class="col">
                <label for="pseudo">Pseudo</label><br>
                <input type="text" id="pseudo" name="pseudo" value="<?php echo $_POST['pseudo'] ?? ''; ?>"><br><br>

                <label for="mdp">Mot de passe</label><br>
                <input type="password" id="mdp" name="mdp" value="<?php echo $_POST['mdp'] ?? ''; ?>"><br><br>
            </div>
        </div>
        <input type="submit" value="S'inscrire" name="inscription" class="btn btn-block btn-outline-info">
    </form>

<?php
endif;

require_once '../inc/bas.inc.php';
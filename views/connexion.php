<?php
require_once '../inc/init.inc.php';

$message_deconnexion = '';

//3-deco
if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    //supprimer les infos de la session
    unset($_SESSION['user']);
    //message de confirmation
    $message_deconnexion .= '<div class="alert alert-info">Vous avez été déconnecté</div>';
}

//4-redirection
if (internauteEstConnecte())  {
    header('location: profil.php');
    exit();
}

//1-traitement du formulaire
if($_POST) {
    if (empty($_POST['pseudo'])) {
        //empty = vérifie si c'est vide (0, NULL, '', false, undefined)
        $contenu .= '<div class="alert alert-danger">Le pseudo est requis.</div>';
    }
    if (empty($_POST['mdp'])) {
        $contenu .= '<div class="alert alert-danger">Le mot de passe est requis</div>';
    }

    if (empty($contenu)) { //si pas d'erreur
        $resultat = executeRequete("SELECT * FROM utilisateurs WHERE nom = :pseudo", array(':pseudo' => $_POST['pseudo']));

        if ($resultat->rowCount() > 0) {

            $user = $resultat->fetch(PDO::FETCH_ASSOC);
            if(password_verify($_POST['mdp'], $user['mdp'])) {
                //on crée une session 'user' pour y stocker les infos de la bdd
                $_SESSION['user'] = $user;

                header('location: profil.php');
                exit();
            } else {
                $contenu .= '<div class="alert alert-danger">Identifiants erronés</div>';
            }
        } else {
            //pas de correspondance pseudo/mdp en bdd
            $contenu .= '<div class="alert alert-danger">Identifiants erronés</div>';
        }
    }
}//fin if($_POST)

//2-affichage
require_once '../inc/haut.inc.php';
?>

    <h1 class="mt-4">Connexion</h1>
    <p>Veuillez indiquer vos identifiants pour vous connecter</p>

<?php echo $message_deconnexion;?>

    <form method="post">
        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : '';?>" required>
        </div>
        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>
        <button type="submit" class="btn btn-outline-info mt-3">Se connecter</button>
    </form>

<?php
require_once '../inc/bas.inc.php';
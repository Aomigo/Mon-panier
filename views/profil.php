<?php
require_once '../inc/init.inc.php';
require_once '../inc/haut.inc.php';



//user non connecté
if (!internauteEstConnecte()) {
    header('location: connexion.php');
    exit();
}

//récupère les infos en sessions
extract($_SESSION['user']);
?>

    <h1 class="mt-4">Profil</h1>
    <h2>Bonjour <strong><?php echo htmlspecialchars($nom); ?></strong></h2>

<?php
if (internauteEstConnecteEtAdmin()) {
    echo '<p>Vous êtes un des administrateurs du site.</p>';
}
?>

<?php
require_once '../inc/bas.inc.php';
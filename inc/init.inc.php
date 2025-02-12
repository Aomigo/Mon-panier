<?php
/**
 * Configuration du site
 */
//Pour voir les erreurs via MAMP
ini_set('display_errors', 1);

// Charger les variables d'environnement
function loadEnv($filePath = '.env') {
    if (!file_exists($filePath)) {
        throw new Exception("Le fichier .env est introuvable à : $filePath");
    }

    // ignorer les commentaires dans .env
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // récupérer les variables d'environnement et les rendre exploitables en PHP
        // découpe les clés et les valeurs
        [$key, $value] = explode('=', $line, 2);

        // supprimer les espaces et définir la variable d'environnement
        $key = trim($key);
        $value = trim($value);
        putenv("$key=$value");
        // optionnel, pour rendre les var d'env accessibles dans les super globales
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// charges les variables d'environnement
try {
    loadEnv(__DIR__ . '/../.env');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

/**
 * connexion BDD
 */
try {
    // $pdo est un objet issu de la classe prédéfinie PDO, il représente la connexion à la BDD ici "entreprise"
    $pdo = new PDO('mysql:host=localhost;dbname=mon_petit_panier', 'root',/*ID de la bdd*/ 'root');
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //passe en lide exception pour capturer les erreurs sql
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4' // caractère UTF8
    );
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

/*try {
    $pdo = new PDO (
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASS'),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // erreurs SQL
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4' // définition caractères dans les échanges avec la BDD
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la BDD : ' . $e->getMessage());
}*/

/**
 * Session
 */
session_start();

/*
 * Constante qui contient le chemin du site
 */
define('RACINE_SITE', '/Projet_panier_2024/');

/*
 * Variables d'affichage
 */
$contenu = '';
$contenu_main = '';
$contenu_droite = '';

/*
 * inclusion des fonctions du site
 */
require_once 'function.inc.php';
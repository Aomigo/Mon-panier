<?php

/*
 * Debug
 */

// Fonction d'affichage d'un print_r() [2ème paramètre = 1] et d'un var_dump() [2ème paramètre = 2] avec balise <pre>
function debug($param, int $exit = 2): void
{
    if ($exit === 1) {
        echo '<pre style="background-color: #d5ecd4; padding: 1vh 5vh;">';
        echo '<strong>print_r($param)</strong> <br>';
        print_r($param);
        echo '</pre>';
    } elseif ($exit === 2) {
        echo '<pre style="background-color: #ebd4cb; padding: 1vh 5vh;">';
        echo '<strong>var_dump($param)</strong> <br>';
        var_dump($param);
        echo '</pre>';
    }
}

/*
 * Fonctions membres avec les rôles
 */
// Si l'internautes est connecté -> Bool
function internauteEstConnecte() : bool {
    return isset($_SESSION['user']);
}

// Si l'internautes est admin
function internauteEstConnecteEtAdmin() : bool {
    return internauteEstConnecte() && ($_SESSION['user']['statut'] ?? 0) === 1;
}

/*
 * Fonctions de requête
 */
function executeRequete(string $requete, array $param = []) : PDOStatement {
    if (!empty($param)) {
        foreach($param as $indice => $value) {
            $param[$indice] = htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }

    global $pdo; // permet d'accéder à $pdo défini dans l'espace global (hors de la fonction) et dans le fichier init.inc.php
    $res = $pdo->prepare($requete); // Préparation de la requête
    $res->execute($param); // exécute de la requête avec les paramètres sécurisés

    return $res;
}

/*
 * Gestion du panier
 */
function montantTotal($panier_fetched) {
    $total = 0;
    for ($i = 0; $i < count($panier_fetched); $i++) {
        $total += $panier_fetched[$i]['quantite'] * $panier_fetched[$i]['prix'];
    }
    return round($total, 2);
}
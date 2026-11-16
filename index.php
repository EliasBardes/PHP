<?php
/**
 * AUTO-MATCH — Point d'entrée de l'application (Front Controller)
 * Architecture Orientée Objet MVC en PHP
 */

// Initialisation de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chargement du routeur
require_once __DIR__ . '/routeur.php';

// Exécution du routage
$router = new Router();
$router->dispatch();

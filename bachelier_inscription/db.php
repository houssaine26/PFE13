<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "inscription_bacheliers";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       // Gestion d'erreurs
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Récupération associative
        PDO::ATTR_EMULATE_PREPARES => false,               // Désactivation emulate prepares
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

<?php

// Configurazione delle credenziali del database
$db_name = 'mysql:host=localhost;dbname=hotel_db';
$db_user_name = 'root';
$db_user_pass = '';

// Creazione della connessione PDO con gestione degli errori
try {
    $conn = new PDO($db_name, $db_user_name, $db_user_pass);
    // Imposta il PDO error mode a eccezione
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Imposta la codifica dei caratteri
    $conn->exec("set names utf8");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Funzione per creare un ID univoco
function create_unique_id($length = 20) {
    // Usa random_bytes per una maggiore sicurezza
    return bin2hex(random_bytes($length / 2));
}

?>

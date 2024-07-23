<?php
// Avvia la sessione
session_start();

// Controlla se l'utente è loggato
if (isset($_SESSION['user_id'])) {
    // Distruggi tutte le variabili di sessione
    $_SESSION = array();

    // Se si utilizza un cookie di sessione, distruggi il cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    // Distruggi la sessione
    session_destroy();

    // Reindirizza l'utente alla pagina di login o alla home page
    header("Location: login.php"); // Modifica 'login.php' con la tua pagina di login
    exit;
} else {
    // Se non c'è una sessione attiva, reindirizza alla home page o alla pagina di login
    header("Location: index.php"); // Modifica 'index.php' con la tua pagina di home
    exit;
}
?>

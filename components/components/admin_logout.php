<?php

include '../components/connect.php';

// Verifica se l'utente è effettivamente loggato
if (isset($_COOKIE['admin_id'])) {
    // Annulla il cookie di sessione impostando il tempo di scadenza al passato
    setcookie('admin_id', '', time() - 3600, '/');
}

// Reindirizza alla pagina di login con un messaggio di conferma
header('location:../admin/login.php?message=logout_success');
exit;

?>

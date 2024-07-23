<?php
// Include il file di connessione al database
include '../components/connect.php';

// Verifica se il cookie 'admin_id' è impostato, altrimenti reindirizza alla pagina di login
if (!isset($_COOKIE['admin_id'])) {
    header('location:login.php');
    exit();
}

// Sanitizza il valore del cookie 'admin_id' per prevenire attacchi XSS
$admin_id = filter_var($_COOKIE['admin_id'], FILTER_SANITIZE_STRING);

// Gestione della richiesta POST per l'eliminazione di una prenotazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Sanitizza l'ID della prenotazione da eliminare
    $delete_id = filter_var($_POST['delete_id'], FILTER_SANITIZE_STRING);

    try {
        // Inizia una transazione per garantire l'integrità dei dati
        $conn->beginTransaction();

        // Verifica se la prenotazione esiste nel database
        $verify_delete = $conn->prepare("SELECT * FROM reservation WHERE id_reservation = ?");
        $verify_delete->execute([$delete_id]);

        // Se la prenotazione esiste, procedi con l'eliminazione
        if ($verify_delete->rowCount() > 0) {
            $delete_bookings = $conn->prepare("DELETE FROM reservation WHERE id_reservation = ?");
            $delete_bookings->execute([$delete_id]);
            $conn->commit();  // Commit della transazione
            $success_msg = 'Réservation annulée';
        } else {
            $conn->rollBack();  // Rollback della transazione se la prenotazione non esiste
            $warning_msg = 'Réservation déjà annulée';
        }
    } catch (Exception $e) {
        $conn->rollBack();  // Rollback della transazione in caso di errore
        $error_msg = 'Erreur : réservation pas annulée ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservations</title>
    <!-- Link ai fogli di stile -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <!-- Include l'header amministrativo -->
    <?php include '../components/admin_header.php'; ?>

    <!-- Sezione principale per la gestione delle prenotazioni -->
    <section class="grid">
        <h1 class="heading">RESERVATIONS</h1>
        <div class="box-container">
            <?php
            // Seleziona tutte le prenotazioni dal database
            $select_bookings = $conn->prepare("SELECT * FROM bookings");
            $select_bookings->execute();
            if ($select_bookings->rowCount() > 0) {
                while ($fetch_bookings = $select_bookings->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box">
                <p>ID reservation: <span><?= htmlspecialchars($fetch_bookings['id_reservation']); ?></span></p>
                <p>Nom: <span><?= htmlspecialchars($fetch_bookings['nom']); ?></span></p>
                <p>Email: <span><?= htmlspecialchars($fetch_bookings['email']); ?></span></p>
                <p>N° portable: <span><?= htmlspecialchars($fetch_bookings['n_portable']); ?></span></p>
                <p>Check-in: <span><?= htmlspecialchars($fetch_bookings['check_in']); ?></span></p>
                <p>Check-out: <span><?= htmlspecialchars($fetch_bookings['check_out']); ?></span></p>
                <p>Chambres: <span><?= htmlspecialchars($fetch_bookings['chambres']); ?></span></p>
                <p>Adultes: <span><?= htmlspecialchars($fetch_bookings['adultes']); ?></span></p>
                <p>Cenfants: <span><?= htmlspecialchars($fetch_bookings['enfants']); ?></span></p>
                <form action="" method="POST">
                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($fetch_bookings['id_reservation']); ?>">
                    <input type="submit" value="Supprimer réservation" onclick="return confirm('Vous êtes sûre de vouloir supprimer cette réservation?');" name="delete" class="btn">
                </form>
            </div>
            <?php
                }
            } else {
            ?>
            <div class="box" style="text-align: center;">
                <p>Aucune réservation trouvé!</p>
                <a href="dashboard.php" class="btn">Retour à la page d'accueil</a>
            </div>
            <?php
            }
            ?>
        </div>
    </section>

    <!-- Include il file per la visualizzazione dei messaggi -->
    <?php include '../components/message.php'; ?>

    <!-- Include gli script JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
</body>
</html>
<?php
// Include il file di connessione al database
include '../components/connect.php';

// Verifica se l'amministratore è autenticato tramite il cookie
if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
    exit();
}

// Gestione della richiesta di eliminazione di un messaggio
if (isset($_POST['delete'])) {
    // Sanitizza l'ID del messaggio da eliminare
    $delete_id = filter_var($_POST['delete_id'], FILTER_SANITIZE_STRING);

    // Verifica se il messaggio esiste
    $verify_delete = $conn->prepare("SELECT * FROM `messages` WHERE id = ?");
    $verify_delete->execute([$delete_id]);

    if ($verify_delete->rowCount() > 0) {
        // Elimina il messaggio
        $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
        $delete_message->execute([$delete_id]);
        $success_msg[] = 'Message supprimé !';
    } else {
        $warning_msg[] = 'Message déjà supprimé !';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <!-- Link ai fogli di stile -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<!-- Includi l'header dell'admin -->
<?php include '../components/admin_header.php'; ?>

<!-- Sezione dei messaggi -->
<section class="grid">
    <h1 class="heading">Messages</h1>
    <div class="box-container">
        <?php
        // Seleziona tutti i messaggi dal database
        $select_messages = $conn->prepare("SELECT * FROM `messages`");
        $select_messages->execute();
        if ($select_messages->rowCount() > 0) {
            while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>Nom : <span><?= htmlspecialchars($fetch_messages['name']); ?></span></p>
            <p>Email : <span><?= htmlspecialchars($fetch_messages['email']); ?></span></p>
            <p>Numéro : <span><?= htmlspecialchars($fetch_messages['number']); ?></span></p>
            <p>Message : <span><?= htmlspecialchars($fetch_messages['message']); ?></span></p>
            <form action="" method="POST">
                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($fetch_messages['id']); ?>">
                <input type="submit" value="Supprimer le message" onclick="return confirm('Supprimer ce message ?');" name="delete" class="btn">
            </form>
        </div>
        <?php
            }
        } else {
        ?>
        <div class="box" style="text-align: center;">
            <p>Aucun message trouvé !</p>
            <a href="dashboard.php" class="btn">Retour à l'accueil</a>
        </div>
        <?php
        }
        ?>
    </div>
</section>

<!-- Includi il file per la visualizzazione dei messaggi -->
<?php include '../components/message.php'; ?>

<!-- Include gli script JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>

</body>
</html>

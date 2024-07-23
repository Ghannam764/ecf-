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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    
    <!-- style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <!-- Include l'header amministrativo -->
    <?php include '../components/admin_header.php'; ?>

    <!-- Sezione principale della dashboard -->
    <section class="dashboard">
        <h1 class="heading">Tableau de bord</h1>
        <div class="box-container">
            <!-- Box di benvenuto -->
            <div class="box">
                <?php
                // Seleziona le informazioni del profilo amministrativo
                $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
                $select_profile->execute([$admin_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                ?>
                <h3>Bienvenue !</h3>
                <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
                <a href="update.php" class="btn">Mettre à jour le profil</a>
            </div>

            <!-- Box per il conteggio delle prenotazioni -->
            <div class="box">
                <?php
                // Seleziona e conta tutte le prenotazioni
                $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
                $select_bookings->execute();
                $count_bookings = $select_bookings->rowCount();
                ?>
                <h3><?= $count_bookings; ?></h3>
                <p>Réservations totales</p>
                <a href="bookings.php" class="btn">Voir les réservations</a>
            </div>

            <!-- Box per il conteggio degli amministratori -->
            <div class="box">
                <?php
                // Seleziona e conta tutti gli amministratori
                $select_admins = $conn->prepare("SELECT * FROM `admins`");
                $select_admins->execute();
                $count_admins = $select_admins->rowCount();
                ?>
                <h3><?= $count_admins; ?></h3>
                <p>Admins totaux</p>
                <a href="admins.php" class="btn">Voir les admins</a>
            </div>

            <!-- Box per il conteggio dei messaggi -->
            <div class="box">
                <?php
                // Seleziona e conta tutti i messaggi
                $select_messages = $conn->prepare("SELECT * FROM `messages`");
                $select_messages->execute();
                $count_messages = $select_messages->rowCount();
                ?>
                <h3><?= $count_messages; ?></h3>
                <p>Messages totaux</p>
                <a href="messages.php" class="btn">Voir les messages</a>
            </div>

            <!-- Box per accesso rapido a login o registrazione -->
            <div class="box">
                <h3>Sélection rapide</h3>
                <p>Connexion ou inscription</p>
                <a href="login.php" class="btn" style="margin-right: 1rem;">Connexion</a>
                <a href="register.php" class="btn" style="margin-left: 1rem;">Inscription</a>
            </div>
        </div>
    </section>

    <!-- Include il file per la visualizzazione dei messaggi -->
    <?php include '../components/message.php'; ?>

    <!-- Include gli script JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js"></script>
</body>
</html>
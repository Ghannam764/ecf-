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

// Gestione della richiesta di registrazione
if (isset($_POST['submit'])) {
    // Crea un ID unico per il nuovo admin
    $id = create_unique_id();

    // Sanitizza e filtra i dati di input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); 
    $pass = filter_var(sha1($_POST['pass']), FILTER_SANITIZE_STRING); 
    $c_pass = filter_var(sha1($_POST['c_pass']), FILTER_SANITIZE_STRING);   

    // Verifica se l'username è già in uso
    $select_admins = $conn->prepare("SELECT * FROM `admin` WHERE nom = ?");
    $select_admins->execute([$name]);

    if ($select_admins->rowCount() > 0) {
        $warning_msg[] = 'Nom d’utilisateur déjà pris !';
    } else {
        if ($pass != $c_pass) {
            $warning_msg[] = 'Les mots de passe ne correspondent pas !';
        } else {
            // Inserisce il nuovo admin nel database
            $insert_admin = $conn->prepare("INSERT INTO `admin` (admin_id, nom, password) VALUES (?, ?, ?)");
            $insert_admin->execute([$id, $name, $pass]);
            $success_msg[] = 'Inscription réussie !';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Link ai fogli di stile -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
   
<!-- Includi l'header dell'admin -->
<?php include '../components/admin_header.php'; ?>

<!-- Sezione di registrazione -->
<section class="form-container">
    <form action="" method="POST">
        <h3>Inscription nouvelle</h3>
        <input type="text" name="nom" placeholder="Entrez le nom d'utilisateur" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" placeholder="Entrez le mot de passe" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="c_pass" placeholder="Confirmez le mot de passe" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="S'inscrire maintenant" name="submit" class="btn">
    </form>
</section>

<!-- Include gli script JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>

<!-- Include il file per la visualizzazione dei messaggi -->
<?php include '../components/message.php'; ?>

</body>
</html>

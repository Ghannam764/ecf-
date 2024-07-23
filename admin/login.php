<?php
// Include il file di connessione al database
include '../components/connect.php';


if (isset($_GET['message']) && $_GET['message'] === 'logout_success') {
    echo '<p>Vous vous êtes déconnecté avec succès.</p>';
}


// Verifica se il modulo di login è stato inviato
if (isset($_POST['submit'])) {
    // Sanitizza l'input per prevenire attacchi XSS
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Verifica le credenziali dell'amministratore
    $select_admins = $conn->prepare("SELECT * FROM `admin` WHERE nom = ? AND password = ? LIMIT 1");
    $select_admins->execute([$name, $pass]);
    $row = $select_admins->fetch(PDO::FETCH_ASSOC);

    // Se le credenziali sono corrette, imposta il cookie 'admin_id' e reindirizza alla dashboard
    if ($select_admins->rowCount() > 0) {
        setcookie('admin_id', $row['id'], time() + 60*60*24*30, '/');
        header('location:dashboard.php');
        exit();
    } else {
        $warning_msg[] = 'Nom d\'utilisateur ou mot de passe incorrect !';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Link ai fogli di stile -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<!-- Sezione di login -->
<section class="form-container" style="min-height: 100vh;">
    <form action="" method="POST">
        <h3>Bienvenue !</h3>
        <p>Nom par défaut = <span>admin</span> & mot de passe = <span>111</span></p>
        <input type="text" name="name" placeholder="Entrez le nom d'utilisateur" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" placeholder="Entrez le mot de passe" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Se connecter maintenant" name="submit" class="btn">
    </form>
</section>

<!-- Include il file per la visualizzazione dei messaggi -->
<?php include '../components/message.php'; ?>

<!-- Include gli script JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</body>
</html>

<?php
// Include il file di connessione al database
include '../components/connect.php';

// Verifica se l'amministratore è autenticato tramite il cookie
if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
} else {
   $admin_id = '';
   header('location:login.php');
   exit();
}

// Seleziona i dettagli del profilo dell'amministratore corrente
$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE admin_id = ? LIMIT 1");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

// Gestione della richiesta di aggiornamento del profilo
if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 

   // Aggiorna il nome utente
   if(!empty($name)){
      $verify_name = $conn->prepare("SELECT * FROM `admin` WHERE nom = ?");
      $verify_name->execute([$name]);
      if($verify_name->rowCount() > 0){
         $warning_msg[] = 'Nom d’utilisateur déjà pris !';
      } else {
         $update_name = $conn->prepare("UPDATE `admin` SET nnom = ? WHERE admin_id = ?");
         $update_name->execute([$name, $admin_id]);
         $success_msg[] = 'Nom d’utilisateur mis à jour !';
      }
   }

   // Password di default per confronto (hash di una stringa vuota)
   $empty_pass = sha1('');
   $prev_pass = $fetch_profile['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $c_pass = sha1($_POST['c_pass']);
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);

   // Aggiorna la password
   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $warning_msg[] = 'Ancien mot de passe incorrect !';
      } elseif($c_pass != $new_pass){
         $warning_msg[] = 'Nouveau mot de passe non correspond !';
      } else {
         if($new_pass != $empty_pass){
            $update_password = $conn->prepare("UPDATE `admin` SET password = ? WHERE admin_id = ?");
            $update_password->execute([$c_pass, $admin_id]);
            $success_msg[] = 'Mot de passe mis à jour !';
         } else {
            $warning_msg[] = 'Veuillez entrer un nouveau mot de passe !';
         }
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
   <title>Mettre à jour</title>

   <!-- Link ai fogli di stile -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
   
<!-- Include l'header dell'admin -->
<?php include '../components/admin_header.php'; ?>

<!-- Sezione di aggiornamento del profilo -->
<section class="form-container">
   <form action="" method="POST">
      <h3>Mettre à jour le profil</h3>
      <input type="text" name="name" placeholder="<?= htmlspecialchars($fetch_profile['name']); ?>" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="old_pass" placeholder="Entrez l’ancien mot de passe" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Entrez le nouveau mot de passe" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="c_pass" placeholder="Confirmez le nouveau mot de passe" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Mettre à jour maintenant" name="submit" class="btn">
   </form>
</section>

<!-- Include gli script JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>

<!-- Include il file per la visualizzazione dei messaggi -->
<?php include '../components/message.php'; ?>

</body>
</html>

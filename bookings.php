<?php
// Include il file di connessione al database
include 'components/connect.php';

// Controlla se il cookie 'user_id' è impostato
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id']; // Recupera l'ID utente dal cookie
}else{
   // Se il cookie non è impostato, crea un nuovo ID utente unico e imposta il cookie
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
   // Reindirizza alla home page
   header('location:index.php');
}

// Verifica se è stato inviato un modulo di annullamento
if(isset($_POST['cancel'])){

   // Recupera e sanifica l'ID della prenotazione da annullare
   $booking_id = $_POST['booking_id'];
   $booking_id = filter_var($booking_id, FILTER_SANITIZE_STRING);

   // Prepara una query per verificare se la prenotazione esiste
   $verify_booking = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
   $verify_booking->execute([$booking_id]);

   // Controlla se la prenotazione esiste
   if($verify_booking->rowCount() > 0){
      // Se esiste, prepara ed esegui una query per eliminarla
      $delete_booking = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
      $delete_booking->execute([$booking_id]);
      $success_msg[] = 'booking cancelled successfully!'; // Messaggio di successo
   }else{
      $warning_msg[] = 'booking cancelled already!'; // Messaggio di avviso
   }
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>bookings</title>

   <!-- Include il CSS di Swiper per gli slider -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- Include il CSS di Font Awesome per le icone -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Include il CSS personalizzato -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?> <!-- Include l'intestazione utente -->

<!-- Sezione prenotazioni -->
<section class="bookings">

   <h1 class="heading">my bookings</h1>

   <div class="box-container">

   <?php
      // Prepara e esegui una query per selezionare tutte le prenotazioni dell'utente
      $select_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ?");
      $select_bookings->execute([$user_id]);

      // Controlla se ci sono prenotazioni
      if($select_bookings->rowCount() > 0){
         // Itera attraverso ogni prenotazione e visualizzala
         while($fetch_booking = $select_bookings->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>name : <span><?= $fetch_booking['name']; ?></span></p>
      <p>email : <span><?= $fetch_booking['email']; ?></span></p>
      <p>number : <span><?= $fetch_booking['number']; ?></span></p>
      <p>check in : <span><?= $fetch_booking['check_in']; ?></span></p>
      <p>check out : <span><?= $fetch_booking['check_out']; ?></span></p>
      <p>rooms : <span><?= $fetch_booking['rooms']; ?></span></p>
      <p>adults : <span><?= $fetch_booking['adults']; ?></span></p>
      <p>childs : <span><?= $fetch_booking['childs']; ?></span></p>
      <p>booking id : <span><?= $fetch_booking['booking_id']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="booking_id" value="<?= $fetch_booking['booking_id']; ?>">
         <!-- Bottone per annullare la prenotazione con conferma -->
         <input type="submit" value="cancel booking" name="cancel" class="btn" onclick="return confirm('cancel this booking?');">
      </form>
   </div>
   <?php
    }
   }else{
   ?>   
   <!-- Messaggio quando non ci sono prenotazioni e link per prenotare una nuova -->
   <div class="box" style="text-align: center;">
      <p style="padding-bottom: .5rem; text-transform:capitalize;">no bookings found!</p>
      <a href="index.php#reservation" class="btn">book new</a>
   </div>
   <?php
   }
   ?>
   </div>

</section>

<!-- Sezione prenotazioni fine -->

<?php include 'components/footer.php'; ?> <!-- Include il footer -->

<!-- Include il JS di Swiper -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<!-- Include il JS di SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Include il JS personalizzato -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?> <!-- Include il file dei messaggi -->

</body>
</html>

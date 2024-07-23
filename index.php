<?php

// Include il file di connessione al database
include 'components/connect.php';

// Verifica se esiste un cookie per l'ID utente
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id']; // Recupera l'ID utente dal cookie
}else{
   // Se non esiste un cookie, crea un nuovo ID utente unico e imposta il cookie
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
   header('location:index.php'); // Ricarica la pagina per impostare il cookie
}

// Gestisce la richiesta di verifica disponibilità delle camere
if(isset($_POST['check'])){

   // Recupera e sanitizza i dati di input
   $check_in = filter_var($_POST['check_in'], FILTER_SANITIZE_STRING);

   // Inizializza il conteggio delle camere prenotate
   $total_rooms = 0;

   // Prepara e esegue la query per verificare le prenotazioni
   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   // Somma il numero di camere prenotate
   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   // Verifica se il numero totale di camere prenotate è maggiore o uguale a 30
   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available'; // Messaggio di avviso se le camere non sono disponibili
   }else{
      $success_msg[] = 'rooms are available'; // Messaggio di successo se le camere sono disponibili
   }

}

// Gestisce la richiesta di prenotazione
if(isset($_POST['book'])){

   // Recupera e sanitizza i dati di input
   $booking_id = create_unique_id();
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $rooms = filter_var($_POST['rooms'], FILTER_SANITIZE_STRING);
   $check_in = filter_var($_POST['check_in'], FILTER_SANITIZE_STRING);
   $check_out = filter_var($_POST['check_out'], FILTER_SANITIZE_STRING);
   $adults = filter_var($_POST['adults'], FILTER_SANITIZE_STRING);
   $childs = filter_var($_POST['childs'], FILTER_SANITIZE_STRING);

   // Inizializza il conteggio delle camere prenotate
   $total_rooms = 0;

   // Prepara e esegue la query per verificare le prenotazioni
   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   // Somma il numero di camere prenotate
   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   // Verifica se il numero totale di camere prenotate è maggiore o uguale a 30
   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available'; // Messaggio di avviso se le camere non sono disponibili
   }else{
      // Prepara e esegue la query per verificare se la prenotazione è già esistente
      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

      // Verifica se la prenotazione esiste già
      if($verify_bookings->rowCount() > 0){
         $warning_msg[] = 'room booked already!'; // Messaggio di avviso se la prenotazione esiste già
      }else{
         // Inserisce la nuova prenotazione nel database
         $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
         $success_msg[] = 'room booked successfully!'; // Messaggio di successo se la prenotazione è avvenuta con successo
      }
   }
}

// Gestisce l'invio di un messaggio
if(isset($_POST['send'])){

   // Recupera e sanitizza i dati di input
   $id = create_unique_id();
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

   // Prepara e esegue la query per verificare se il messaggio è già stato inviato
   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_message->execute([$name, $email, $number, $message]);

   // Verifica se il messaggio esiste già
   if($verify_message->rowCount() > 0){
      $warning_msg[] = 'message sent already!'; // Messaggio di avviso se il messaggio è già stato inviato
   }else{
      // Inserisce il nuovo messaggio nel database
      $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$id, $name, $email, $number, $message]);
      $success_msg[] = 'message send successfully!'; // Messaggio di successo se il messaggio è stato inviato con successo
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- home section starts  -->
<section class="home" id="home">
   <div class="swiper home-slider">
      <div class="swiper-wrapper">
         <div class="box swiper-slide">
            <img src="images/home-img-1.jpg" alt="">
            <div class="flex">
               <h3>luxurious rooms</h3>
               <a href="#availability" class="btn">check availability</a>
            </div>
         </div>
         <div class="box swiper-slide">
            <img src="images/home-img-2.jpg" alt="">
            <div class="flex">
               <h3>foods and drinks</h3>
               <a href="#reservation" class="btn">make a reservation</a>
            </div>
         </div>
         <div class="box swiper-slide">
            <img src="images/home-img-3.jpg" alt="">
            <div class="flex">
               <h3>luxurious halls</h3>
               <a href="#contact" class="btn">contact us</a>
            </div>
         </div>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
   </div>
</section>
<!-- home section ends -->

<!-- availability section starts  -->
<section class="availability" id="availability">
   <form action="" method="post">
      <div class="flex">
         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1">1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0">0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
            </select>
         </div>
         <div class="box">
            <input type="submit" name="check" value="check availability" class="btn">
         </div>
      </div>
   </form>
   <!-- Display messages for availability check -->
   <?php if(!empty($success_msg)): ?>
      <div class="success-messages">
         <?php foreach($success_msg as $msg): ?>
            <p><?= htmlspecialchars($msg); ?></p>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>

   <?php if(!empty($warning_msg)): ?>
      <div class="warning-messages">
         <?php foreach($warning_msg as $msg): ?>
            <p><?= htmlspecialchars($msg); ?></p>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
</section>
<!-- availability section ends -->

<!-- reservation section starts  -->
<section class="reservation" id="reservation">
   <form action="" method="post">
      <h1 class="heading">make a reservation</h1>
      <div class="flex">
         <div class="box">
            <p>name <span>*</span></p>
            <input type="text" name="name" class="input" required>
         </div>
         <div class="box">
            <p>email <span>*</span></p>
            <input type="email" name="email" class="input" required>
         </div>
         <div class="box">
            <p>phone number <span>*</span></p>
            <input type="text" name="number" class="input" required>
         </div>
         <div class="box">
            <p>rooms <span>*</span></p>
            <input type="number" name="rooms" min="1" max="10" class="input" required>
         </div>
         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1">1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>childs <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0">0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
            </select>
         </div>
         <div class="box">
            <input type="submit" name="book" value="book now" class="btn">
         </div>
      </div>
   </form>
</section>
<!-- reservation section ends -->

<!-- contact section starts  -->
<section class="contact" id="contact">
   <form action="" method="post">
      <h1 class="heading">contact us</h1>
      <div class="flex">
         <div class="box">
            <p>name <span>*</span></p>
            <input type="text" name="name" class="input" required>
         </div>
         <div class="box">
            <p>email <span>*</span></p>
            <input type="email" name="email" class="input" required>
         </div>
         <div class="box">
            <p>phone number <span>*</span></p>
            <input type="text" name="number" class="input" required>
         </div>
         <div class="box">
            <p>message <span>*</span></p>
            <textarea name="message" class="input" required></textarea>
         </div>
         <div class="box">
            <input type="submit" name="send" value="send message" class="btn">
         </div>
      </div>
   </form>
</section>
<!-- contact section ends -->

<!-- Include footer -->
<?php include 'components/footer.php'; ?>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<!-- Custom JS -->
<script src="js/script.js"></script>
</body>
</html>

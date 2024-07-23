<?php

// Verifica e mostra i messaggi di successo
if(isset($success_msg)){
   foreach($success_msg as $message){
      // Usa htmlspecialchars per evitare XSS
      $safe_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
      echo '<script>swal("'.$safe_message.'", "", "success");</script>';
   }
}

// Verifica e mostra i messaggi di avviso
if(isset($warning_msg)){
   foreach($warning_msg as $message){
      // Usa htmlspecialchars per evitare XSS
      $safe_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
      echo '<script>swal("'.$safe_message.'", "", "warning");</script>';
   }
}

// Verifica e mostra i messaggi informativi
if(isset($info_msg)){
   foreach($info_msg as $message){
      // Usa htmlspecialchars per evitare XSS
      $safe_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
      echo '<script>swal("'.$safe_message.'", "", "info");</script>';
   }
}

// Verifica e mostra i messaggi di errore
if(isset($error_msg)){
   foreach($error_msg as $message){
      // Usa htmlspecialchars per evitare XSS
      $safe_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
      echo '<script>swal("'.$safe_message.'", "", "error");</script>';
   }
}

?>

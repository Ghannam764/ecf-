// Assicurati che il DOM sia completamente caricato prima di eseguire il codice
document.addEventListener('DOMContentLoaded', () => {
   // Seleziona la navbar e il pulsante del menu
   const navbar = document.querySelector('.header .flex .navbar');
   const menuBtn = document.querySelector('.header .flex #menu-btn');

   // Verifica che gli elementi esistano prima di aggiungere event listeners
   if (navbar && menuBtn) {
       // Aggiungi un event listener al pulsante del menu per togglare le classi
       menuBtn.addEventListener('click', () => {
           menuBtn.classList.toggle('fa-times'); // Aggiunge/rimuove la classe 'fa-times'
           navbar.classList.toggle('active'); // Aggiunge/rimuove la classe 'active'
       });

       // Aggiungi un event listener per lo scroll della finestra
       window.addEventListener('scroll', () => {
           // Se la navbar è attiva, rimuovi le classi per chiudere il menu
           if (navbar.classList.contains('active')) {
               menuBtn.classList.remove('fa-times'); // Rimuove la classe 'fa-times'
               navbar.classList.remove('active'); // Rimuove la classe 'active'
           }
       });
   }

   // Seleziona tutti gli input di tipo testo (non numerico)
   document.querySelectorAll('input[type="text"]').forEach(inputText => {
       // Aggiungi un event listener per gestire l'input dell'utente
       inputText.addEventListener('input', () => {
           // Ottieni il valore dell'attributo maxlength, se presente
           const maxLength = inputText.getAttribute('maxlength');
           // Se maxLength è definito e l'input supera la lunghezza massima, troncalo
           if (maxLength && inputText.value.length > maxLength) {
               inputText.value = inputText.value.slice(0, maxLength); // Trunca il valore
           }
       });
   });
});

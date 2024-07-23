// Seleziona la navbar
let navbar = document.querySelector('.header .navbar');

// Aggiunge un event listener al pulsante del menu per alternare la classe 'active' sulla navbar
document.querySelector('#menu-btn').onclick = () => {
   navbar.classList.toggle('active'); // Toglie o aggiunge la classe 'active' per mostrare/nascondere la navbar
}

// Aggiunge un event listener per l'evento di scroll della finestra per rimuovere la classe 'active' dalla navbar
window.onscroll = () => {
   navbar.classList.remove('active'); // Rimuove la classe 'active' quando l'utente scorre la pagina
}

// Aggiunge un event listener a ciascun elemento h3 all'interno delle faq per mostrare/nascondere il contenuto associato
document.querySelectorAll('.contact .row .faq .box h3').forEach(faqBox => {
   faqBox.onclick = () => {
      faqBox.parentElement.classList.toggle('active'); // Toglie o aggiunge la classe 'active' al genitore per mostrare/nascondere la risposta
   }
});

// Aggiunge un event listener a tutti gli input di tipo numero per limitare la lunghezza del valore in base all'attributo maxlength
document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
   inputNumber.oninput = () => {
      // Verifica se la lunghezza del valore supera il massimo consentito e tronca il valore se necessario
      if (inputNumber.value.length > inputNumber.maxLength) {
         inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength); // Trunca il valore a maxlength caratteri
      }
   }
});

// Inizializza il primo swiper per lo slider della home
var swiperHome = new Swiper(".home-slider", {
   loop: true, // Abilita il loop infinito
   effect: "coverflow", // Imposta l'effetto coverflow
   spaceBetween: 30, // Spazio tra le slide
   grabCursor: true, // Mostra il cursore a mano
   coverflowEffect: {
      rotate: 50, // Rotazione delle slide
      stretch: 0, // Allungamento delle slide
      depth: 100, // Profondità dell'effetto 3D
      modifier: 1, // Modificatore dell'effetto
      slideShadows: false, // Disabilita le ombre delle slide
   },
   navigation: {
     nextEl: ".swiper-button-next", // Selettore per il pulsante 'next'
     prevEl: ".swiper-button-prev", // Selettore per il pulsante 'prev'
   },
});

// Inizializza il secondo swiper per lo slider della galleria
var swiperGallery = new Swiper(".gallery-slider", {
   loop: true, // Abilita il loop infinito
   effect: "coverflow", // Imposta l'effetto coverflow
   slidesPerView: "auto", // Numero di slide visibili automatico
   centeredSlides: true, // Centra la slide attiva
   grabCursor: true, // Mostra il cursore a mano
   coverflowEffect: {
      rotate: 0, // Rotazione delle slide
      stretch: 0, // Allungamento delle slide
      depth: 100, // Profondità dell'effetto 3D
      modifier: 2, // Modificatore dell'effetto
      slideShadows: true, // Abilita le ombre delle slide
   },
   pagination: {
      el: ".swiper-pagination", // Selettore per la paginazione
    },
});

// Inizializza il terzo swiper per lo slider delle recensioni
var swiperReviews = new Swiper(".reviews-slider", {
   loop: true, // Abilita il loop infinito
   slidesPerView: "auto", // Numero di slide visibili automatico
   grabCursor: true, // Mostra il cursore a mano
   spaceBetween: 30, // Spazio tra le slide
   pagination: {
      el: ".swiper-pagination", // Selettore per la paginazione
   },
   breakpoints: {
      768: {
        slidesPerView: 1, // Mostra una slide per vista su schermi più piccoli
      },
      991: {
        slidesPerView: 2, // Mostra due slide per vista su schermi più grandi
      },
   },
});

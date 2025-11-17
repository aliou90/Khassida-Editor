$(document).ready(function () {
  var isSlideEnabled = false; // Variable pour suivre l'état de l'affichage en slide
  var slickInitialized = false; // Variable pour suivre l'état de l'initialisation de Slick
  var isArabicText = false; // Variable pour suivre si le texte est en arabe
  var wasReversed = false; // Variable pour suivre si les diapositives étaient inversées

  // Événement de clic sur le bouton .btn-slide
  $('.btn-slide').on('click', function () {
    toggleSlideDisplay();
  });

  // Fonction pour activer ou désactiver l'affichage en slide
  function toggleSlideDisplay() {
    if (isSlideEnabled) {
      // Désactiver l'affichage en slide
      $('.chanson-slide').slick('unslick');
      slickInitialized = false;

      // Rétablir la direction du texte et l'ordre des diapositives si nécessaire
      if (isArabicText) {
        $('.chanson-slide').css('direction', 'ltr'); // Remettre la direction en LTR
        if (wasReversed) {
          // Réorganiser les diapositives en ordre inverse (du premier au dernier)
          var slides = $('.chanson-slide .ligne');
          $('.chanson-slide').empty(); // Vider le conteneur du carrousel
          slides.each(function(index, slide) {
            $('.chanson-slide').prepend(slide);
          });
        }
      }
      // Réactiver le défilement de la page
      enablePageScroll();
    } else {
      // Activer l'affichage en slide
      setTextDirection(); // Mettre à jour la direction du texte
      // Désactiver le défilement de la page
      disablePageScroll();
    }

    // Inverser l'état de l'affichage en slide
    isSlideEnabled = !isSlideEnabled;
  }

  // Fonction pour déterminer la direction du texte en fonction de son contenu
  function setTextDirection() {
    var titleText = $('.chanson-slide .ligne').eq(0).text(); // Prend le texte du premier élément .ligne
    isArabicText = isArabic(titleText);
    var direction = isArabicText ? 'rtl' : 'ltr';

    // Réorganiser les diapositives en ordre inverse si le texte est en arabe
    if (isArabicText) {
      wasReversed = true; // Marquer que les diapositives sont inversées
      var slides = $('.chanson-slide .ligne');
      $('.chanson-slide').empty(); // Vider le conteneur du carrousel
      slides.each(function(index, slide) {
        $('.chanson-slide').prepend(slide); // Ajouter les diapositives en ordre inverse
      });
    } else {
      wasReversed = false; // Marquer que les diapositives ne sont pas inversées
    }

    // Appliquer la direction au carrousel
    $('.chanson-slide').slick({
      infinite: false, // Activer la boucle infinie
      slidesToShow: 1, // Afficher un seul slide à la fois
      slidesToScroll: 1, // Nombre de diapositives à faire défiler à la fois
      rtl: isArabicText, // Définir RTL si le texte est en arabe
      initialSlide: 0, // Définir le slide initial par défaut
      vertical: false, // Activer l'alignement vertical
      verticalSwiping: false, // Activer le défilement vertical
      centerMode: true, // Activer le mode centré
      centerPadding: '0', // Espacement autour du slide actif
      draggable: true, // Activer le glissement de la souris/tactile
      arrows: false, // Masquer les flèches de navigation (à personnaliser)
      dots: false, // Masquer les indicateurs de pagination (à personnaliser)
        // Autres options de personnalisation
      afterChange: function () {
        if (slickInitialized) {
          setTextDirection();
        }
      }
    });

    slickInitialized = true; // Marquer Slick comme initialisé
    $('.chanson-slide').css('direction', direction);
  }


  // Vérifier la direction du texte lors du redimensionnement de la fenêtre
  $(window).on('resize', function () {
    if (slickInitialized) {
      setTextDirection();
    }
  });


  // Fonction pour désactiver le défilement de la page
function disablePageScroll() {
  $('body').css('overflow', 'hidden');
}

// Fonction pour réactiver le défilement de la page
function enablePageScroll() {
  $('body').css('overflow', 'auto');
}

// FONCTION DE VÉRIFICATION DE TEXTE ARABE POUR LA FONCTION EN DESSUS
function isArabic(str) {
  var arabicLetters = /[\u0600-\u06FF]/;
  return arabicLetters.test(str);
}

});

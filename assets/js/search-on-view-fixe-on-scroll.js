$(document).ready(function() {
    var menuFixeHeight = $('.menu-fixe').outerHeight(); // Hauteur du menu fixé
    var songItemWidth = $('.card').width(); // Largeur de .song-item
  
    // Appliquer la largeur initiale de .song-item au search-container
    $('.search-container').css('width', songItemWidth + 'px');
  
    $(window).scroll(function() {
      var scrollPosition = $(window).scrollTop();
      var songItemWidth = $('.card').width(); // Largeur de .song-item
  
      // Si la position de défilement atteint la hauteur du menu fixé
      if (scrollPosition >= menuFixeHeight) {
        $('.search-container').css({
          'position': 'fixed',
          'top': menuFixeHeight + 'px',
          'width': songItemWidth + 'px',
          'z-index': '500',
          'left': '0',
          'right': '0'
        });
      } else {
        // Si la position de défilement est en dessous de la hauteur du menu fixé
        $('.search-container').css({
          'position': 'relative',
          'top': '0',
          'left': '0',
          'right': '0',
          'width': songItemWidth + 'px',
          'z-index': '500'
        });
      }
    });
  });
  
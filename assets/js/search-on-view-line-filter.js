$(document).ready(function() {
  var isFiltered = false; // Variable pour suivre l'état de filtrage

  // Gestionnaire d'événements de clic pour le bouton .btn-line-filter
  $('.btn-line-filter').on('click', function() {
    if (isFiltered) {
      // Si les éléments sont filtrés, les réafficher
      $('.ligne').show();
    } else {
      // Sinon, les cacher (en excluant ceux qui contiennent .highlight)
      $('.ligne:not(:has(.highlight))').hide();

      // Afficher les chapitres 
      //$('.chapitre').show();
    }
    
    // Inverser l'état de filtrage
    isFiltered = !isFiltered;
  });
});

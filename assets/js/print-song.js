// Imprimer
function printChanson() {
    // Masquer toutes les sections sauf celle de la chanson
    $('body > *:not(.chanson-title, .chanson-content)').hide();
    // Lancer l'impression
    window.print();
    // Réafficher toutes les sections
    $('body > *:not(.chanson-title, .chanson-content)').show();
}
      
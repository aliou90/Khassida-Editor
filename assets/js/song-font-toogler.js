// CHANGEMENT DES FONTS D'ÉCRITURE DE L'OEUVRE EN VISION
$(document).ready(function() {
    $('.btn-font').click(function() {
        var font = $(this).val();
        $('.chanson-titre, .chanson-content').css('font-family', font);

        // Réinitialiser la bordure pour tous les boutons
        $('.btn-font').removeClass('selected-font-button');

        // Appliquer la bordure au bouton sélectionné
        $(this).addClass('selected-font-button');

        // Ajuster la taille de la police en fonction de la police sélectionnée
        if (font == "macca") {
            $('.chanson-titre, .chanson-content').css('font-size', '1.2rem');
        }
        if (font == "madina") {
            $('.chanson-titre, .chanson-content').css('font-size', '1.4rem');
        }
        if (font == "touba") {
            $('.chanson-titre, .chanson-content').css('font-size', '1.1rem');
        }
        if (font == "ndiarem") {
            $('.chanson-titre, .chanson-content').css('font-size', '1.1rem');
        }
    });
});

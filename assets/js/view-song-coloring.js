// MISE EN ÉVIDENCE DU NOM D'ALLAH ET DU PROPHÈTE(PSL)
$(document).ready(function() {
$('.circled').each(function() {
    var arabaicAllah =/(?!اللَّهُمَّ|اللَّهَم|اللهم|اللَّهِم|اللَّهٌم|اللَّهْم|اللّهم|اللَّهم|اللَّهُم|اللَّهَم|اللَّهِم|اللَّهْم|اللَهم)(?: لِلهِ| اُ۬للَّهَ|اُ۬للَّهِ|اُ۬للَّهُ|اُ۬للَّهَ|اُ۬للَّهِ|اُ۬للَّهُ|اَ۬للَّهَ|اَ۬للَّهِ|اَ۬للَّهُ|اِ۬للَّهُ|اِ۬للَّهَ|اِ۬للَّهِ| اللَّهَ|اللَّهِ|اللَّهٌ|اللَّهْ|اللّه|اللَّه|اللَّهُ|اللَّهَ|اللَّهِ|اللَّهْ|اللَه|ٱللَّٰه|الله|ألله)/gi;
    var arabaicMouhamad =/مُحَمَّد(?![ا_َُِْ\p{L}])/ugi;
    var arabaicKhadim =/خَدِيم(?![ا_َُِْ\p{L}])/ugi;
    var allahRegex = /\b(?:Allah|ALLAH|Dieu|God)\b/gi; // 'g' pour recherche globale et 'i' pour insensible à la casse
    var mouhamadRegex = /\b(?:Mouhammad|Mouhamad|Mahomet)\b/gi; // 'g' pour recherche globale et 'i' pour insensible à la casse
    
    var text = $(this).text();        
    text = text.replace(arabaicAllah, '<span style="color:red;font-weight:bold">$&</span>');
    text = text.replace(arabaicMouhamad, '<span style="color:blue;font-weight:bold">$&</span>');
    text = text.replace(arabaicKhadim, '<span style="color:green;font-weight:bold">$&</span>');            
    text = text.replace(allahRegex, '<span style="color:red;font-weight:bold">$&</span>');
    text = text.replace(mouhamadRegex, '<span style="color:blue;font-weight:bold">$&</span>');
    $(this).html(text);
});
});




/**
 * // RECHERCHER DANS L'OEUVRE
$(document).ready(function() {
        // Gestion de la recherche
        $('#search').on('input', function() {
            // Cacher le bouton d'impression
            $('.btnPrint').hide();
            var searchTerm = $(this).val();
            $('.ligne').hide();
            var nbCorrespondances = 0;
            if (searchTerm) {
                $('.colonne').each(function() {
                    var colonne = $(this).text();
                    if (colonne.indexOf(searchTerm.toLowerCase()) != -1) {
                        var regex = new RegExp(searchTerm, "gi");
                        colonne = colonne.replace(new RegExp(searchTerm, "gi"), function(match) {
                            // Vérifier si la correspondance est en majuscules
                            if (match === searchTerm.toUpperCase()) {
                                return '<span style="color: red;"><b>' + searchTerm.toUpperCase() + '</b></span>';
                            }
                            // Vérifier si la correspondance est en minuscules
                            if (match === searchTerm.toLowerCase()) {
                                return '<span style="color: red;"><b>' + searchTerm.toLowerCase() + '</b></span>';
                            }
                            // Sinon, retourner la correspondance avec la casse d'origine
                            return '<span style="color: red;"><b>' + match + '</b></span>';
                            });

                        $(this).html(colonne);
                        $(this).closest('.ligne').show();
                        nbCorrespondances++;
                    }
                });
            } else {
                $('.ligne').show();
            }
            $('#nb-correspondances').text('Trouvé(s): '+nbCorrespondances);
        });
    });

 */
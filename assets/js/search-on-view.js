//FONCTION DE RECHERCHE ET MISE EN ÉVIDENCE
$(document).ready(function() {
    // Cacher le texte resultat et le bouton .btn-line-filter en utilisant jQuery
    $('#nb-correspondances').hide();
    $('.btn-line-filter').hide();
    // Gestion de la recherche
    $('#search').on('input submit', function() {
    var searchTerm = $(this).val().trim().toLowerCase();
    //Actualiser la ligne avant de la cacher
    $('.colonne').each(function() {
        // Mise en évidences des noms sacrés d'Allah et du Prophète(PSL)
        highlightText();
        //Colonne mise en évidence
        var colonne = $(this).html();
        $(this).html(colonne);
    });

    /** --------------------------------- TRÈS INTÉRESSANT  -----------------------------------------------------------------
     * Décommenter pour Cacher tous les lignes avant de rechercher et filtrer les corespondantes à la recherche 
    */
    //$('.ligne').hide(); 
    //$('.chapitre').show(); 
    /**    ---------------------------------------------------------------------------------------------------------------------- */


    var nbCorrespondances = 0; // initialiser le nombre de correspondances à 0
    if (searchTerm && searchTerm.trim()) {
        $('.colonne').each(function() {
            //Colonne mise en évidence
            var colonne = $(this).text();
                // Vérifier si le terme recherché et la colonne sont en arabe
                if (isArabic(searchTerm) && isArabic(colonne)) {
                    var searchTermWithoutHarakat = searchTerm.replace(/[\u064B-\u065F۬ۥٰٓ]/g, '');
                    var colonneWithoutHarakat = colonne.replace(/[\u064B-\u065F۬ۥٰٓ]/g, '');
                    
                    // Si le terme n'a pas de harakat, enlever les harakats du terme recherché et de la colonne
                    if (searchTermWithoutHarakat === searchTerm) {
                        searchTerm = searchTermWithoutHarakat;
                        colonne = colonneWithoutHarakat;
                    }

                    // Rechercher le terme sur la colonne
                    var correspondances = (colonne.match(new RegExp(searchTerm.replace(/(.)/g, '$1[\u064B-\u065F۬ۥٰٓ]*'), "gi")) || []).length;
                    if (correspondances > 0) {
                        // Mettre en évidence le terme sur la colonne
                        colonne = $(this).html();
                        colonne = colonne.replace(new RegExp(searchTerm.replace(/(.)/g, '$1[\u064B-\u065F۬ۥٰٓ]*'), "gi"), '<span class="highlight">$&</span>');

                        // Afficher la colonne avec la mise en évidence
                        $(this).html(colonne);
                        $(this).closest('.ligne').show();
                        nbCorrespondances += correspondances; // incrémenter le nombre de correspondances total
                    }
                } else {
                    // Si ce n'est pas en arabe, rechercher le terme sur la colonne en tenant compte de la casse
                    var correspondances = (colonne.match(new RegExp(searchTerm, "gi")) || []).length;
                    if (correspondances > 0) {
                        // Mettre en évidence la colonne en respectant la casse du mot recherché
                        colonne = colonne.replace(new RegExp(searchTerm, "gi"), '<span class="highlight">$&</span>');
                        // Mise en évidences des noms sacrés d'Allah et du Prophète(PSL)
                        //highlightText();
                        // Afficher la colonne avec la mise en évidence
                        $(this).html(colonne);
                        $(this).closest('.ligne').show();                                    
                        nbCorrespondances += correspondances; // incrémenter le nombre de correspondances total
                    }
                }
            });

            // Afichage Texte resultat et bouton filtre
            $('#nb-correspondances').show();
            if (nbCorrespondances > 0) {
                $('.btn-line-filter').show();
            }else {
                $('.btn-line-filter').hide();
            }
        } else {
            // Mise en évidences des noms sacrés d'Allah et du Prophète(PSL)
            highlightText();
            $('.ligne').show();
            // Afichage Texte resultat et bouton filtre
            $('#nb-correspondances').hide();
            $('.btn-line-filter').hide();
        }

        $('#nb-correspondances').text('Trouvé(s): '+nbCorrespondances+' ');         
    });
});
    

// FONCTION DE VÉRIFICATION DE TEXTE ARABE POUR LA FONCTION EN DESSUS
function isArabic(str) {
var arabicLetters = /[\u0600-\u06FF]/;
return arabicLetters.test(str);
}

function highlightText() {
    $('.colonne').each(function() {
      var arabaicAllah =/(?!اللَّهُمَّ|اللَّهَم|اللَّهِم|اللَّهٌم|اللَّهْم|اللّهم|اللَّهم|اللَّهُم|اللَّهَم|اللَّهِم|اللَّهْم|اللَهم)(?: لِلهِ| اُ۬للَّهَ|اُ۬للَّهِ|اُ۬للَّهُ|اُ۬للَّهَ|اُ۬للَّهِ|اُ۬للَّهُ|اَ۬للَّهَ|اَ۬للَّهِ|اَ۬للَّهُ|اِ۬للَّهُ|اِ۬للَّهَ|اِ۬للَّهِ| اللَّهَ|اللَّهِ|اللَّهٌ|اللَّهْ|اللّه|اللَّه|اللَّهُ|اللَّهَ|اللَّهِ|اللَّهْ|اللَه|ٱللَّٰه|الله|ألله)/g;
      var arabaicMouhamad =/مُحَمَّد(?![ا_َُِْ\p{L}])/ug;
      var arabaicKhadim =/خَدِيم(?![ا_َُِْ\p{L}])/ug;
      var allahRegex = /\b(?:Allah|ALLAH|Dieu|God)\b/g;
      var mouhamadRegex = /\b(?:Mouhammad|Mouhamad|Mahomet)\b/g;
      var text = $(this).text();        
      text = text.replace(arabaicAllah, '<span style="color:red;font-weight:bold">$&</span>');
      text = text.replace(arabaicMouhamad, '<span style="color:blue;font-weight:bold">$&</span>');
      text = text.replace(arabaicKhadim, '<span style="color:green;font-weight:bold">$&</span>');            
      text = text.replace(allahRegex, '<span style="color:red;font-weight:bold">$&</span>');
      text = text.replace(mouhamadRegex, '<span style="color:blue;font-weight:bold">$&</span>');
      $(this).html(text);
    });
  }
  
    
    
    
    
    
    
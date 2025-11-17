function recherche_chansons_par_categorie() {
    // Récupérer le texte saisi par l'utilisateur
    var recherche = $('#search-input').val().trim().toLowerCase();
    
    // Parcourir chaque chanson de la catégorie
    var nbCorrespondances = 0; // initialiser le nombre de correspondances à 0

    //Actualiser la ligne avant de la cacher
    $('.song-info').each(function() {
        // Mise en évidence des noms sacrés d'Allah et du Prophète(PSL) avant la boucle
        highlightText();
        // Colonne mise en évidence
        var titre = $(this).find('h3').html();
        var introduction = $(this).find('p').html();
        // Afficher la colonne avec la mise en évidence
        $(this).find('h3').html(titre); // Mettre à jour uniquement le titre
        $(this).find('p').html(introduction); // Mettre à jour uniquement l'introduction
    });

    if (recherche && recherche.trim()) {
        $('.song-info').each(function() {
            // Colonne mise en évidence
            var titre = $(this).find('h3').text();
            var introduction = $(this).find('p').text();
            var originalTitre = titre; // Garder le titre d'origine
            var originalIntroduction = introduction; // Garder l'introduction d'origine
            
            // Vérifier si le terme recherché et la colonne sont en arabe
            if (isArabic(recherche) && (isArabic(titre) || isArabic(introduction))) {
                var rechercheWithoutHarakat = recherche.replace(/[\u064B-\u065F۬ۥٰٓ]/g, '');
                var titreWithoutHarakat = titre.replace(/[\u064B-\u065F۬ۥٰٓ]/g, '');
                var introductionWithoutHarakat = introduction.replace(/[\u064B-\u065F۬ۥٰٓ]/g, '');
                
                // Si le terme a des harakat, enlever les harakats du terme recherché et de la colonne
                if (rechercheWithoutHarakat === recherche) {
                    recherche = rechercheWithoutHarakat;
                    titre = titreWithoutHarakat;
                    introduction = introductionWithoutHarakat;
                }

                // Rechercher le terme sur la colonne
                var correspondances1 = (titre.match(new RegExp(recherche.replace(/(.)/g, '$1[\u064B-\u065F۬ۥٰٓ]*'), "gi")) || []).length;
                var correspondances2 = (introduction.match(new RegExp(recherche.replace(/(.)/g, '$1[\u064B-\u065F۬ۥٰٓ]*'), "gi")) || []).length;

                if (correspondances1 > 0 || correspondances2 > 0) {
                    // Mettre en évidence le terme sur la colonne
                    titre = originalTitre.replace(new RegExp(recherche.replace(/(.)/g, '$1[\u064B-\u065F۬ۥٰٓ]*'), "gi"), '<span class="highlight">$&</span>');
                    introduction = originalIntroduction.replace(new RegExp(recherche.replace(/(.)/g, '$1[\u064B-\u065F۬ۥٰٓ]*'), "gi"), '<span class="highlight">$&</span>');

                    // Afficher la colonne avec la mise en évidence
                    $(this).find('h3').html(titre); // Mettre à jour uniquement le titre
                    $(this).find('p').html(introduction); // Mettre à jour uniquement l'introduction
                    $(this).closest('.song-item').show();
                    nbCorrespondances += correspondances1; // incrémenter le nombre de correspondances total
                    nbCorrespondances += correspondances2; // incrémenter le nombre de correspondances total
                } else {
                    $(this).closest('.song-item').hide();
                }
            } else {
                // Si ce n'est pas en arabe, rechercher le terme sur la colonne en tenant compte de la casse
                var correspondances1 = (titre.match(new RegExp(recherche, "gi")) || []).length;
                var correspondances2 = (introduction.match(new RegExp(recherche, "gi")) || []).length;

                if (correspondances1 > 0 || correspondances2 > 0) {
                    // Mettre en évidence la colonne en respectant la casse du mot recherché
                    titre = originalTitre.replace(new RegExp(recherche, "gi"), '<span class="highlight">$&</span>');
                    introduction = originalIntroduction.replace(new RegExp(recherche, "gi"), '<span class="highlight">$&</span>');
                    
                    // Afficher la colonne avec la mise en évidence
                    $(this).find('h3').html(titre); // Mettre à jour uniquement le titre
                    $(this).find('p').html(introduction); // Mettre à jour uniquement l'introduction
                    $(this).closest('.song-item').show();                                 
                    nbCorrespondances += correspondances1; // incrémenter le nombre de correspondances total
                    nbCorrespondances += correspondances2; // incrémenter le nombre de correspondances total
                } else {
                    $(this).closest('.song-item').hide();
                }
            }
        });
        // Appeler la fonction de défilement une fois la recherche terminée
        scrollToFirstMatch();
    } else {
        // Mise en évidence des noms sacrés d'Allah et du Prophète(PSL) avant la boucle
        highlightText();
        $('.song-item').show();
    }
}

// Appeler la fonction lorsque l'utilisateur saisit du texte dans l'input de recherche
$('#search-input').on('input', recherche_chansons_par_categorie);

function scrollToFirstMatch() {
    var firstMatch = $('.song-item:visible').first(); // Sélectionne le premier élément visible après la recherche
    if (firstMatch.length) {
        var offset = firstMatch.offset().top;
        var searchBarHeight = $('#search-input').outerHeight(); // Hauteur de la barre de recherche
        var scrollPosition = offset - searchBarHeight - 150; // Ajuster pour compenser la hauteur de la barre de recherche

        $('html, body').animate({ scrollTop: scrollPosition }, 300); // Défilement doux
    }
}


// FONCTION DE VÉRIFICATION DE TEXTE ARABE POUR LA FONCTION EN DESSUS
function isArabic(str) {
    var arabicLetters = /[\u0600-\u06FF]/;
    return arabicLetters.test(str);
}

function highlightText() {
    $('.song-info').find('h3, p').each(function() {
        var text = $(this).text();
        var originalWords = text.split(/(\s+)/); // Conserve les espaces et séparateurs

        var highlightedWords = originalWords.map(word => {
            const normalized = normalizeText(word);

            // Noms isolés en arabe sans harakats
            if (/^(?:الله|اللّه|ٱللٰه)$/.test(normalized)) {
                return '<span style="color:red;font-weight:bold">' + word + '</span>';
            }
            if (/^محمد$/.test(normalized)) {
                return '<span style="color:blue;font-weight:bold">' + word + '</span>';
            }
            if (/^خديم$/.test(normalized)) {
                return '<span style="color:green;font-weight:bold">' + word + '</span>';
            }

            // Noms isolés en lettres latines
            if (/^(allah|dieu|god)$/i.test(normalized)) {
                return '<span style="color:red;font-weight:bold">' + word + '</span>';
            }
            if (/^(mouhammad|mouhamad|mahomet)$/i.test(normalized)) {
                return '<span style="color:blue;font-weight:bold">' + word + '</span>';
            }

            return word;
        });

        $(this).html(highlightedWords.join(''));
    });
}

// Fonction de normalisation de texte
function normalizeText(text) {
    return text
        .normalize("NFD")                          // Décompose les lettres accentuées
        .replace(/[\u0300-\u036f]/g, "")           // Supprime les accents latins
        .replace(/[\u064B-\u065F\u0610-\u061A]/g, "") // Supprime les harakats arabes + signes supplémentaires
        .replace(/[\u200c\u200d]/g, '')            // Supprime zwj/zwnj
        .replace(/[^\p{L}]/gu, '')                 // Garde uniquement les lettres
        .toLowerCase();
}


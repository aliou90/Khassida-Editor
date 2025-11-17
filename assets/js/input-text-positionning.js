$('input').on('input', function() {
  var text = $(this).val(); // Récupérer le texte saisi
  var alignClass = isArabic(text) ? 'text-align-right' : 'text-align-left';
  var dir = isArabic(text) ? 'rtl' : 'ltr';

  // Aligner l'input
  $(this).removeClass('text-align-left text-align-right').addClass(alignClass);
  $(this).attr('dir', dir); // Définir la direction du texte

  // Aligner l'élément avec id="nb-correspondances"
  $('#nb-correspondances').removeClass('text-align-left text-align-right').addClass(alignClass);
  $('#nb-correspondances').attr('dir', dir); // Définir la direction du texte
});



function isArabic(text) { /* FONCTION DE VÉRIFICATION DE LA LANGUE ARABE */
  // Expression régulière pour vérifier les caractères arabes
  const arabicRegex = /[\u0600-\u06FF]/;

  // Vérifier si le texte contient des caractères arabes
  return arabicRegex.test(text);
}
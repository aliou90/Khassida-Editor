$(document).ready(function() {
    $('#btn-lang').click(function() {
      var lang = $(this).text();
      if (lang == 'EN') {
        $(this).text('AR');
        $('input[type="text"], textarea, select, label').css('direction', 'rtl');
        $('html').attr('lang', 'ar');
        $('form[id="add-song-frm"]').attr('dir', 'rtl');
        //$('h1[id="add-song-ttl"]').text('إضافة كتاب جديد');
        //$('label[for="title"]').text('العنوان :');
        //$('label[for="intro"]').text('المقدمة :');
        //$('label[for="verses"]').text('التطور في الآيات :');
        //$('label[for="conclusion"]').text('الخلاصة :');
        //$('label[for="category"]').text('الفئة :');
        //$('option[value=""]').text('-- اختر فئة --');
        $('option[value="quran"]').text('القرآن');
        $('option[value="zikr"]').text('دعآء - شكر - ذكر اللّه ﷻ');
        $('option[value="salat"]').text('صلاة على نبي ﷺ');
        $('option[value="madh"]').text('مدح النبي ﷺ');
        $('option[value="hilm"]').text('علم الإسلاميه');
        $('option[value="wasiya"]').text('وصية');
        //$('button[id="add-song-btn"]').text('إضافة');
      } else if (lang == 'AR') {
        $(this).text('FR');
        $('input, textarea, select, label').css('direction', 'ltr');
        $('html').attr('lang', 'fr');
        $('form[id="add-song-frm"]').attr('dir', 'ltr');
        //$('h1[id="add-song-ttl"]').text('Ajouter une nouvelle œuvre');
        //$('label[for="title"]').text('Titre :');
        //$('label[for="intro"]').text('Introduction :');
        //$('label[for="verses"]').text('Développement :');
        //$('label[for="conclusion"]').text('Conclusion :');
        //$('label[for="category"]').text('Catégorie :');
        //$('option[value=""]').text('-- Sélectionner une catégorie --');
        $('option[value="quran"]').text('Quran');
        $('option[value="zikr"]').text('Zikr - Duha - Shukr');
        $('option[value="salat"]').text('Salatu Halan Nabiy');
        $('option[value="madh"]').text('Madhun Nabiy');
        $('option[value="hilm"]').text('Hilmul Islamiya');
        $('option[value="wasiya"]').text('Wasiya');
        //$('button[id="add-song-btn"]').text('Ajouter');
      } else {
        $(this).text('EN');
        $('input, textarea, select, label').css('direction', 'ltr');
        $('html').attr('lang', 'en');
        $('form[id="add-song-frm"]').attr('dir', 'ltr');
        //$('h1[id="add-song-ttl"]').text('Ajouter une nouvelle œuvre');
        //$('label[for="title"]').text('Titre :');
        //$('label[for="intro"]').text('Introduction :');
        //$('label[for="verses"]').text('Développement :');
        //$('label[for="conclusion"]').text('Conclusion :');
        //$('label[for="category"]').text('Catégorie :');
        //$('option[value=""]').text('-- Sélectionner une catégorie --');
        $('option[value="quran"]').text('Quran');
        $('option[value="zikr"]').text('Zikr - Duha - Shukr');
        $('option[value="salat"]').text('Salatu Halan Nabiy');
        $('option[value="madh"]').text('Madhun Nabiy');
        $('option[value="hilm"]').text('Hilmul Islamiya');
        $('option[value="wasiya"]').text('Wasiya');
        //$('button[id="add-song-btn"]').text('Ajouter');
      }
    });
  });
  
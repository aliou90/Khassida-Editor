<?php
// On définit les langues disponibles
$langs = array('fr', 'ar', 'en');

// On détecte la langue de l'utilisateur
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if (!in_array($lang, $langs)) {
        $lang = 'fr'; // Langue par défaut
    }
} else {
    $lang = 'fr'; // Langue par défaut
}

// On définit la locale correspondante
switch ($lang) {
    case 'fr':
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $locale = 'fr_FR'; // Locale pour IntlDateFormatter
        break;
    case 'ar':
        setlocale(LC_TIME, 'ar_MA.UTF-8');
        $locale = 'ar_MA'; // Locale pour IntlDateFormatter
        break;
    case 'en':
        setlocale(LC_TIME, 'en_US.UTF-8');
        $locale = 'en_US'; // Locale pour IntlDateFormatter
        break;
    default:
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        $locale = 'fr_FR'; // Locale pour IntlDateFormatter par défaut
}

// Vérifiez si l'extension Intl est activée et si la version de PHP est compatible
if (class_exists('IntlDateFormatter')) {
    $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    $date = $formatter->format(new DateTime());
} else {
    // Si IntlDateFormatter n'est pas disponible, utilisez strftime() pour PHP < 8.1
    $date = strftime("%A %e %B %Y"); // Ex: "dimanche 4 août 2024"
}

// Affiche la date dans la langue de l'utilisateur
//echo "Date formatée : " . $date;

// Optionnel : Affiche la langue détectée
// echo "Langue détectée : " . $lang;
?>

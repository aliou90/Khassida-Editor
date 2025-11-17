<?php
function isArabic($data){
    // Vérifie si le premier vers est en langue arabe
    return preg_match('/\p{Arabic}/u', $data); 
}

// Supprimer partie non arabe
function removeNonArabic($text) {
    // Utiliser preg_replace pour supprimer les caractères non arabes
    $filteredText = preg_replace('/[^\x{0600}-\x{06FF}]+/u', ' ', $text);
    // Supprimer les espaces supplémentaires et faire un trim
    return trim(preg_replace('/\s+/', ' ', $filteredText));
}

// Supprimer partie arabe
function removeArabic($text) {
    // Utiliser preg_replace pour supprimer les lettres arabes
    $filteredText = preg_replace('/[\x{0600}-\x{06FF}]+/u', ' ', $text);
    // Supprimer les espaces supplémentaires et faire un trim
    return trim(preg_replace('/\s+/', ' ', $filteredText));
}

// Compter nombre de lettres et mots non arabes
function countNonArabicText($getedTexte) {
    $words = str_word_count(trim($getedTexte));
    $letters = strlen(trim($getedTexte));
    // Retourner les résultats sous forme de tableau associatif
    return array(
        'letters' => $letters,
        'words' => $words
    );
} 

// Compter nombre de lettres et mots arabes
function countArabicText($texte) {
    // Filtrer et récupérer texte
    $texte = extractTextFromHtml($texte);

    // Rechercher et supprimer le texte entre <<< et >>> (Avant Propos)
    $texte = preg_replace('/<<<(.*?)>>>/s', '', $texte);
    $texte = preg_replace('/&lt;&lt;&lt;(.*?)&gt;&gt;&gt;/s', '', $texte);

    // Supprimer les balises HTML en utilisant une expression régulière
    $texte = strip_tags($texte);

    // Supprimer les harakats en utilisant une expression régulière
    $texte_sans_harakats = preg_replace('/\p{M}/u', '', $texte);

    // Compter le nombre de caractères arabes restants
    $letters = preg_match_all('/\p{Arabic}/u', $texte_sans_harakats, $matches);
    $letters = count($matches[0]);

    // Compter le nombre de mots arabes en séparant le texte par des espaces
    $words = preg_match_all('/\p{Arabic}+/u', $texte_sans_harakats, $matches);
    $words = count($matches[0]);
    
    // Retourner les résultats sous forme de tableau associatif
    return array(
        'letters' => $letters,
        'words' => $words
    );
}


// ENLEVER HARAKATS
function removeHarakat($text) {
    // Supprimer les harakats en utilisant une expression régulière
    $text = preg_replace('/\p{M}/u', '', $text);
    return $text;
}

// Fonction pour Détecter le HTML
function containsHtml($str) {
    return $str != strip_tags($str);
}

// Extraire le texte d'un bloc html en remplçant les balises par des espaces
function extractTextFromHtml($htmlContent) {
    // Utiliser preg_replace pour remplacer les balises HTML par des espaces
    // L'expression régulière suivante correspond à toutes les balises HTML
    $textWithSpaces = preg_replace('/<[^>]+>/', ' ', $htmlContent);

    // Supprimer les espaces supplémentaires (si plusieurs espaces consécutifs existent)
    $textWithSpaces = preg_replace('/\s+/', ' ', $textWithSpaces);

    // Retourner le texte avec les espaces appropriés
    return trim($textWithSpaces);
}
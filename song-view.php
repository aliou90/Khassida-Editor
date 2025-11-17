<?php
// Vérification de la présence de l'ID de la chanson
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header('Location: 404.html');
    exit();
}

/**--- ENTËTE DU SITE --------------------- */
require_once('header.php'); 

?>
<?php
/* ----------------------------------------------------------------------
    VÉRIFICATION DE L'ÉXISTANCE DES RECHERCHES ET DES DROITS
-------------------------------------------------------------------------*/
// Récupération de la chanson correspondante à l'ID
$chanson = rechercher_chansons_par_id($pdo,  $_GET['id'] ?? $_POST['id']);

// Redirection vers la page de recherche si la chanson n'existe pas
if (!$chanson) {
    header('Location: 404.html');
    exit();
}

/* ---------------------------------------------------------------------------
    PARAMÉTRAGE ET AFFICHAGES DES ENTËTES ET VÉRIFICATION DES SESSION ACTIVES
-------------------------------------------------------------------------------*/
// Titre de la page
$page_title = $chanson['title'];
?>

<?php
/* ----------------------------------------------------------------------
    INITIALISATION DES VARIABLES DE L'OEUVRE ET VARIABLES D'AFFICHAGE
-------------------------------------------------------------------------*/
// Compter le nombre de lettres & Compter le nombre de mots
$texte = $chanson['content'] ?? '';

if (!empty($texte) && $texte != 'Aucun contenu') {
    // Vérifier si le texte est en arabe
    $isArabic = isArabic($texte);
    if ($isArabic) {
        $arabicText = countArabicText($texte);
        $letters = $arabicText['letters'];
        $words = $arabicText['words'];
    } else {
        $letters = strlen($texte);
        $words = str_word_count($texte);
    }
} else {
    // Si $texte est null ou vide, définir les valeurs par défaut
    $letters = 0;
    $words = 0;
}

// Réinitialiser le compteur de vers
$vers_count = NULL; 
?>

<?php
/* ----------------------------------------------------------------------
    BOUTONS D'ACTION 
-------------------------------------------------------------------------*/
?>

<div class="container view-btn mt-3 mb-0">
<div class="col-6 view-btn-lft m-0 p-0">
  <div class="btn-group">
    <button type="button" class="btn btn-outline-primary dropdown-toggle" id="optionsDropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Options
    </button>
    <div class="dropdown-menu" id="optionsDropdown-menu" aria-labelledby="optionsDropdown">
        <a href="profile.php" 
            class="dropdown-item myCollectionLink">
            <?= $collection_svg ;?>
        </a> 
        <?php // BOUTON IMPRIMER ?>
        <button onclick="printChanson()" id="btnPrint" class="dropdown-item"><?= $print_svg ;?></button>
        <?php // Si l'utilisateur est éditeur ?>
        <a href="song-edit.php?id=<?=$chanson['id'] ;?>" class="dropdown-item"><?= $edit_song_svg ;?></a>
    </div>
  </div>
</div>

<?php
/* ----------------------------------------------------------------------
    BOUTONS DES STYLES DE CARACTÈRE
-------------------------------------------------------------------------*/
?>
<div class="col-6 d-flex form-group view-btn-rgt m-0 p-0">
    <?php // Style par défaut selon la catégorie
    include('includes/song_font_by_category.php'); ?>

    <div class="d-flex">
        <button id="macca-btn" class="btn btn-font" value="macca"><?= $font_active_btn2_svg; ?></button>
        <button id="madina-btn" class="btn btn-font" value="madina"><?= $font_active_btn2_svg; ?></button>
        <button id="touba-btn" class="btn btn-font" value="touba"><?= $font_active_btn3_svg; ?></button>
        <button id="ndiarem-btn" class="btn btn-font" value="ndiarem"><?= $font_active_btn4_svg; ?></button>

        <?php if($chanson['category'] != $categorie_clefs[0]): ?>
        <button class="btn btn-outline-success btn-slide" value="ndiarem"><?= $slide_active_btn_svg; ?></button>
        <?php endif; ?>
    </div>
</div>
<!-- FIN BOUTONS D'ACTION ET STYLES-->
</div>


<?php
/* ----------------------------------------------------------------------
    ZONE D'AFFICHAGE DE L'OEUVRE
-------------------------------------------------------------------------*/
?>
<?php 
/* ----------------------------------------------------------------------
    AFFICHAGE DU TITRE DE L'OEUVRE ET DE LA BARE DE RECHERCHE
-------------------------------------------------------------------------*/  
?>
<?php $isArabic = isArabic($chanson['title']); // Vérifier si le titre est en arabe ?>
<!-- TITRE DE L'OEUVRE -->
<div class="container chanson-box chanson-title" <?php if ($isArabic){ echo 'dir="rtl"'; } else { echo 'dir="ltr"'; }?>>
    <?php
    // Diviser le titre en parties avec '---' comme séparateur
    $parts = explode('---', $chanson['title']);
    
    // Boucler sur chaque partie du titre et l'afficher sur une ligne distincte
    foreach ($parts as $part) {
        echo '<h3 class="text-center chanson-titre fs-1 circled">' . htmlspecialchars($part) . '</h3>';
    }
    ?>
</div>

<!-- BARE DE RECHERCHE  -->
<div class="container chanson-box search-container"  <?php if ($isArabic){ echo 'dir="rtl"'; } else { echo 'dir="ltr"'; }?>>
    <input type="text" id="search" name="search" placeholder="Rechercher dans cette œuvre" class="form-control w-100 bg-transparent h-25">
    <div class=" search-result-options">
        <!-- BOUTONS SLIDE  -->
        <button class="btn btn-outline-primary btn-line-filter"><?= $filter_search_results_svg; ?></button>
        <span id="nb-correspondances"></span>
    </div>
</div>

<?php 
/* ----------------------------------------------------------------------
    AFFICHAGE DU CONTENU DE L'OEUVRE
-------------------------------------------------------------------------*/  
?>

<div class="container chanson-box chanson-content chanson-slide">
<?php if(!empty($chanson['content']) && $chanson['content'] != 'Aucun contenu'): // DEBUT S'il y'a du contenu, l'afficher ?>

<?php $isArabic = isArabic($chanson['content']); ?>
<?php $introduction = explode("\n", $chanson['content']); ?>
<?php 
$highlightAction = false; // Variable pour rechercher et mettre en gris italic l'avant-propos 
$highlightActionfinised = false;
?>
<?php foreach ($introduction as $ligne): ?>
  <?php // Debut Mise en italic et en gris de l'avant propos 
    if (strpos($ligne, '<<<') !== false || strpos($ligne, '&lt;&lt;&lt;') !== false) {
      $highlightAction = true;
      $ligne = preg_replace('/<<</s', '', $ligne);
      $ligne = preg_replace('/&lt;&lt;&lt;/s', '', $ligne);
      $ligne = trim(($ligne));
    }
    if (strpos($ligne, '>>>') !== false || strpos($ligne, '&gt;&gt;&gt;') !== false) {
      $ligne = preg_replace('/>>>/s', '', $ligne); 
      $ligne = preg_replace('/&gt;&gt;&gt;/s', '', $ligne);
      $ligne = trim(($ligne));
      $highlightActionfinised = true;
    }
  ?>
<?php
  // Vérification si cette ligne est un vers
 if(strpos($ligne, '---') !== false) {
    $vers = explode('---', $ligne);
?>
  <?php
    if(count($vers) == 5){
      $vers_count+=1;
      // Vérifie si le premier vers est en langue arabe
      $isArabic = isArabic($vers[0]);
    ?>
        <div class="ligne slide row " <?php if ($isArabic){ echo 'dir="rtl"'; } else { echo 'dir="ltr"'; }?> <?php if ($highlightAction){ echo ' style="font-style: italic; color: gray;" '; } ?>>
        <?php //echo '<em class="numLigne">'.($numline+=1).'</em>';?>
        <div class="row">
            <div class="colonne col-md-6 col-sm-6 col-xs-12 col-xxs-12 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[0])) ;?></div></div>
            <div class="colonne col-md-6 col-sm-6 col-xs-12 col-xxs-12 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[1])) ;?></div></div>                        
        </div>
        <hr class="w-100">
        <div class="row">
            <div class="colonne col-md-6 col-sm-6 col-xs-12 col-xxs-12 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[2])) ;?></div></div>
            <div class="colonne col-md-6 col-sm-6 col-xs-12 col-xxs-12 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[3])) ;?></div></div>
        </div>
        <hr class="w-100">
        <div class="row">
            <div class="colonne col-md-12 col-sm-12 col-xs-12 col-xxs-12 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[4])) ;?></div></div>
        </div>
        </div>
  <?php } else if(count($vers) == 4){
        $vers_count+=1;
        // Vérifie si le premier vers est en langue arabe
        $isArabic = isArabic($vers[0]); ?>
            <div class="ligne slide row " <?php if ($isArabic){ echo 'dir="rtl"'; } else { echo 'dir="ltr"'; }?> <?php if ($highlightAction){ echo ' style="font-style: italic; color: gray;" '; } ?>>
            <?php //echo '<em class="numLigne">'.($numline+=1).'</em>';?>
            <div class="row">
                <div class="colonne col-6 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[0])) ;?></div></div>
                <div class="colonne col-6 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[1])) ;?></div></div>                        
            </div>
            <hr class="w-100">
            <div class="row">
                <div class="colonne col-6 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[2])) ;?></div></div>
                <div class="colonne col-6 text-center circled"><div class="circled"><?= htmlspecialchars(trim($vers[3])) ;?></div></div>
            </div>
            </div>
    <?php } else if (count($vers) == 2) { 
        $vers_count+=1;
        // Vérifie si le premier vers est en langue arabe
        $isArabic = isArabic($vers[0]);  ?>
            <div class="ligne slide  row "  <?php if ($isArabic){ echo 'dir="rtl"'; } else { echo 'dir="ltr"'; }?> <?php if ($highlightAction){ echo ' style="font-style: italic; color: gray;" '; } ?>>
            <?php //echo '<em class="numLigne">'.($numline+=1).'</em>'; ?>
            <div class="colonne col-md-6 col-sm-6 col-xs-12 col-xxs-12 text-center circled"><div class="circled couplets"><?= htmlspecialchars(trim($vers[0])) ?></div></div>
            <div class="colonne col-md-6 col-sm-6 col-xs-12 col-xxs-12 text-center circled"><div class="circled refrains"><?= htmlspecialchars(trim($vers[1])) ?></div></div>
            </div>
    <?php } else { // Si c'est un Titre de chapitre (Sous titre) ?> 
          <?php if(strpos($ligne, '+++') !== false || (strpos($ligne, '{{{') === 0 && strpos($ligne, '}}}') === strlen($ligne) - 3)) {            
                  if(strpos($ligne, '{{{') !== false && strpos($ligne, '}}}') !== false) {
                    $chapitre = str_replace(['{{{', '}}}'], '', $ligne); // Remplace {{{ et }}} par une chaîne vide
                  } else {
                    // Titre de chapitre (Sous titre)
                    $chapitre = str_replace('+++', '', $ligne); // Enlève +++
                  }
              ?>
              <div class="ligne slide row chapitre " <?php if ($highlightAction){ echo ' style="font-style: italic; color: gray;" '; } ?> >
              <hr w-100>
                      <h3 class="colonne col-md-12 col-sm-12 col-xs-12 col-xxs-12 text-center circled chapitre-titre"><?= ucfirst(htmlspecialchars(trim($chapitre))) ;?></h3>
              <hr w-100>
              </div>
          <?php } else {  ?>
              <?php $isArabic = isArabic($ligne); // Est-ce en arabe ? ?>
                    <div class="ligne slide  row "  <?php if ($isArabic){ echo 'dir="rtl"'; } else { echo 'dir="ltr"'; }?> <?php if ($highlightAction){ echo ' style="font-style: italic; color: gray;" '; } ?> >
                    <?php //echo '<em class="numLigne">'.($numline+=1).'</em>'; ?>
                    <div class="colonne col-md-12 col-sm-12 col-xs-12 col-xxs-12 text-center circled"><div class="circled"><?= htmlspecialchars($ligne) ?></div></div>
                    </div>
            <?php } ?>
              <?php } ?>
    <?php } else { // Si c'est un Titre de chapitre (Sous titre)
              if(strpos($ligne, '+++') !== false || (strpos($ligne, '{{{') === 0 && strpos($ligne, '}}}') === strlen($ligne) - 3)) {            
                if(strpos($ligne, '{{{') !== false && strpos($ligne, '}}}') !== false) {
                  $chapitre = str_replace(['{{{', '}}}'], '', $ligne); // Remplace {{{ et }}} par une chaîne vide
                } else {
                  // Titre de chapitre (Sous titre)
                  $chapitre = str_replace('+++', '', $ligne); // Enlève +++
                }                
                ?>
                <div class="ligne slide row chapitre " <?php if ($highlightAction){ echo ' style="font-style: italic; color: gray;" '; } ?> >
                <hr class="w-100">
                        <h3 class="colonne col-md-12 col-sm-12 col-xs-12 col-xxs-12 text-center circled chapitre-titre"><?= ucfirst(htmlspecialchars(trim($chapitre))) ;?></h3>
                    <hr w-100>
                </div>
                
            <?php } else {  
                $isArabic = isArabic($ligne);
                ?>
                <div class="ligne slide row "  <?php if ($isArabic){ echo 'dir="rtl"'; } else { echo 'dir="ltr"'; }?> <?php if ($highlightAction){ echo ' style="font-style: italic; color: gray;" '; } ?> >
                <?php //echo '<em class="numLigne">'.($numline+=1).'</em>'; ?>
                <div class="colonne col-md-12 col-sm-12 col-xs-12 col-xxs-12 text-center circled"><div class="circled"><?= htmlspecialchars(trim($ligne)) ?></div></div>
                </div>
            <?php } ?>
            
        <?php } ?>
  
  <?php // Fin Mise en italic et en gris de l'avant propos 
    if (strpos($ligne, '>>>') !== false || $highlightActionfinised) {
      $highlightAction = false;
    }
  ?>

  <?php endforeach ?>

<?php endif; // FIN S'il y'a du contenu, l'afficher ?>
</div>
<br>

<?php 
/* ----------------------------------------------------------------------
  AFFICHAGE DES DONNÉES DE L'OEUVRE
-------------------------------------------------------------------------*/  
?>
<div class="container chanson-box chanson-info">
  <div class="chanson-info-box">
    <span class="chanson-info-label">Nombre de lettres :</span> <?php echo $letters; ?>
  </div>
  <div class="chanson-info-box">
    <span class="chanson-info-label">Nombre de mots :</span> <?php echo $words; ?>
  </div>
  <?php if ($vers_count >= 1):?>
  <div class="chanson-info-box">
    <span class="chanson-info-label">Nombre de vers :</span> <?php echo $vers_count; ?>
  </div>
  <?php endif;?>
  <div class="container chanson-categorie">
<p><u><b>Catégorie</b></u> : <?php echo $categorie_dic[$chanson['category']];?></p>
</div>
</div>

<?php 
/* ----------------------------------------------------------------------
  INCLUSION DES SCRIPTS JAVASCRIPTS ET DU PIEDS DE PAGE
-------------------------------------------------------------------------*/  
?>
<!-- GESTIONNAIRE DES BOUTONS D'ACTION -->
<script src="assets/js/song-font-toogler.js"></script>
<script src="assets/js/print-song.js"></script>
<script src="assets/js/song-options-toogle-closer.js"></script>

<script src="assets/js/search-on-view.js"></script>
<script src="assets/js/search-on-view-line-filter.js"></script>
<script src="assets/js/view-song-coloring.js"></script>

<!-- SCRIPT POUR FIXER LA BARE DE RECHERCHE LORS DU DÉFILMENT DE PAGE POUR song-view.php -->
<script src="assets/js/search-on-view-fixe-on-scroll.js"></script>

<!-- AFFICHAGE DE L'OEUVRE EN SLIDE AU CLIQUE DU BOUTON .btn-slide -->
<script src="assets/js/slide-display.js"></script>

<?php
// Inclusion du pied de page
include __DIR__.'/footer.php';
?>

<?php

/**--- ENTËTE DU SITE --------------------- */
require_once('header.php'); 


/***--- ENTËTE DE PAGE */
$page_title = $app['name'];
?>

<main class="container mt-8">
  <div class="row justify-content-center">
    <!-- Zone d'alerte -->
    <div class="container text-center">
        <!-- MESSAGE D'ALERT --> 
            <div class="" id="songActionsInfo">
                <div class="alert alert-primary alert-dismissible fade show d-none" role="alert"> <!-- Ajoutez d-none pour cacher par défaut -->
                    <i></i> <!-- Le message sera inséré ici -->
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
        <div class="card"> 
            <div class="card-header">
                <?php
                    echo '<h5 class="text-center">Les œuvres que vous éditez</h5>';
                ?>
                </p>  
            </div>
            <div class="search-container">
                <!-- AFFICHAGE DE LA BARE DE RECHERCHE (AVANT LE CARD-BODY)  -->
                <input type="text" id="search-input" placeholder="Saisir pour rechercher et filtrer la liste" class=" form-control w-100 bg-transparent h-25">

            </div>
            <div class="card-body">
                <?php
                $user_id = 1;
                $mes_chansons = afficher_mes_oeuvres($pdo, $user_id);
                ?>
                <ul class="song-list">
                    <?php foreach ($mes_chansons as $chanson): ?>
                        <li class="song-item">
                            <div class="song-info">
                                <h3 class="circled"><?= $chanson['title'] ?></h3>
                                <?php // Vérifier c'est en arabe pour la direction l'affichage
                                    $isArabic = isArabic($chanson['content']); 
                                    // Tronquage du contenu pour afficher la première ligne seulement
                                    $lines = explode("\n", $chanson['content']);
                                    $firstLine = array_shift($lines); // Récupère la première ligne
                                    $chanson['content'] = preg_replace('/<<<|>>>|\+++|---/s', '', $firstLine); 
                                ?>
                                <p class="circled" <?php if($isArabic){echo 'dir="rtl"';  } // Paramètre direction d'affichage ?> >
                                    <?= htmlspecialchars($chanson['content']); // Affichage texte tronqué?>
                                </p>
                                <span><u>Catégorie:</u> <b><?= $categorie_dic[$chanson['category']]; ?></b></span>
                            </div>
                            <div class="song-actions d-block m-2">
                                <a href="song-view.php?id=<?php echo $chanson['id']; ?>" class="btn btn-primary"><?php echo $read_song_svg; ?></a>
                                <a href="song-edit.php?id=<?php echo $chanson['id']; ?>" class="btn btn-secondary"><?php echo $edit_song_svg; ?></a>
                                <a href="#" class="btn btn-danger" data-song-id="<?php echo $chanson['id']; ?>" onclick="supprimerChansonDefinitivement(<?php echo $chanson['id']; ?>); return false;">
                                    <?php echo $delete_definitively_svg; ?>
                                </a>                                
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if (empty($mes_chansons)): ?>
                    <p class="text-center">Vous n'avez encore ajouté aucune œuvres à éditer.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
  </div>
</main>

<script src="assets/js/profile-actions.js"></script>
<script src="assets/js/search-in-a-category.js"></script>
<!-- SCRIPT POUR FIXER LA BARE DE RECHERCHE LORS DU DÉFILMENT DE PAGE POR categies.php et profile.php -->
<script src="assets/js/search-filter-fixe-on-scroll.js"></script>
<!-- COLORISATION DES NOMS SACRÉS D'ALLAH ﷻ ET DU PHROPHÈTE ﷺ ET DE CHEIKHOUL KHADIM -->
<script src="assets/js/view-song-coloring.js"></script>

<?php require_once(__DIR__.'/footer.php'); ?>

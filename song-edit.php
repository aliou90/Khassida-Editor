<?php
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

/**--- ENTËTE DU SITE --------------------- */
require_once('header.php'); 

// Récupération de la chanson correspondante à l'ID
$chanson = rechercher_chansons_par_id($pdo, $_GET['id']);

// Redirection vers la page d'accueil si la chanson n'existe pas
if (!$chanson) {
    header('Location: index.php');
    exit();
}

// Titre de la page
$page_title = 'Modifier ' . $chanson['title'];

// Inclusion de l'en-tête
include_once __DIR__.'/header.php';  // Contient aussi les variables de SESSION

?>

<div class="container">
  <div class="border border-2 rounded">
      <!-- Référencement de l'éditeur -->
      <div class="ks-editor"></div>    

  </div>
</div>

<script>
    // Initialisation de l'éditeur
    $(document).ready(function() {
        const mode = 'update'; // Les `mode` valides: `create`, `update` & `review` 
        const requiredID = <?php echo intval($_GET['id']); ?>; // Assurez-vous que c'est un entier

        $('.ks-editor').ksEditor(mode, requiredID);
    });
</script>

<?php
// Inclusion du pied de page
include __DIR__.'/footer.php';
?>
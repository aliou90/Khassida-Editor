<?php
/**--- ENTËTE DU SITE --------------------- */
require_once('header.php'); 
?>
<div class="container">
    <div class="border border-2 rounded">
        <!-- Passage de fonction pour l'application-->
        <div class="ks-editor"></div>
        
    </div>
</div>


<script>
    // Initialisation de l'éditeur
    $(document).ready(function() {
        const mode = 'create'; // Les `mode` valides: `create`, `update` & `review` 
        const requiredID = <?php echo 1; /*intval($_SESSION['user_id']); */ ?>; // Assurez-vous que c'est un entier

        $('.ks-editor').ksEditor(mode, requiredID);
    });
</script>



<!--POSITIONNEMENT AUTOMATIQUE DU TEXTE SAISIE -->
<script src="assets/js/input-text-positionning.js"></script>

<?php require_once('footer.php'); ?>
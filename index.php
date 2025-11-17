<?php
/**--- ENTËTE DU SITE --------------------- */
require_once('header.php'); 
?>

<div class="cover-container m-2">
    <div class="cover-content" style="position: relative;">
        <img src="assets/images/covers/cover1.png" alt="Khassida Éditor Cover Image" class="w-100 h-100">
        <a href="song-add.php" class="btn btn-primary left-btn"><?php echo $nav_addbook_svg; ?></a>
        <a href="profile.php" class="btn btn-secondary right-btn"><?php echo $nav_myeditions_svg; ?></a>
    </div>
</div>

<script>
    $('header').remove();
</script>

<?php require_once('footer.php'); ?>
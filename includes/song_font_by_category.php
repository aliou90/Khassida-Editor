<?php // Style par défaut selon la catégorie
if ($chanson['category'] === $categorie_clefs[0]) {
    echo '<style>
    .chanson-titre, 
    .chanson-content {
        font-family: "macca";
        font-size: 1.2rem;
    }
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
        // Réinitialiser la bordure pour tous les boutons
        $(".btn-font").removeClass("selected-font-button");

        // Appliquer la bordure au bouton sélectionné
        $("#macca-btn").addClass("selected-font-button");
    });
    </script>';
}

if ($chanson['category'] === $categorie_clefs[2]) {
    echo '<style>
    .chanson-titre, 
    .chanson-content {
        font-family: "madina";
        font-size: 1.4rem;
    }
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
        // Réinitialiser la bordure pour tous les boutons
        $(".btn-font").removeClass("selected-font-button");

        // Appliquer la bordure au bouton sélectionné
        $("#madina-btn").addClass("selected-font-button");
    });
    </script>';
}

if ($chanson['category'] === $categorie_clefs[1] || $chanson['category'] === $categorie_clefs[4] || $chanson['category'] === $categorie_clefs[3]) {
    echo '<style>
    .chanson-titre, 
    .chanson-content {
        font-family: "touba";
        font-size: 1.1rem;
    }
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
        // Réinitialiser la bordure pour tous les boutons
        $(".btn-font").removeClass("selected-font-button");

        // Appliquer la bordure au bouton sélectionné
        $("#touba-btn").addClass("selected-font-button");
    });
    </script>';
}

if ($chanson['category'] === $categorie_clefs[5]) {
    echo '<style>
    .chanson-titre, 
    .chanson-content {
        font-family: "ndiarem";
        font-size: 1.1rem;
    }
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
        // Réinitialiser la bordure pour tous les boutons
        $(".btn-font").removeClass("selected-font-button");

        // Appliquer la bordure au bouton sélectionné
        $("#ndiarem-btn").addClass("selected-font-button");
    });
    </script>';
}
?>

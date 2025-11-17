(function($) { /*-- DEBUT APPLICATION --*/
    $.fn.ksEditor = function(mode, requiredID) { /*-- DEBUT FONCTION PRINCIPALES --*/
        // Vérifier si mode est valide et si requiredID est un entier
        const acceptedModes = ['create', 'update', 'review'];
        if (!acceptedModes.includes(mode) || !Number.isInteger(requiredID)) {
            // Rediriger vers la page 403 si les conditions ne sont pas remplies
            window.location.href = '/403.html'; // Remplacez par le chemin de votre page 403
            return; // Sortir de la fonction
        }
        
        // Recupérer l'ID selon le mode de l'application
        if (mode === 'create') {
            userID = requiredID; 
        } else if (mode === 'update') {
            songID = requiredID;
        }

        // Création du conteneur principal pour l'éditeur
        const editor = $('<div id="ks-editor" class="border border-2 p-3 mt-5 pt-5 rounded m-3"></div>'); // Conteneur principal avec bordure de 2

        const editorArea = $('<div id="editor-area" class="bg-secondary border p-2 rounded mb-3"></div>'); // Zone d'édition sans conteneur Bootstrap

        const toolbar = $('<div id="toolbar" class="mb-3 d-flex flex-wrap justify-content-between border border-primary bg-light p-2"></div>'); // Barre d'outils sans conteneur Bootstrap

        const contentArea = $('<div id="content-area" class="flex-column border p-2 rounded"></div>'); // Zone de contenu sans conteneur Bootstrap

        // Configuration de la toolbar
        // Créer un conteneur pour le label et le bouton de langue
        const languageContainer = $('<div id="languageContainer" class="d-flex align-items-center" style="margin-right: 0.5em;"></div>');

        // Ajouter le label pour la langue
        const languageLabel = $('<p id="selectLangue" class="bg-light text-black" style="font-size: 0.7em; padding: 0.2em 0.4em; margin: 0; line-height: 1.2;">Langue</p>'); // Suppression de la marge

        // Ajouter le label au conteneur
        languageContainer.append(languageLabel);

        // Créer le bouton de langue avec les mêmes styles que précédemment
        const languages = $('<button id="selectLangueBtn" class="btn btn-secondary" style="font-size: 0.7em; padding: 0.2em 0.4em; height: 24px; width: 24px; border-radius: 4px;" value="ar">AR</button>');

        // Ajouter l'événement de clic au bouton
        languages.on('click', function(e) {
            e.preventDefault();
            var lang = $(this).text(); // Récupère le texte du bouton
            
            if (lang === 'AR') {
                $(this).text('FR'); // Change le texte du bouton
                $(this).val('fr'); // Change la valeur du bouton                    
                $('html').attr('lang', 'fr'); // Change l'attribut lang
                $('p.ks-editable').attr('dir', 'ltr'); // Applique la direction gauche à droite pour le texte français
                $('p.ks-editable').css('text-align', 'left');
            } else if (lang === 'FR') {
                $(this).text('EN'); // Change le texte du bouton
                $(this).val('en'); // Change la valeur du bouton                    
                $('html').attr('lang', 'en'); // Change l'attribut lang
                $('p.ks-editable').attr('dir', 'ltr'); // Applique la direction droite à gauche pour le texte arabe
                $('p.ks-editable').css('text-align', 'left');
            } else {
                $(this).text('AR'); // Change le texte du bouton
                $(this).val('ar'); // Change la valeur du bouton                    
                $('html').attr('lang', 'ar'); // Change l'attribut lang
                $('p.ks-editable').attr('dir', 'rtl'); // Applique la direction droite à gauche pour le texte arabe
                $('p.ks-editable').css('text-align', 'right');
            }
        });

        // Ajouter le bouton au conteneur
        languageContainer.append(languages);

        // Ajouter le conteneur à la toolbar
        toolbar.append(languageContainer);

        // Créer un conteneur pour le label et le sélecteur de style
        const docStyleContainer = $('<div class="d-flex align-items-center" style="margin-right: 0.5em;"></div>');

        // Ajouter le label pour le style
        const docStyleLabel = $('<p class="bg-light text-black" style="font-size: 0.7em; padding: 0.2em 0.4em; margin: 0; line-height: 1.2;">Ligne</p>'); // Suppression de la marge

        // Ajouter le label au conteneur
        docStyleContainer.append(docStyleLabel);

        // Créer le sélecteur de styles de document avec les mêmes styles que les boutons
        const docStyles = $('<select id="docStyle" class="form-control d-inline-block" style="font-size: 0.7em; padding: 0.2em; height: 24px; width: auto; border-radius: 4px; margin-right: 0.5em;">' +
            '<option value="texte">Texte</option>' +
            '<option value="2_vers">2 Vers</option>' +
            '<option value="4_vers">4 Vers</option>' +
            '<option value="5_vers">5 Vers</option>' +
            '</select>');

        // Écouteur d'événements pour le changement de style de document
        docStyles.change(function(e) {
            e.preventDefault();
            const selectedStyle = $(this).val();
            generateInputs(selectedStyle);

            if (selectedStyle === 'texte') {
                $('.conditional').show();
            } else {
                $('.conditional').hide();
            }
        });

        // Ajouter le sélecteur au conteneur
        docStyleContainer.append(docStyles);

        // Ajouter le conteneur à la toolbar
        toolbar.append(docStyleContainer);
                
        // Conteneur pour les formats et taille de police
        const formattingContainer = $('<div class="d-flex align-items-end" style="margin-right: 0.5em;"></div>');

        // Ajouter le label pour les formats
        const formatLabel = $('<p class="bg-light text-black conditional" style="font-size: 0.7em; padding: 0.2em 0.4em; margin: 0.5em; line-height: 1.2;">Formats</p>'); // Suppression de la marge

        // Ajouter le label au conteneur
        formattingContainer.append(formatLabel);

        // Créer le sélecteur pour les formats
        const formats = $('<select id="format" class="form-control w-auto conditional" style="font-size: 0.7em; padding: 0.2em 0.4em; margin-right: 0.5em;"></select>');

        // Options du sélecteur
        const formatOptions = [
            { value: 'p', name: 'Paragraph' },
            { value: 'h3', name: 'Sous Titre' },
            { value: 'cite', name: 'Préface' }
        ];

        // Ajout des options au sélecteur
        formatOptions.forEach(option => {
            const formatOption = $(`<option value="${option.value}">${option.name}</option>`);
            formats.append(formatOption);
        });

        // Ajout des éléments à la toolbar
        formattingContainer.append(formats);
        toolbar.append(formattingContainer);

        editorArea.append(toolbar, contentArea);

        // Récupération zone de titre et d'affichage depuis la pages'il existent
        let titleArea = $('<div id="ks-title" class="ks-title"></div>');
        // Ajout titre d'entête
        let titleHead = $(`<h1 id="titleHead" class="">Ajouter une nouvelle œuvre</h1>`);
        // Ajout Lien d'affichage de l'oeuvre
        let showLink = $(`<p id="showLink"></p>`);
        // Ajout Barre d'alerte
        let messageBox = $(`<div class="container text-center" id="messageBox"></div>`);
        // Ajouter le formulaire à titleArea
        let titleFormArea = $(`
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="song_title">Titre :</label>
                            <input type="text" class="form-control p-2 h-50" id="song_title" name="song_title" style="min-height: 55px;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6 m-0 p-0">
                                <div class="form-group">
                                    <label for="song_category">Catégorie :</label>
                                    <select class="form-control mt-1 p-2 h-50" id="song_category" name="song_category" style="min-height: 55px;" required>
                                        <option value="">-- Sélectionner une catégorie --</option>
                                        <option value="quran">Quran</option>
                                        <option value="zikr">Zikr - Duha - Shukr</option>
                                        <option value="salat">Salatu Halan Nabiy</option>
                                        <option value="madh">Madhun Nabiy</option>
                                        <option value="hilm">Hilmul Islamiya</option>
                                        <option value="wasiya">Wasiya</option>
                                    </select>
                                </div>
                            </div>
                            <div id="songButtonContainer" class="col-6 m-0 mt-4">
                                <!-- Emplacement des boutons de soumission implémentés plus tard -->
                            </div>
                        </div>                        
                    </div>
                </div>
        `);
        
        titleFormArea.on('input', '#song_title', function() {
            var text = $(this).val(); // Récupérer le texte saisi
            if (isArabic(text)) {
                $(this).addClass('ks-text-right'); // Ajouter une classe pour aligner à droite
                $(this).removeClass('ks-text-left'); // Supprimer la classe d'alignement à gauche
                $(this).attr('dir', 'rtl'); // Définir la direction du texte à droite à gauche
                $('option[value="quran"]').text('القرآن');
                $('option[value="zikr"]').text('دعآء - شكر - ذكر اللّه ');
                $('option[value="salat"]').text('صلاة على نبي ﷺ');
                $('option[value="madh"]').text('مدح النبي ﷺ');
                $('option[value="hilm"]').text('علم الإسلاميه');
                $('option[value="wasiya"]').text('وصيّة');
            } else {
                $(this).addClass('ks-text-left'); // Ajouter une classe pour aligner à gauche
                $(this).removeClass('ks-text-right'); // Supprimer la classe d'alignement à droite
                $(this).attr('dir', 'ltr'); // Définir la direction du texte à gauche à droite
                $('option[value="quran"]').text('Quran');
                $('option[value="zikr"]').text('Zikr - Duha - Shukr');
                $('option[value="salat"]').text('Salatu Halan Nabiy');
                $('option[value="madh"]').text('Madhun Nabiy');
                $('option[value="hilm"]').text('Hilmul Islamiya');
                $('option[value="wasiya"]').text('Wasiya');
            }
        });
        
        const addSongButton = $(`<button id="add-song-btn" class="btn btn-outline-primary w-100 mt-1" style="min-height: 55px;">Commencer</button>`);
        const modifySongButton = $(`<button id="modify-song-btn" class="btn btn-outline-warning w-100 mt-1" style="min-height: 55px;">Modifier</button>`);

        // Initialisation zone d'affichage
        let displayArea = $(`<div></div>`);
        
        // Vérifier le mode de l'éditeur
        if (mode === 'create') {
            titleArea = $('<div id="ks-title" class="ks-title">');
            
            // Ajout de la zone de message et du formulaire
            titleArea.append(titleHead);
            titleArea.append(messageBox);
            userIdInput = $(`<input type="hidden" name="user_id" id="user_id" value="${userID}">`);
            titleFormArea.append(userIdInput);
            titleArea.append(titleFormArea);

            displayArea = $('<div id="ks-screen" style="min-height: 250px;" class="container chanson-box chanson-content chanson-slide flex-column sortable-container border p-2 rounded mb-2"></div>');

            // Ajouter le #ks-title au conteneur principal
            editor.append(titleArea);
            editor.append(displayArea); // Ajout de la zone d'affichage
            editor.append(editorArea);
            this.append(editor);
            $('#songButtonContainer').append(addSongButton); // Ajout bouton soumission correspondant

            titleArea.show();
            editorArea.hide();

            // Gérer la soumission du formulaire
            $('#add-song-btn').on('click', function(e) {
                e.preventDefault(); // Empêcher la soumission par défaut

                // Logic pour enregistrer dans la base de données
                const title = $('#song_title').val().trim(); // Trim pour enlever les espaces superflus
                const category = $('#song_category').val().trim();
                const userId = $('#user_id').val().trim();

                // Vérification basique pour s'assurer que les champs ne sont pas vides
                if (!title || !category || !userId) {
                    alert("Veuillez remplir tous les champs.");
                    return;
                }

                // Effectuer une requête AJAX pour ajouter la chanson
                $.ajax({
                    url: 'rqt-song-add-title-new.php',
                    method: 'POST',
                    data: { title: title, category: category, user_id: userId },
                    success: function(response) {
                        const jsonResponse = JSON.parse(response);

                        if (jsonResponse.success) {
                            // Ajout de l'ID de l'oeuvre insérée et en cours d'édition
                            const song_id = jsonResponse.id;
                            editor.append(`<input type="hidden" name="song_id" id="song_id" value="${song_id}">`);

                            // Ajouter le titre & catégorie en haut de editor
                            editor.prepend(titleArea).hide().fadeIn(700);
                            addSongButton.remove(); // Supprimer le bouton Commencer du formulaire de titre
                            $('#songButtonContainer').append(modifySongButton).fadeIn(); // Ajout bouton soumission correspondant

                            showLink.append(`<em>Les modifications seront sauvegardées automatiquement.</em> <a href="song-view.php?id=${song_id}" class=" link-success m-0 p-0 border-2 rounded">Visualiser</a> -- <a href="profile.php?mes_oeuvres=mes_oeuvres" class=" link-primary border-2 rounded align-content-center"> Voir dans vos éditions </a>`)
                            titleArea.prepend(showLink);
                            // Mise à jour du Titre Affiché
                            titleHead.remove();
                            // Utiliser .text() pour éviter l'interprétation de HTML
                            titleHead = $(`<h1 id="titleHead" class="">Édition de ${title} <em>en cours ....</em></h1>`);
                            titleArea.prepend(titleHead);

                            // Gérer le succès (afficher un message de confirmation)
                            $('#messageBox').empty()
                                .append(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i id="messageBoxInfo">${jsonResponse.message}</i>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                `)
                                .show();

                            // Afficher l'éditeur et le screen
                            editor.fadeIn(700);
                            editorArea.show();

                        } else {
                            // Gérer l'erreur (afficher un message)
                            $('#messageBox').empty()
                            .append(`
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i id="messageBoxInfo">${jsonResponse.message}</i>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            `)
                            .show();
                        }
                    },
                    error: function() {
                        alert('Une erreur est survenue lors de l\'ajout de la chanson.');
                    }
                });

            });

        } else if (mode === 'update') {
            let songTitle;
            let songContent;
            let songCategory;
            // Requête AJAX pour récupérer les données de la chanson
            $.ajax({
                url: 'rqt-song-edit-select.php', // URL du fichier de traitement
                method: 'POST', // Méthode de la requête
                data: { 
                    song_id: songID // Envoyer le songID
                },
                success: function(response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        // Récupérer les données dans des variables
                        songTitle = jsonResponse.data.title;
                        songContent = jsonResponse.data.content;
                        songCategory = jsonResponse.data.category;

                        // Remplir les champs du formulaire avec les données récupérées
                        titleHead = $(`<h1 id="titleHead" class="">Édition de ${songTitle} <em>en cours ....</em></h1>`);
                        
                        // Ajout les éléments de l'entête et du formulaire
                        titleArea.append(titleHead);
                        showLink.append(`<em>Les modifications seront sauvegardées automatiquement.</em> <a href="song-view.php?id=${songID}" class=" link-success m-0 p-0 border-2 rounded">Visualiser</a> -- <a href="profile.php?mes_oeuvres=mes_oeuvres" class=" link-primary border-2 rounded align-content-center"> Voir dans vos éditions </a>`)
                        titleArea.append(showLink);
                        titleArea.append(messageBox);
                        titleArea.append(titleFormArea);
                        $('#songButtonContainer').append(modifySongButton); // Ajout bouton soumission correspondant
                        
                        // Restaurer les données du formulaire
                        $('#song_title').val(songTitle); // Mettre à jour le titre
                        if (isArabic(songTitle)) {
                            $('#song_title').addClass('ks-text-right'); // Ajouter une classe pour aligner à droite
                            $('#song_title').removeClass('ks-text-left'); // Supprimer la classe d'alignement à gauche
                            $('#song_title').attr('dir', 'rtl'); // Définir la direction du texte à droite à gauche
                            $('option[value="quran"]').text('القرآن');
                            $('option[value="zikr"]').text('دعآء - شكر - ذكر اللّه ');
                            $('option[value="salat"]').text('صلاة على نبي ﷺ');
                            $('option[value="madh"]').text('مدح النبي ﷺ');
                            $('option[value="hilm"]').text('علم الإسلاميه');
                            $('option[value="wasiya"]').text('وصيّة');
                        }
                        $('#song_category').val(songCategory); // Mettre à jour la catégorie
                        // Ajouter le contenu sur displayArea
                        // Vérifier si le contenu est du html et insérer directement ou sur un conteneur html
                        if (!ishtml(songContent)) {
                            displayArea.append(`<div id="ks-screen" style="min-height: 250px;" class="container chanson-box chanson-content chanson-slide flex-column sortable-container border p-2 rounded mb-2"> </div>`);
                            setFormattedContent(songContent);
                        } else {
                            displayArea.append(songContent); // Insérer le contenu HTML
                        }


                        // Restaurer les bouton de tri sur les lignes
                        $('#ks-screen .ks-line').each(function() {
                            // Créer une nouvelle instance de sortButton pour chaque nouveau contenu
                            const sortButton = $(`<span dir="ltr" class="sort-button hide_on_save" style="position: absolute; z-index: 9999; top: 0; left: 0; cursor: pointer; background-color: transparent; padding: 8px; border-radius: 5px;"></span>`);
                            const sortButton_svg = $(`<span title="Réorganiser" class="d-flex align-items-lg-center align-content-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter-left" viewBox="0 0 16 16">
                                    <path d="M2 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                </span>`);
                            sortButton.append(sortButton_svg);
                            $(this).append(sortButton); // Ajouter le bouton de tri
                        });

                        // Appeler la fonction pour initialiser les tris après l'ajout d'éléments
                        initializeSortable();
                        initializeSwipeToDelete();
            
                    } else {
                        // Rediriger vers 403.html en cas d'échec
                        window.location.href = '/403.html'; // Remplacez par le chemin de votre page 403
                        return; // Sortir de la fonction
                    }
                },
                error: function() {
                    console.error('Erreur de communication avec le serveur.');
                    // Vous pouvez également rediriger ici si nécessaire
                }
            });

            // Ajouter le #ks-title au conteneur principal
            editor.append(titleArea);
            editor.append(displayArea); // Ajout de la zone d'affichage
            editor.append(editorArea);
            editor.append(`<input type="hidden" name="song_id" id="song_id" value="${songID}">`);
            this.append(editor);
            
        }

        // Gérer la soumission du formulaire de modification titre & Catégorie
        titleFormArea.on('click', '#modify-song-btn', function(e) {
            e.preventDefault(); // Empêcher la soumission par défaut

            // Récupérer les données du formulaire
            const title = $('#song_title').val();
            const category = $('#song_category').val();
            const songId = $('#song_id').val();

            // Effectuer une requête AJAX pour modifier la chanson
            $.ajax({
                url: 'rqt-song-title-modify.php',
                method: 'POST',
                data: {
                    title: title,
                    category: category,
                    song_id: songId
                },
                success: function(response) {
                    const jsonResponse = JSON.parse(response);
                
                    if (jsonResponse.success) {
                        // Gérer le succès (ex: afficher un message de confirmation)
                        $('#messageBox').empty()
                            .append(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i id="messageBoxInfo">${jsonResponse.message}</i>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            `)
                            .show();

                        // Mise à jour du Titre Affiché
                        titleHead.remove();
                        // Utiliser .text() pour éviter l'interprétation de HTML
                        titleHead = $(`<h1 id="titleHead" class="">Édition de ${title} <em>en cours ....</em></h1>`);
                        titleArea.prepend(titleHead);

                    } else {
                        // Gérer l'échec (afficher un message d'erreur)
                        $('#messageBox').empty()
                        .append(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i id="messageBoxInfo">${jsonResponse.message}</i>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        `)
                        .show();

                        if (jsonResponse.title && jsonResponse.category) {
                            // Restaurer les données du formulaire
                            $('#song_title').val(jsonResponse.title); // Mettre à jour le titre
                            $('#song_category').val(jsonResponse.category); // Mettre à jour la catégorie
                        }
                    }
                },
                error: function() {
                    $('#messageBox').empty()
                        .append(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i id="messageBoxInfo">Erreur de communication avec le serveur.</i>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        `)
                        .show();
                }                                    
            });
        });
        
        function ishtml(string) {
            // Vérifie si la chaîne contient des balises HTML
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = string;
            return Array.from(tempDiv.childNodes).some(node => node.nodeType === 1); // Vérifie si au moins un noeud est un élément
        }        

        /* FONCTION DE TRI DES LIGNES INSÉRÉES */
        function initializeSortable() {
            // Vérifier si des éléments avec la classe .sort-button existent
            const hasHandle = $('.sort-button').length > 0;

            if (hasHandle) {
                // Initialiser le tri sur la zone d'affichage
                $('#ks-screen').sortable({
                    handle: hasHandle ? ".sort-button" : undefined, // Utilise la poignée si elle existe
                    placeholder: 'ks-ui-state-highlight', // Classe pour le placeholder lors du tri
                    start: function(event, ui) {
                        // Lorsque le tri commence
                        console.log('Tri commencé');
                        disableSwipeToDelete();
                    },
                    stop: function(event, ui) {
                        // Cette fonction est appelée lorsque le tri est arrêté
                        console.log('Tri arrêté');
                        enableSwipeToDelete(); // Réactivez le glissement pour supprimer même si le tri n'a pas abouti
                    },
                    update: function(event, ui) {
                        // Action à effectuer après le tri
                        console.log('Tri terminé');
                        // Obtenir le contenu à mettre à jour sans les éléments .hide_on_save
                        const contentToUpdate = $('#ks-screen').clone(); // Clone l'élément #ks-screen lui-même
                        // Supprimer les enfants .hide_on_save
                        contentToUpdate.find('.hide_on_save').remove(); 
                        // Convertir le contenu mis à jour en HTML
                        const htmlContentToUpdate = contentToUpdate.prop('outerHTML'); // Utiliser outerHTML pour inclure l'élément racine
                        const songId = $('#song_id').val();
                        updateSongContent(songId, htmlContentToUpdate);
                        enableSwipeToDelete();
                    },                
                });   
            }
        }
        
        let hammer; // Déclaration de la variable Hammer.js à l'extérieur
        let currentLine; // Variable pour garder la trace de la ligne actuellement glissée
        
        function initializeSwipeToDelete() {
            // Initialiser Hammer.js pour détecter les gestes si ce n'est pas déjà fait
            if (!hammer) {
                hammer = new Hammer(document.getElementById('ks-screen'));
        
                // Configuration pour le seuil de glissement
                const swipeThreshold = 0.20; // 1/10 de la largeur de l'élément
        
                // Détection du glissement horizontal
                hammer.on('panstart', function(event) {
                    disableSortable();

                    const targetElement = $(event.target).closest('.ks-line');
        
                    if (targetElement.length && !currentLine) { // Assurez-vous qu'aucun élément n'est déjà en cours de glissement
                        currentLine = targetElement; // Définir l'élément actuellement en cours de glissement
                        //currentLine.css('position', 'relative');
        
                        // Créer le placeholder de suppression
                        const deletePlaceholder = $('<div class="delete-placeholder">Supprimer</div>');
                        deletePlaceholder.css({
                            position: 'absolute',
                            top: 0,
                            right: 0,
                            width: '100%',
                            height: '100%',
                            backgroundColor: 'red',
                            color: 'white',
                            display: 'none',
                            alignItems: 'center',
                            justifyContent: 'center',
                            textAlign: 'center',
                            zIndex: 10
                        });
                        currentLine.append(deletePlaceholder);
                    }
                });
        
                hammer.on('panmove', function(event) {
                    if (currentLine) { // Vérifiez que nous avons une ligne actuellement en cours de glissement
                        const deltaX = event.deltaX; // Déplacement horizontal
        
                        // Appliquer le glissement
                        currentLine.css('transform', `translateX(${deltaX}px)`); // Appliquer le glissement
        
                        // Afficher ou masquer le placeholder
                        if (deltaX < 0) {
                            const width = currentLine.width();
                            const threshold = width * swipeThreshold;
        
                            if (Math.abs(deltaX) > threshold) {
                                currentLine.find('.delete-placeholder').fadeIn();
                            } else {
                                currentLine.find('.delete-placeholder').fadeOut();
                            }
                        }
                    }
                });
        
                hammer.on('panend', function(event) {
                    enableSortable();
                    if (currentLine) { // Vérifiez que nous avons une ligne actuellement en cours de glissement
                        const deltaX = event.deltaX;
        
                        // Si le glissement atteint le seuil, demander confirmation
                        if (deltaX < -currentLine.width() * swipeThreshold) {
                            if (confirm("Êtes-vous sûr de vouloir supprimer cette ligne ?")) {
                                currentLine.fadeOut(300, function() {
                                    $(this).remove(); // Supprime l'élément après l'animation

                                    // Obtenir le contenu à mettre à jour sans les éléments .hide_on_save
                                    const contentToUpdate = $('#ks-screen').clone(); // Clone l'élément #ks-screen lui-même
                                    // Supprimer les enfants .hide_on_save
                                    contentToUpdate.find('.hide_on_save').remove(); 
                                    // Convertir le contenu mis à jour en HTML
                                    const htmlContentToUpdate = contentToUpdate.prop('outerHTML'); // Utiliser outerHTML pour inclure l'élément racine
                                    const songId = $('#song_id').val();
                                    updateSongContent(songId, htmlContentToUpdate);
                                });

                            } else {
                                // Ramener l'élément à sa position d'origine
                                currentLine.css('transform', 'translateX(0)');
                            }
                        } else {
                            // Ramener l'élément à sa position d'origine si le seuil n'est pas atteint
                            currentLine.css('transform', 'translateX(0)');
                        }
        
                        // Réinitialiser la classe de style et supprimer le placeholder
                        currentLine.find('.delete-placeholder').remove();
                        //currentLine.css('position', ''); // Réinitialiser la position
                        currentLine = null; // Réinitialiser currentLine pour permettre un nouveau glissement
                    }
                });
        
                // Désactiver le glissement vertical
                hammer.get('pan').set({ direction: Hammer.DIRECTION_HORIZONTAL }); // Limiter les mouvements à l'horizontale uniquement
            }
        }
        
        function enableSwipeToDelete() {
            initializeSwipeToDelete(); // Initialiser si pas encore fait
            if (hammer) {
                hammer.set({ enable: true }); // Activer les gestes
            }
        }
        
        function disableSwipeToDelete() {
            if (hammer) {
                hammer.set({ enable: false }); // Désactiver les gestes
                hammer.destroy(); // Détruire l'instance Hammer.js
                hammer = null; // Réinitialiser la variable Hammer.js
            }
        }
        
        function enableSortable() { /* FONCTION D'ACTIVATION DU TRI DES LIGNES INSÉRÉES */
            $('#ks-screen').sortable("enable"); // Activer le tri
            //enableSwipeToDelete(); // Activer le swipe lorsque le tri est activé
        }
        
        function disableSortable() { /* FONCTION DE DÉSACTIVATION DU TRI DES LIGNES INSÉRÉES */
            $('#ks-screen').sortable("disable"); // Désactiver le tri
            //disableSwipeToDelete(); // Désactiver le swipe lorsque le tri est désactivé
        }
    
        function isArabic(text) { /* FONCTION DE VÉRIFICATION DE LA LANGUE ARABE */
            // Expression régulière pour vérifier les caractères arabes
            const arabicRegex = /[\u0600-\u06FF]/;
        
            // Vérifier si le texte contient des caractères arabes
            return arabicRegex.test(text);
        }
        function isFullArabic(text) { 
            // Expression régulière pour vérifier si le texte contient uniquement des caractères arabes ou des chiffres
            const arabicRegex = /^[\u0600-\u06FF0-9\s]+$/;
        
            // Teste le texte avec l'expression régulière
            return arabicRegex.test(text);
        }

        // Fonction pour remettre le contenu formaté dans #ks-screen
        function setFormattedContent(songContent) {
            // Vider le contenu actuel de #ks-screen avant d'ajouter le nouveau
            $('#ks-screen').empty();

            // Découper le contenu en lignes
            const lines = songContent.split('\n'); // Découper par retour à la ligne

            lines.forEach(line => {
                // Vérifier si la ligne contient '---'
                line = line.trim();
                if (line.includes('---')) {
                    // Si la ligne contient plusieurs colonnes, séparer et ajouter chaque élément
                    const columns = line.split('---').map(item => item.trim()).filter(item => item); // Séparer et retirer les espaces vides
                    const rowDiv = $('<div>', {
                        class: 'row ligne ks-line',
                        style: 'position: relative;',
                        dir: isArabic(line) ? 'rtl' : 'ltr' // Déterminer la direction
                    });

                    // Vérifiez le nombre de colonnes
                    if (columns.length === 4) {
                        // Si la ligne contient exactement 4 colonnes
                        columns.forEach(column => {
                            const colDiv = $('<div>', {
                                class: 'vers colonne ks-column col-6 text-center circled', // Utiliser col-6 pour 4 colonnes
                                dir: isArabic(column) ? 'rtl' : 'ltr' // Déterminer la direction
                            });

                            colDiv.append(`<p>${column}</p>`); // Ajouter le contenu dans une balise <p>
                            rowDiv.append(colDiv); // Ajouter la colonne à la ligne
                        });
                    } else {
                        // Si le nombre de colonnes est différent de 4
                        columns.forEach(column => {
                            const colDiv = $('<div>', {
                                class: 'vers colonne ks-column col-md-6 col-sm-6 col-xs-12 col-xxs-12 text-center circled',
                                dir: isArabic(column) ? 'rtl' : 'ltr' // Déterminer la direction
                            });

                            colDiv.append(`<p>${column}</p>`); // Ajouter le contenu dans une balise <p>
                            rowDiv.append(colDiv); // Ajouter la colonne à la ligne
                        });
                    }

                    $('#ks-screen').append(rowDiv); // Ajouter la ligne à l'écran

                } else {
                    if (line !== 'Aucun contenu') {

                        // Vérifier le format de la ligne unique
                        let contentHtml = '';
                        if (line.startsWith('+++')) {
                            const text = line.substring(4).trim(); // Récupérer le texte sans le préfixe
                            contentHtml = `<h3>${text}</h3>`; // Créer un <h3>
                        } else if (line.startsWith('{{{') && line.endsWith('}}}')) {
                            const text = line.substring(4, line.length - 4).trim(); // Récupérer le texte sans les symboles
                            contentHtml = `<h3>${text}</h3>`; // Créer un <h3>
                        } else if (line.startsWith('<<<') && line.endsWith('>>>')) {
                            const text = line.substring(4, line.length - 4).trim(); // Récupérer le texte sans les symboles
                            contentHtml = `<cite>${text}</cite>`; // Créer un <cite>
                        } else {
                            contentHtml = `<p>${line}</p>`; // Créer un <p> par défaut
                        }

                        const singleRowDiv = $('<div>', {
                            class: 'row ligne ks-line',
                            style: 'position: relative;',
                            dir: isArabic(line) ? 'rtl' : 'ltr' // Déterminer la direction
                        });

                        const singleColDiv = $('<div>', {
                            class: 'txt colonne ks-column col-md-12 col-sm-12 col-xs-12 col-xxs-12 text-center circled',
                            dir: isArabic(line) ? 'rtl' : 'ltr' // Déterminer la direction
                        });

                        singleColDiv.append(contentHtml); // Ajouter le contenu formaté à la colonne
                        singleRowDiv.append(singleColDiv); // Ajouter la colonne à la ligne
                        $('#ks-screen').append(singleRowDiv); // Ajouter la ligne à l'écran

                    }
                }
            });
        }
        
        // Fonction pour récupérer et formater le contenu des vers
        function getFormattedContent() {
            const formattedLines = []; // Tableau pour stocker les lignes formatées

            // Sélectionner toutes les lignes dans #ks-screen
            $('#ks-screen .ks-line').each(function() {
                const columns = $(this).find('.ks-column'); // Récupérer toutes les colonnes de la ligne
                let lineContent = ''; // Variable pour stocker le contenu de la ligne

                // Vérifier le nombre de colonnes
                if (columns.length === 1) {
                    // Si une seule colonne, vérifier les balises enfants
                    const column = columns.first(); // Récupérer la première colonne
                    const paragraph = column.find('p').first(); // Chercher un <p>
                    const cite = column.find('cite').first(); // Chercher un <cite>
                    const heading = column.find('h3').first(); // Chercher un <h3>

                    // Vérifier si une des balises est présente et récupérer le texte
                    if (heading.length) {
                        lineContent = `+++ ${heading.text().trim()}`; // Préfixer avec +++
                    } else if (cite.length) {
                        lineContent = `<<< ${cite.text().trim()} >>>`; // Entourer de <<< >>>
                    } else if (paragraph.length) {
                        lineContent = paragraph.text().trim(); // Récupérer normalement
                    }
                } else {
                    // Si plusieurs colonnes, récupérer le texte séparé par ---
                    lineContent = columns.map(function() {
                        return $(this).text().trim(); // Récupérer le texte de chaque colonne
                    }).get().join(' --- '); // Joindre les textes avec ---
                }

                // Ajouter le contenu formaté à la liste si lineContent n'est pas vide
                if (lineContent) {
                    formattedLines.push(lineContent);
                }
            });

            // Retourner le contenu formaté
            return formattedLines.join('\n'); // Joindre toutes les lignes par un retour à la ligne
        }

        // Fonction AJAX pour mettre à jour le contenu
        function updateSongContent(songId) {
            const content = getFormattedContent(); // Récupérer le contenu formaté
            // Envoyer une requête AJAX pour mettre à jour le champ `content` de la chanson
            $.ajax({
                url: 'rqt-song-add-content-update.php', // Remplacez par votre URL de traitement
                method: 'POST',
                data: {
                    song_id: songId, // Assurez-vous que songId est défini
                    content: content // Envoyer le contenu mis à jour
                },
                success: function(response) {
                    const jsonResponse = JSON.parse(response);
                    if (jsonResponse.success) {
                        // Traitement en cas de succès
                        /*
                        $('#messageBox').empty()
                            .append(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i id="messageBoxInfo">Contenu mis à jour avec succès</i>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            `)
                            .show();
                            */
                    } else {
                        // Traitement en cas d'échec
                        $('#messageBox').empty()
                            .append(`
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i id="messageBoxInfo">Erreur lors de la mise à jour du contenu</i>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            `)
                            .show();
                    }
                },
                error: function() {
                    $('#messageBox').empty()
                        .append(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i id="messageBoxInfo">Erreur de communication avec le serveur.</i>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        `)
                        .show();
                }
            });
        }
        
        let formRow;
        let labelsDiv;
        let inputsDiv;
        // Création d'une div pour le bouton d'enregistrement
        let buttonDiv;
        let addButton;

        // Mise à jour de la fonction generateInputs pour accepter le contenu actuel
        function generateInputs(style) { /*-- DEBUT FONCTION generateInputs (Action Initiale de la chaîne d'actions) --*/
            contentArea.empty();
            // Détection et Vérification du language
            const language = languages.val();
            let dir;
            if(language === 'ar'){
                dir = "rtl";
            } else {
                dir = "ltr";
            }

            formRow = $('<div class="row p-3"></div>');
            labelsDiv = $('<div class="col-4 d-flex text-white flex-column gap-2"></div>');
            
            // Création d'une div pour le bouton d'enregistrement
            buttonDiv = $('<div class="d-flex justify-content-end mt-3"></div>');
            addButton = $('<button id="addButton" type="submit" class="btn btn-primary">Ajouter</button>');

            // Vérification de la largeur de l'écran pour étendre inputsDiv
            if ($(window).width() <= 768) { // Utiliser 768px comme seuil pour les appareils mobiles
                inputsDiv = $('<div class="col-12 d-flex flex-column gap-2"></div>'); // Occupé toutes les colonnes
                //labelsDiv.hide(); // Cacher labelsDiv sur mobile
            } else {
                inputsDiv = $('<div class="col-8 d-flex flex-column gap-2"></div>'); // Occupé 8 colonnes
            }
            switch (style) {
                case 'texte':
                    labelsDiv.append(`<p class="p-1 mt-1">Texte :</p>`);
                    const $editableParagraph = $(`<p contenteditable="true" dir="${dir}" class="form-control ks-editable mb-2" style="min-height: 45px; overflow-y: auto; overflow-x: hidden;"></p>`);
                    
                    inputsDiv.append($editableParagraph);
                
                    // Ajuster la hauteur du champ editable en fonction de son contenu
                    $editableParagraph.on('input keyup focus', function() {
                        $(this).css('height', 'auto'); // Réinitialiser la hauteur pour calculer la nouvelle hauteur
                        $(this).css('height', this.scrollHeight + 'px'); // Ajuster la hauteur à celle du contenu
                    });
                    // Bloquer la touche Entrée 
                    inputsDiv.on('keydown', '[contenteditable="true"]', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault(); // Empêche l'action par défaut de la touche Entrée
                        }
                    });   
                    
                    break;
                case '2_vers':
                    for (let i = 1; i <= 2; i++) {
                        labelsDiv.append(`<p class="p-1 mt-1">Vers ${i} :</p>`);
                        // Créer un élément contenteditable
                        inputsDiv.append(`<p contenteditable="true" dir="${dir}" class="form-control ks-editable mb-3" style="min-height: 45px;"></p>`);

                        // Bloquer la touche Entrée 
                        inputsDiv.on('keydown', '[contenteditable="true"]', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault(); // Empêche l'action par défaut de la touche Entrée
                            }
                        });                        
                    }
                    break;
                case '4_vers':
                    for (let i = 1; i <= 4; i++) {
                        labelsDiv.append(`<p class="p-1 mt-1">Vers ${i} :</p>`);
                        // Créer un élément contenteditable
                        inputsDiv.append(`<p contenteditable="true" dir="${dir}" class="form-control ks-editable mb-2" style="min-height: 45px;"></p>`);

                        // Bloquer la touche Entrée 
                        inputsDiv.on('keydown', '[contenteditable="true"]', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault(); // Empêche l'action par défaut de la touche Entrée
                            }
                        });
                    }
                    break;
                case '5_vers':
                    for (let i = 1; i <= 5; i++) {
                        labelsDiv.append(`<p class="p-1 mt-1">Vers ${i} :</p>`);
                        // Créer un élément contenteditable
                        inputsDiv.append(`<p contenteditable="true" dir="${dir}" class="form-control ks-editable mb-2" style="min-height: 45px;"></p>`);

                        // Bloquer la touche Entrée 
                        inputsDiv.on('keydown', '[contenteditable="true"]', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault(); // Empêche l'action par défaut de la touche Entrée
                            }
                        });
                   
                    }
                    break;
            }

            // Bloquer la touche Entrée et déclencher la fonction pour insérer la ligne sur le screen
            inputsDiv.on('keydown', '[contenteditable="true"]', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Empêche l'action par défaut de la touche Entrée
                    // Déclencher le clic sur le bouton d'ajout
                    addButton.click(); // Simuler le clic sur le bouton d'ajout
                }
            });


            // Vérification de la largeur de l'écran pour cacher/montrer labelsDiv
            if ($(window).width() <= 768) { // Utiliser 768px comme seuil pour les appareils mobiles
                labelsDiv = '';
                //labelsDiv.hide(); // Cacher labelsDiv sur mobile
            }
            formRow.append(labelsDiv);
            formRow.append(inputsDiv);

            buttonDiv.append(addButton);
            contentArea.append(formRow);
            contentArea.append(buttonDiv);

            // Écoute l'événement paste sur les éléments contenteditable nouvellement créés
            formRow.find('[contenteditable="true"]').on('paste', function(e) {
                e.preventDefault(); // Empêche le collage par défaut
                
                // Récupérer le texte collé
                const text = (e.originalEvent || e).clipboardData.getData('text/plain');

                // Remplacer les retours à la ligne par des espaces
                const formattedText = text.replace(/(\r\n|\n|\r)/g, ' '); // Remplacer les retours à la ligne par des espaces

                // Récupérer la sélection actuelle
                const selection = window.getSelection();
                const range = selection.getRangeAt(0); // Obtenir la plage actuelle

                // Créer un nouveau nœud de texte avec le texte formaté
                const textNode = document.createTextNode(formattedText);

                // Effacer la plage actuelle et insérer le nouveau nœud de texte
                range.deleteContents();
                range.insertNode(textNode);

                // Déplacer le curseur après le texte inséré
                range.setStartAfter(textNode);
                range.setEndAfter(textNode);
                selection.removeAllRanges();
                selection.addRange(range);
            });
                        
        } /*-- FIN FONCTION generateInputs --*/
        
        // Écouteur d'événements pour le bouton Enregistrer
        contentArea.on('click', '#addButton', function(e) {     /*-- DEBUT VÉRIFICATION ET ENREGISTREMENT --*/
            e.preventDefault(); // Empêche le comportement par défaut

            const inputs = contentArea.find('.ks-editable');
            const format = formats.val(); // Récupérer le format
            let isArabicText = true;  // Pour vérifier si c'est bien arabic si AR est choisie
            let allFilled = true; // Pour vérifier si tous les champs sont remplis
            // Détection et Vérification du language
            const language = languages.val();
            let dir;
            if(language === 'ar'){
                dir = "rtl";
            } else {
                dir = "ltr";
            }

            // Création de la ks-line contenant le(s) colonne(s) à insérer 
            const newDisplayContent = $(`<div dir="${dir}" class="row ligne ks-line" style="position: relative;"></div>`); // Nouvelle zone de contenu affiché
            
            // Créer une nouvelle instance de sortButton pour chaque nouveau contenu
            const sortButton = $(`<span dir="ltr" class="sort-button hide_on_save" style="position: absolute; z-index: 9999; top: 0; left: 0; cursor: pointer; background-color: transparent; padding: 8px; border-radius: 5px;"></span>`);

            const sortButton_svg = $(`<span title="Réorganiser" class="d-flex align-items-lg-center align-content-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter-left" viewBox="0 0 16 16">
                    <path d="M2 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </span>`);

            sortButton.append(sortButton_svg);

            inputs.each(function() {
                const texte = $(this).text().trim();
                if (texte === '') {
                    allFilled = false; // Si un champ est vide
                    return false; // Sortir de la boucle
                }
                // Si la langue choisie est arabe et que le texte saisie n'est pas arabe
                if (language === 'ar' && !isArabic(texte)) {
                    isArabicText = false;
                }
                // Si la langue choisie n'est pas arabe et que le texte saisie est entièrement arabe
                if (language !== 'ar' && isFullArabic(texte)) {
                    isArabicText = false;
                }

            });
            
            if (!isArabicText) {
                alert("Langue choisie ne correspond pas à la langue du texte saisie."); // Message d'alerte si des champs sont vides
            } else if (!allFilled) {
                alert("Veuillez remplir tous les champs avant de sauvegarder."); // Message d'alerte si des champs sont vides
            } else {
                // Récupérer tout le contenu de inputDiv
                inputs.each(function() {
                    let classList = '';
                    let classList1 = 'txt colonne ks-column col-md-12 col-sm-12 col-xs-12 col-xxs-12 text-center pb-1 circled'; // Classes pour paragraphe
                    let classList2 = 'vers colonne ks-column col-6 text-center circled'; // Classes pour 4 vers
                    let classList3 = 'vers colonne ks-column col-md-6 col-sm-6 col-xs-12 col-xxs-12 text-center pb-1 circled'; // Classes pour 2 ou 5 vers

                    // Condition pour le style du document
                    if (docStyles.val() === 'texte') {
                        classList += classList1; // Ajout d'une classe spécifique si nécessaire
                    } else if (docStyles.val() === '4_vers') {
                        classList += classList2;
                    } else {
                        classList += classList3;
                    }

                    let newElement = ''; // Créer un nouvel élément avec les classes et le contenu
                    let style = docStyles.val();
                    if (style !== '2_vers' && style !== '3_vers' && style !== '4_vers' && style !== '5_vers') {
                        switch (format) {
                            case 'h3':
                                newElement = $(`<div dir="${dir}" class="${classList}"><h3>${$(this).html()}</h3></div>`);
                                break;
                            case 'cite':
                                newElement = $(`<div dir="${dir}" class="${classList}"><cite>${$(this).html()}</cite></div>`);
                                break;
                            default: // 'div' par défaut
                                newElement = $(`<div dir="${dir}" class="${classList}"><p>${$(this).html()}</p></div>`);
                        }
                    } else {
                        newElement = $(`<div dir="${dir}" class="${classList}"><p>${$(this).html()}</p></div>`);
                    }
                    
                    newDisplayContent.append(newElement); // Ajouter à la nouvelle zone d'affichage
                });

                // Ajouter le bouton au nouveau contenu
                newDisplayContent.append(sortButton);

                // Ajouter le nouveau contenu dans la zone d'affichage
                $('#ks-screen').append(newDisplayContent); // Ajoute le nouveau contenu

                // MISE EN ÉVIDENCE DU NOM D'ALLAH ET DU PROPHÈTE(PSL)
                //highlightText();

                // Obtenir le contenu à mettre à jour sans les éléments .hide_on_save
                const contentToUpdate = $('#ks-screen').clone(); // Clone l'élément #ks-screen lui-même
                // Supprimer les enfants .hide_on_save
                contentToUpdate.find('.hide_on_save').remove(); 
                // Convertir le contenu mis à jour en HTML
                const htmlContentToUpdate = contentToUpdate.prop('outerHTML'); // Utiliser outerHTML pour inclure l'élément racine
                const songId = $('#song_id').val();
                updateSongContent(songId, htmlContentToUpdate);

                // Appeler la fonction pour initialiser les tris après l'ajout d'éléments
                initializeSortable();
                initializeSwipeToDelete();
                
                // Activer le tri lorsque l'édition est terminée
                //enableSortable();
                //enableSwipeToDelete();

            } /*-- FIN IF-ELSE DANS VÉRIFICATION ET ENREGISTREMENT --*/

            // Faire défiler jusqu'à l'élément #editor-area en bas de l'écran
            $('html, body').animate({
                scrollTop: $('#editor-area').offset().top - $(window).height() + $('#editor-area').outerHeight()
            }, 500); // Durée de l'animation en millisecondes
            
        }); /*-- FIN VÉRIFICATION ET ENREGISTREMENT --*/


        // Rendre chaque ks-column éditable au clic
        displayArea.on('click', '.ks-line .ks-column', function() {
            const $this = $(this);
            
            // Récupérer la valeur de l'attribut dir dans la même balise que .ks-column
            const thisdirValue = $this.attr('dir'); // Récupérer la valeur de l'attribut dir

            // Vérifier si le contenu est déjà éditable
            if ($this.find('[contenteditable="true"]').length === 0) {
                // Désactiver les tris lorsque le champ éditable est activé
                disableSortable();
                disableSwipeToDelete();
                
                const currentHtml = $this.html(); // Récupérer le HTML actuel
                const editableDiv = $('<div contenteditable="true" class="form-control" style="min-height: 30px;"></div>').html(currentHtml); // Créer un div éditable avec le HTML actuel

                const initialContent = currentHtml;

                if ($this.hasClass('txt')) {
                    // Ajuster la hauteur du div editable en fonction de son contenu
                    editableDiv.on('input keyup focus', function() {
                        $(this).css('height', 'auto'); // Réinitialiser la hauteur pour calculer la nouvelle hauteur
                        $(this).css('height', this.scrollHeight + 'px'); // Ajuster la hauteur à celle du contenu
                    });
                }

                // Créer le mini-toolbar
                const minitoolbar = $('<div id="minitoolbar" class="d-flex justify-content-between border border-primary bg-light pb-1" dir="ltr" style="position: absolute; z-index: 500; top: -35px; left: 0;"></div>');

                // Vérifier si le contenu a des balises p, h3 ou cite
                const regex = /<\/?(p|h3|cite)[^>]*>/g;
                const hasFormat = regex.test(currentHtml);

                // Stocker l'ancienne balise format
                const currentFormatMatch = currentHtml.match(regex);
                const currentFormat = currentFormatMatch ? currentFormatMatch[0].replace(/<\/?/, '').replace(/>/, '') : null; // Récupérer la balise actuelle sans les chevrons

                // Créer le miniFormattingContainer seulement si la colonne a la classe 'txt'
                if ($this.hasClass('txt')) {
                    // Créer le miniFormattingContainer
                    const miniFormattingContainer = $('<div class="d-flex align-items-end" style="margin-right: 0.5em;"></div>');

                    // Ajouter le label pour les formats dans le mini-toolbar
                    const miniFormatLabel = $('<p class="bg-light text-black conditional" style="font-size: 0.7em; padding: 0.2em 0.4em; margin: 0.5em; line-height: 1.2;">Formats</p>'); // Suppression de la marge

                    // Ajouter le label au miniFormattingContainer
                    miniFormattingContainer.append(miniFormatLabel);

                    // Créer le sélecteur pour les formats dans le mini-toolbar
                    const miniFormats = $('<select id="miniFormat" class="form-control w-auto conditional" style="font-size: 0.7em; padding: 0.2em 0.4em; margin-right: 0.5em;"></select>');

                    // Options du sélecteur mini
                    const miniFormatOptions = [
                        { value: 'p', name: 'Paragraph' },
                        { value: 'h3', name: 'Sous Titre' },
                        { value: 'cite', name: 'Préface' }
                    ];

                    // Ajout des options au sélecteur mini
                    miniFormatOptions.forEach(option => {
                        const miniFormatOption = $(`<option value="${option.value}">${option.name}</option>`);
                        miniFormats.append(miniFormatOption);
                    });

                    // Sélectionner l'option correspondante si une balise est trouvée
                    if (currentFormat) {
                        miniFormats.val(currentFormat); // Sélectionne l'option correspondante
                    }

                    // Ajouter le miniFormats au miniFormattingContainer
                    miniFormattingContainer.append(miniFormats);

                    // Ajouter le miniFormattingContainer dans le minitoolbar
                    minitoolbar.append(miniFormattingContainer);

                    // Gestion de la sélection de format dans le mini-toolbar
                    miniFormats.change(function() {
                        const selectedFormat = $(this).val(); // Récupérer le format sélectionné
                        const $currentContent = $this.find('[contenteditable="true"]'); // Récupérer le contenu en cours d'édition
                        const currentHtml = $currentContent.html(); // Récupérer le HTML actuel

                        if (hasFormat) {
                            // Remplacer uniquement la balise ouvrante et fermante existante
                            const newHtml = currentHtml.replace(regex, (match) => {
                                return match.startsWith('</') ? `</${selectedFormat}>` : `<${selectedFormat}>`;
                            });
                            $currentContent.html(newHtml); // Mettre à jour le contenu
                        }
                    });
                }

                // Vider le contenu de la colonne et ajouter le mini-toolbar et le div éditable
                $this.empty().append(minitoolbar).append(editableDiv); 

                // Si du texte est collé dans le div en cours d'édition 
                editableDiv.on('paste', function(e) {
                    e.preventDefault(); // Empêche le collage par défaut
                    
                    // Récupérer le texte collé
                    const text = (e.originalEvent || e).clipboardData.getData('text/plain');

                    // Remplacer les retours à la ligne par des espaces
                    const formattedText = text.replace(/(\r\n|\n|\r)/g, ' '); // Remplacer les retours à la ligne par des espaces

                    // Récupérer la sélection actuelle
                    const selection = window.getSelection();
                    const range = selection.getRangeAt(0); // Obtenir la plage actuelle

                    // Créer un nouveau nœud de texte avec le texte formaté
                    const textNode = document.createTextNode(formattedText);

                    // Effacer la plage actuelle et insérer le nouveau nœud de texte
                    range.deleteContents();
                    range.insertNode(textNode);

                    // Déplacer le curseur après le texte inséré
                    range.setStartAfter(textNode);
                    range.setEndAfter(textNode);
                    selection.removeAllRanges();
                    selection.addRange(range);
                });
                       

                // Écouteur pour quand l'utilisateur appuie sur Entrée
                editableDiv.on('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Empêcher l'insertion d'une nouvelle ks-line

                        const newHtml = $(this).html(); // Récupérer le contenu HTML du div
                        const $newHtmlElement = $(newHtml); // Créer un jQuery element à partir de newHtml
                        
                        // Lors de la vérification du texte pour déterminer l'orientation
                        const textContent = $newHtmlElement.text().trim(); // Récupérer le texte brut et enlever les espaces
                        const initialTextContent = $(initialContent).text().trim(); // Créer un jQuery element à partir de initialContent et récupérer le texte brut

                        // Vérification si le texte est vide
                        if (textContent === '') {
                            $this.html(initialContent); // Rétablir le contenu initial

                            alert("Le contenu est vide, aucune mise à jour effectuée.");
                            return; // Sortir de la fonction sans faire de mise à jour
                        }

                        if (thisdirValue === "rtl" && !isArabic(textContent)) {
                            // Remettre le contenu initial
                            $this.html(initialContent); // Rétablir le contenu initial

                            alert("Le texte saisie ne correspond pas à la langue de base (Arabe). \n Veuillez saisir du texte arabique \noubien supprimer cette ligne et en créer une autre.");
                            return; // Sortir de la fonction sans faire de mise à jour
                            
                        } else if (thisdirValue === "ltr" && isFullArabic(textContent) && isFullArabic(textContent)) {
                            // Remettre le contenu initial
                            $this.html(initialContent); // Rétablir le contenu initial

                            alert("Le texte saisie ne correspond pas à la langue de base (Français/Anglais). \n Veuillez saisir du texte français ou anglais \noubien supprimer cette ligne et en créer une autre.");
                            return; // Sortir de la fonction sans faire de mise à jour
                            
                        }

                        $this.html(newHtml); // Remplacer le contenu de la colonne par le nouveau HTML                                    
                        
                        // Obtenir le contenu à mettre à jour sans les éléments .hide_on_save
                        const contentToUpdate = $('#ks-screen').clone(); // Clone l'élément #ks-screen lui-même
                        // Supprimer les enfants .hide_on_save
                        contentToUpdate.find('.hide_on_save').remove(); 
                        // Convertir le contenu mis à jour en HTML
                        const htmlContentToUpdate = contentToUpdate.prop('outerHTML'); // Utiliser outerHTML pour inclure l'élément racine
                        const songId = $('#song_id').val();
                        updateSongContent(songId, htmlContentToUpdate);

                        // Rétablir les tris lorsque l'édition est terminée
                        enableSortable();
                        enableSwipeToDelete();
                    }
                });

                // Exclure les clics sur le mini-toolbar des zones où un clic remet le champ d'édition en normal
                minitoolbar.on('mousedown', function(e) {
                    e.stopPropagation(); // Empêcher la propagation du clic
                });

                // Écouteur pour perdre le focus
                editableDiv.on('blur', function() {
                    if (!minitoolbar.is(':hover')) { // Vérifie si le mini-toolbar et ses boutons ne sont pas survolés
                
                        const newHtml = $(this).html(); // Récupérer le contenu HTML du div
                        const $newHtmlElement = $(newHtml); // Créer un jQuery element à partir de newHtml
                        
                        // Lors de la vérification du texte pour déterminer l'orientation
                        const textContent = $newHtmlElement.text().trim(); // Récupérer le texte brut et enlever les espaces
                        const initialTextContent = $(initialContent).text().trim(); // Créer un jQuery element à partir de initialContent et récupérer le texte brut

                        // Vérification si le texte est vide
                        if (textContent === '') {
                            $this.html(initialContent); // Rétablir le contenu initial

                            alert("Le contenu est vide, aucune mise à jour effectuée.");
                            return; // Sortir de la fonction sans faire de mise à jour
                        }

                        if (thisdirValue === "rtl" && !isArabic(textContent)) {
                            // Remettre le contenu initial
                            $this.html(initialContent); // Rétablir le contenu initial

                            alert("Le texte saisie ne correspond pas à la langue de base (Arabe). \n Veuillez saisir du texte arabique \noubien supprimer cette ligne et en créer une autre.");
                            return; // Sortir de la fonction sans faire de mise à jour
                            
                        } else if (thisdirValue === "ltr" && isFullArabic(textContent) && isFullArabic(textContent)) {
                            // Remettre le contenu initial
                            $this.html(initialContent); // Rétablir le contenu initial

                            alert("Le texte saisie ne correspond pas à la langue de base (Français/Anglais). \n Veuillez saisir du texte français ou anglais \noubien supprimer cette ligne et en créer une autre.");
                            return; // Sortir de la fonction sans faire de mise à jour
                            
                        }

                        $this.html(newHtml); // Remplacer le contenu de la colonne par le nouveau HTML                                    
                        
                        // Obtenir le contenu à mettre à jour sans les éléments .hide_on_save
                        const contentToUpdate = $('#ks-screen').clone(); // Clone l'élément #ks-screen lui-même
                        // Supprimer les enfants .hide_on_save
                        contentToUpdate.find('.hide_on_save').remove(); 
                        // Convertir le contenu mis à jour en HTML
                        const htmlContentToUpdate = contentToUpdate.prop('outerHTML'); // Utiliser outerHTML pour inclure l'élément racine
                        const songId = $('#song_id').val();
                        updateSongContent(songId, htmlContentToUpdate);

                        // Rétablir les tris lorsque l'édition est terminée
                        enableSortable();
                        enableSwipeToDelete();

                    } else {
                        editableDiv.focus(); // Rétablir le focus sur le div éditable si le mini-toolbar est survolé
                    }
                });

                // Gestion des événements pour le mini-toolbar
                minitoolbar.on('mouseenter', function() {
                    // Si le mini-toolbar est survolé, ne rien faire, focus reste sur editableDiv
                });

                minitoolbar.on('mouseleave', function() {
                    // Lorsque le mini-toolbar n'est plus survolé, on peut éventuellement faire quelque chose ici si besoin
                });

                editableDiv.focus(); // Mettre le focus sur le div éditable
            }
        }); /*-- FIN MÉTHODE MODIFICATION DE COLONNE --*/

        // Initialisation
        generateInputs('texte'); // Génère les inputs par défaut au chargement
        return this; // Permet le chaînage
    };  /*-- FIN FONCTION PRINCIPALES --*/

    
}(jQuery));     /*-- FIN APPLICATION --*/


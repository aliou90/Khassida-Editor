/* GESTION DES ÉVÉNEMENTS (CLIC)*/
function deleteAccount(userId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce compte définitivement ?')) {
        return;
    }

    $.ajax({
        url: 'rqt-delete-account2.php', // Le fichier PHP qui gère la suppression
        type: 'POST',
        data: { id: userId },
        success: function(response) {
            if (response.success) {
                // Redirection vers profile.php avec un message de succès stocké en session
                window.location.href = 'profile.php?super_admin_users=super_admin&success_message=' + encodeURIComponent(response.message);
            } else {
                alert(response.message); // Affiche un message d'erreur si la suppression échoue
            }
        },
        error: function(xhr, status, error) {
            alert("Une erreur s'est produite lors de la suppression du compte.");
        }
    });
}



// Fonction pour activer ou désactiver un compte utilisateur
function toggleAccountState(userId, action) {
    $.ajax({
        url: 'rqt-toggle-account-activation.php', // Le fichier PHP qui gère l'activation et la désactivation du compte
        type: 'POST',
        data: { id: userId, action: action },
        success: function(response) {
            if (response.success) {
                // Mettre à jour l'affichage de l'état du compte
                $('#account-state').text(response.newState);
                $('#toggle-button').attr('onclick', `toggleAccountState(${userId}, '${response.newAction}')`);
                $('#toggle-button').text(response.newActionText);
                showAlert(response.message, 's'); // Affiche un message de succès
            } else {
                showAlert(response.message, 'd'); // Affiche un message d'erreur
            }
        },
        error: function(xhr, status, error) {
            showAlert("Une erreur s'est produite lors de la mise à jour du compte.", 'd');
        }
    });
}


// Gestion des droits
// Fonction pour mettre à jour le type de compte utilisateur
function updateAccountType(userId, action) {
    $.ajax({
        url: 'rqt-update-account-type.php', // Le fichier PHP qui gère la mise à jour du compte
        type: 'POST',
        data: { id: userId, action: action },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 's');
                // Mettre à jour l'affichage du type de compte
                $('#account-type').text(response.newRole);
            } else {
                showAlert(response.message, 'd');
            }
        },
        error: function(xhr, status, error) {
            showAlert("Une erreur s'est produite lors de la mise à jour du compte.", 'd');
        }
    });
}

// Super - administrateur - Supprimer un compte 
// Fonction pour supprimer un compte utilisateur
function supprimerCompte(userId) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer ce compte utilisateur ?")) {
        return;
    }

    $.ajax({
        url: 'rqt-delete-account.php', // Le fichier PHP qui gère la suppression
        type: 'POST',
        data: { id: userId },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 's');
                // Supprimer l'utilisateur de la liste sans recharger la page
                $(`a[data-user-id="${userId}"]`).closest('tr').remove();
            } else {
                showAlert(response.message, 'd');
            }
        },
        error: function(xhr, status, error) {
            showAlert("Une erreur s'est produite lors de la suppression du compte.", 'd');
        }
    });
}


// Au clic du bouton supprimer chanson définitivement / pour super administrateur
function supprimerChansonDefinitivement(songId) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer cette œuvre définitivement ?")) {
        return;
    }

    $.ajax({
        url: 'rqt-permanent-delete-song.php', // Le fichier PHP qui gère la suppression
        type: 'POST',
        data: { id: songId },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 's');
                // Retirer la chanson supprimée de la liste sans recharger la page
                $(`a[data-song-id="${songId}"]`).closest('li').remove();
            } else {
                showAlert(response.message, 'd');
            }
        },
        error: function(xhr, status, error) {
            showAlert("Une erreur s'est produite lors de la suppression.", 'd');
        }
    });
}

// Supprimer de ma collection
function supprimerDeMaCollection(button) {
    // Empêche le comportement par défaut
    event.preventDefault();
    
    // Récupère les données à partir des attributs data- du bouton
    const songId = button.getAttribute('data-song-id');
    const songTitle = button.getAttribute('data-song-title');

    // Demande confirmation à l'utilisateur
    if (confirm(`Voulez-vous vraiment supprimer "${songTitle}" de votre collection ?`)) {
        // Envoie une requête AJAX
        fetch('rqt-song-delete-from-collection.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                song_id: songId
            })
        })
        .then(response => response.json())
        .then(data => {
            // Affiche le message dans la div
            showAlert(data.message, data.success ? 's' : 'w');

            // Supprime l'élément de la page si la suppression a réussi
            if (data.success) {
                // Exemple : supprimer le bouton de la page
                button.closest('li').remove();
            }
        })
        .catch(error => {
            console.error("Erreur lors de la suppression de la chanson :", error);
            showAlert("Une erreur s'est produite lors de la suppression de l'œuvre.", 'd');
        });
    }
}

// AFFICHEUR DE MESSAGE D'ALERT
function showAlert(message, type="i") {
    // Supprimer l'ancienne alerte si elle existe
    $('#songActionsInfo .alert').remove();

    let alertType;
    if (type === 's') {
        alertType = 'success';
    } else if (type === 'w') {
        alertType = 'warning';
    } else if (type === 'd') {
        alertType = 'warning';
    } else {
        alertType = 'info';
    }

    // Créer une nouvelle alerte
    const newAlert = `
        <div class="alert alert-${alertType} alert-dismissible fade show" role="alert">
            <i>${message}</i>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    // Ajouter l'alerte à #songActionsInfo
    $('#songActionsInfo').append(newAlert);
}

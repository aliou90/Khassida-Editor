<?php
// Fonction pour afficher la liste des chansons sauf les super admin
require_once __DIR__.'/config.php';
include_once __DIR__.'/express-functions.php';


function afficher_tous_les_utilisateurs($pdo){
    $query = $pdo->prepare('SELECT * FROM users WHERE is_super_admin <> :def ORDER BY users.name ASC');
    $query->bindValue(':def', 1);
    $query->execute();
    $utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);
    return $utilisateurs;
}
// Rechercher utilisateur par id
function rechercher_utilisateur_par_id($pdo, $id){
    $query = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $utilisateur = $query->fetch(PDO::FETCH_ASSOC);
    return $utilisateur;
}

// Rechercher utilisateur par code
function rechercher_utilisateur_par_code($pdo, $code){
    $query = $pdo->prepare('SELECT * FROM users WHERE activation_code = ?');
    $query->execute([$code]);
    $utilisateur = $query->fetch(PDO::FETCH_ASSOC);
    return $utilisateur;
}

// Ajouter code d'activation
function ajouter_code_activation($pdo, $code, $email){
    $stmt = $pdo->prepare("UPDATE users SET activation_code = ? WHERE email = ?");
    $result = $stmt->execute([$code, $email]);
}

// Rechercher compte par email
function rechercher_utilisateur_par_email($pdo, $uname){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND state = ?");
    $stmt->execute([$uname, 1]);
    $user = $stmt->fetch();
    return $user;
}

// Rechercher tout compte par email
function rechercher_tout_utilisateur_par_email($pdo, $uname){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? ");
    $stmt->execute([$uname]);
    $user = $stmt->fetch();
    return $user;
}

// Vérification que l'email n'existe pas déjà dans la base de données
function verifier_email_exist($pdo, $email){
    $result = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=?");
    $result->execute([$email]);
    $count = $result->fetchColumn();
    if ($count > 0) {
        return true;
    }else {
        return false;
    }
}

// Ajout de l'utilisateur dans la base de données avec le compte désactivé
function ajouter_utilisateur($pdo, $name, $email, $hashed_password, $activation_code){
    $result = $pdo->prepare("INSERT INTO users (name, email, password, state, is_admin, is_hight_admin, is_super_admin, activation_code, inscription_date) VALUES (?, ?, ?, 0, 0, 0, 0, ?, ?)");
    $result->execute([$name, $email, $hashed_password, $activation_code, date('Y-m-d H:i:s')]);
}

// Modifier mot de passe utilisateur
function modifier_pass($pdo, $hashed_password, $email){
    $stmt = $pdo->prepare("UPDATE users SET password = ?, activation_code = ?, state = ? WHERE email = ?");
    $result = $stmt->execute([$hashed_password, null, 1, $email]);
}

// Insérer une nouvelle oeuvre
function inserer_chanson(PDO $pdo, string $title, string $content, string $category, int $user_id) {
    // Validation des données
    $errors = [];
    if (empty($title)) {
        $errors[] = 'Le titre est obligatoire';
    }
    if (empty($content)) {
        $errors[] = 'Le contenu est obligatoire';
    }

    if (empty($category)) {
        $errors[] = 'La catégorie est obligatoire';
    }
    // Insertion des données
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO songs (user_id, title, content, category, approval, add_date) VALUES (?, ?, ?, ?, 0, ?)');
            $add_date = date('Y-m-d H:i:s'); // Date et heure actuelle
            $stmt->execute([$user_id, $title, $content, $category, $add_date]);
            return intval($pdo->lastInsertId());
        } catch (PDOException $e) {
            return "Erreur d'insertion : " . $e->getMessage();
        }
    } else {
        return implode('<br>', $errors);
    }
  }

function modifier_chanson(PDO $pdo, int $id, string $title, string $content, string $category) {

    // Validation des données
    $errors = [];
    if (empty($title)) {
        $errors[] = 'Le titre est obligatoire';
    }
    if (empty($content)) {
        $errors[] = 'Le contenu est obligatoire';
    }

    if (empty($category)) {
        $errors[] = 'La catégorie est obligatoire';
    }

    // Si les données sont valides, mise à jour de la chanson
    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE songs SET title = :title, content = :content, category = :category WHERE id = :id');
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':category' => $category,
            ':id' => $id
        ]);
        return $id;
    } else {
        return implode('<br>', $errors);
    }
}

// Modifier image de profil
function modifier_user_image($pdo) {
    $extensions_valides = array('jpg', 'jpeg', 'gif', 'png'); // Liste des extensions valides
    $extension_upload = strtolower(substr(strrchr($_FILES['picture']['name'], '.'), 1)); // Récupération de l'extension de l'image
    $erreur = ""; // Initialisation de la variable d'erreur
  
    // Vérification de l'extension de l'image
    if (!in_array($extension_upload, $extensions_valides)) {
      $erreur = "Erreur : Le fichier n'est pas une image valide (extensions autorisées : " . implode(', ', $extensions_valides) . ").";
    }
  
    // Vérification de la taille de l'image
    if ($_FILES['picture']['size'] > 1000000) {
      $erreur = "Erreur : Le fichier est trop volumineux (taille maximale : 1 Mo).";
    }
  
    // Si aucune erreur n'a été détectée, on réécrit et enregistre l'image dans le dossier
    if (empty($erreur)) {
      // Ouvrir l'image téléchargée
      $img_source = null;
      switch ($extension_upload) {
        case 'jpg':
        case 'jpeg':
          $img_source = imagecreatefromjpeg($_FILES['picture']['tmp_name']);
          break;
        case 'gif':
          $img_source = imagecreatefromgif($_FILES['picture']['tmp_name']);
          break;
        case 'png':
          $img_source = imagecreatefrompng($_FILES['picture']['tmp_name']);
          break;
      }
  
      // Si l'ouverture de l'image a échoué, retourner une erreur
      if (!$img_source) {
        $erreur = "Erreur : Impossible de réécrire l'image.";
      }
  
      // Réécrire l'image dans le format souhaité
      if (!$erreur) {
        $img_destination = imagecreatetruecolor(200, 200); // Créer une image vide de 200x200 pixels
        imagecopyresampled($img_destination, $img_source, 0, 0, 0, 0, 200, 200, imagesx($img_source), imagesy($img_source)); // Réécrire l'image dans la nouvelle taille
        $img = 'profile_'.$_SESSION['user_id'] . ".jpg";
        $fichier = "assets/images/profiles/profile_" . $_SESSION['user_id'] . ".jpg"; // Nom du fichier à enregistrer
        if (file_exists($fichier)) { // Suppression de l'ancien fichier s'il existe
          unlink($fichier);
        }
        imagejpeg($img_destination, $fichier); // Enregistrement de la nouvelle image
        $_SESSION['profile_picture'] = $fichier; // Mise à jour de la session avec le nouveau nom de fichier
        $requete = $pdo->prepare("UPDATE users SET profile_picture = :profile_picture WHERE id = :user_id");
        $requete->execute(array(
          'profile_picture' => $img,
          'user_id' => $_SESSION['user_id']
        ));
        // Définition de la session du photo de profil
        $_SESSION['profile_picture'] = $img;
      }
  
      // Libérer la mémoire utilisée par les images GD
      imagedestroy($img_source);
      imagedestroy($img_destination);
      return 1;
    }
  
    return $erreur; // Retourne une éventuelle erreur détectée
  }
  
// Modifier les infos de Profil Utilisateur
function modifier_user_infos($pdo, $name, $email, $country, $region, $phone, $user_id) {
    // Vérifier que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return "L'adresse email n'est pas valide.";
    }

    // Mettre à jour les informations de l'utilisateur dans la base de données
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, country = ?, region = ?, phone = ? WHERE id = ?");
    $result = $stmt->execute([$name, $email, $country, $region, $phone, $user_id]);

    // Vérifier si la mise à jour a réussi
    if (!$result) {
      return "La mise à jour du profil a échoué. Veuillez réessayer plus tard.";
    }

    // Mettre à jour les informations de l'utilisateur dans la session
    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;
    $_SESSION['country'] = $country;
    $_SESSION['region'] = $region;
    $_SESSION['phone'] = $phone;

    return true;
}

  
// Activer un compte utilisateur
function activer_compte($pdo, $user_id) {
    $query = "UPDATE users SET state = 1 WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $state = 'Le compte a été activé avec succès!';
    return $state;
}
  
  // Désactiver un compte utilisateur
function desactiver_compte($pdo, $user_id) {
    $query = "UPDATE users SET state = 0 WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $state = 'Le compte a été désactivé.';
    return $state;
}

function supprimer_compte($pdo, $user_id) {
    // Supprimer la sélection de l'utilisateur
    $query = "DELETE FROM selection WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Supprimer les approbations de l'utilisateur
    $query = "DELETE FROM approval WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Supprimer le compte utilisateur
    $query = "DELETE FROM users WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $state = 'Le compte a été supprimé.';
    return $state;
}

function definir_correcteur($pdo, $user_id) {
    // Mettre à jour le champ "is_admin" de l'utilisateur
    $query = "UPDATE users SET is_admin = 1, is_hight_admin = 1 WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $state = "Le type compte a été défini en correcteur avec succès !";
    return $state;
}
function definir_editeur($pdo, $user_id) {
    // Mettre à jour le champ "is_admin" de l'utilisateur
    $query = "UPDATE users SET is_admin = 1, is_hight_admin = 0 WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $state = "Le type compte a été défini en éditeur avec succès !";
    return $state;
}
function definir_lecteur($pdo, $user_id) {
    // Mettre à jour le champ "is_admin" de l'utilisateur
    $query = "UPDATE users SET is_admin = 0, is_hight_admin = 0 WHERE id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $state = "Le type compte a été défini en lecteur avec succès !";
    return $state;
  }
    
    
function afficher_chansons($pdo) {
    $sql = "SELECT * FROM songs WHERE approval >= :approval";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':approval', 1, PDO::PARAM_INT);
    $stmt->execute();
    $chansons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $chansons;
}

// Fonction pour rechercher des chansons par titre
function rechercher_chansons_par_titre($pdo, $title) {
    if (isArabic($title)) {
        // Supprimer les harakats du titre
        $title = removeHarakat($title);
        
        // Supprimer les harakats des titres des chansons de la base de données
        $sql = "SELECT id, title, content, category FROM songs WHERE approval >= :approval";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':approval', 1, PDO::PARAM_INT);
        $stmt->execute();
        $chansons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($chansons as &$chanson) {
            $chanson['title'] = removeHarakat($chanson['title']);
        }
        unset($chanson);
        
        // Rechercher les chansons correspondant au titre sans harakats recherché
        $chansons_correspondantes = array();
        foreach ($chansons as $chanson) {
            $titre_sans_harakats = removeHarakat($chanson['title']);
            if (strpos($titre_sans_harakats, $title) !== false) {
                $chansons_correspondantes[] = $chanson;
            }
        }
    } else {
        // Rechercher les chansons correspondant au titre recherché
        $sql = "SELECT * FROM songs WHERE title LIKE :title AND approval >= :approval";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', '%' . $title . '%');
        $stmt->bindValue(':approval', 1, PDO::PARAM_INT);
        $stmt->execute();
        $chansons_correspondantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $chansons_correspondantes;
}


// Récupération de la chanson correspondante à l'ID et qui sont approuvées
function rechercher_chansons_par_id($pdo, $id) {
    $sql = "SELECT * FROM songs WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $chansons = $stmt->fetch(PDO::FETCH_ASSOC);
    return $chansons;
}

// Rechercher les chansons non approuvees pour les correcteurs
function rechercher_chansons_non_approuvees($pdo) {
    $sql = "SELECT * FROM songs WHERE approval <= :approval ORDER BY approval,title ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':approval', 0, PDO::PARAM_INT);
    $stmt->execute();
    $chansons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $chansons;
}

// Vérifier mon approbation du chanson
function verifier_approbation($pdo, $user_id, $song_id) {
    $sql = "SELECT is_approved, is_disapproved FROM approval WHERE user_id = :user_id AND song_id = :song_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':song_id', $song_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        if ($result['is_approved'] == 1) {
            return 1; // À déjà approuvé
        } elseif ($result['is_disapproved'] == 1) {
            return 2; // À déjà désapprouvé
        }
    }
    
    return 0; // N'a pas encore approuvé
}

// Approuver une chanson
function verifier_et_approuver_chanson($pdo, $user_id, $song_id) {
    $sql_select = "SELECT COUNT(*) FROM approval WHERE user_id = :user_id AND song_id = :song_id AND is_approved = :is_approved";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_select->bindValue(':song_id', $song_id, PDO::PARAM_INT);
    $stmt_select->bindValue(':is_approved', 1, PDO::PARAM_INT);
    $stmt_select->execute();
    $row_count = $stmt_select->fetchColumn();
    
    if ($row_count == 0) {
        $sql_update = "UPDATE songs SET approval = approval + 1 WHERE id = :id_chanson";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindValue(':id_chanson', $song_id, PDO::PARAM_INT);
        $stmt_update->execute();
        
        //Vérifier la présence de l'utilisateur dans approval et approuver
        $sql_select = "SELECT COUNT(*) FROM approval WHERE user_id = :user_id AND song_id = :song_id";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_select->bindValue(':song_id', $song_id, PDO::PARAM_INT);
        $stmt_select->execute();
        $row_count2 = $stmt_select->fetchColumn();
        if ($row_count2 == 0) {
            $sql_insert = "INSERT INTO approval (user_id, song_id, is_approved, is_disapproved) VALUES (:user_id, :song_id, 1, 0)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_insert->bindValue(':song_id', $song_id, PDO::PARAM_INT);
            $row_count2 = $stmt_insert->execute();
        }else {
            $sql_update = "UPDATE approval SET is_approved = 1, is_disapproved = 0 WHERE user_id = :user_id AND song_id = :song_id";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_update->bindValue(':song_id', $song_id, PDO::PARAM_INT);
            $stmt_update->execute();
        }
        
        return true;

    } else {
        return false;
    }
}

// Désapprouver une chanson
function verifier_et_desapprouver_chanson($pdo, $user_id, $song_id) {
    $sql_select = "SELECT COUNT(*) FROM approval WHERE user_id = :user_id AND song_id = :song_id AND is_disapproved = :is_disapproved";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_select->bindValue(':song_id', $song_id, PDO::PARAM_INT);
    $stmt_select->bindValue(':is_disapproved', 1, PDO::PARAM_INT);
    $stmt_select->execute();
    $row_count = $stmt_select->fetchColumn();

    if ($row_count == 0) {
        $sql_update = "UPDATE songs SET approval = approval - 1 WHERE id = :id_chanson";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindValue(':id_chanson', $song_id, PDO::PARAM_INT);
        $stmt_update->execute();
        
        //Vérifier la présence de l'utilisateur dans approval et désapprouver
        $sql_select = "SELECT COUNT(*) FROM approval WHERE user_id = :user_id AND song_id = :song_id";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_select->bindValue(':song_id', $song_id, PDO::PARAM_INT);
        $stmt_select->execute();
        $row_count2 = $stmt_select->fetchColumn();
        if ($row_count2 == 0) {
            $sql_insert = "INSERT INTO approval (user_id, song_id, is_approved, is_disapproved) VALUES (:user_id, :song_id, 0, 1)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_insert->bindValue(':song_id', $song_id, PDO::PARAM_INT);
            $row_count2 = $stmt_insert->execute();
        }else {
            $sql_update = "UPDATE approval SET is_approved = 0, is_disapproved = 1 WHERE user_id = :user_id AND song_id = :song_id";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_update->bindValue(':song_id', $song_id, PDO::PARAM_INT);
            $stmt_update->execute();
        }
        
        
        return true;
        
    } else {
        return false;
    }
}


// Fonction pour rechercher des chansons par categorie
function rechercher_chansons_par_categorie($pdo, $cat) {
    // Préparation de la requête SQL
    $query = "SELECT * FROM songs WHERE category = :cat AND approval >= :approval";
    $statement = $pdo->prepare($query);
    $statement->bindParam(":cat", $cat, PDO::PARAM_STR);
    $statement->bindValue(':approval', 1, PDO::PARAM_INT);

    // Exécution de la requête
    $statement->execute();

    // Récupération des résultats
    $chansons = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Retourne les chansons trouvées
    return $chansons;
}

// Fonction pour rechercher des termes dans une chanson
function rechercher_termes_dans_chanson($pdo, $song_id, $terme) {
    $sql = "SELECT COUNT(*) AS nb_resultats, GROUP_CONCAT(vers SEPARATOR '<br>') AS resultats FROM vers WHERE song_id = :song_id AND vers LIKE :terme";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':song_id', $song_id);
    $stmt->bindValue(':terme', '%' . $terme . '%');
    $stmt->execute();
    $resultats = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultats;
}

function afficher_selection($pdo, $user_id) {
    $stmt = $pdo->prepare('SELECT songs.id, songs.title, songs.content, songs.category, selection.id as selection_id
                           FROM songs
                           INNER JOIN selection ON songs.id = selection.song_id
                           WHERE selection.user_id = :user_id ORDER BY selection.summary ASC');
    $stmt->execute(['user_id' => $user_id]);
    $chansons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $chansons;
}
function afficher_mes_oeuvres($pdo, $user_id) {
    $stmt = $pdo->prepare('SELECT songs.id, songs.title, songs.content, songs.category, songs.approval
                           FROM songs
                           WHERE songs.user_id = :user_id ORDER BY songs.id DESC');
    $stmt->execute(['user_id' => $user_id]);
    $chansons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $chansons;
}

function afficher_tout($pdo) {
    $sql = "SELECT * FROM songs ORDER BY approval,title ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $chansons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $chansons;
}

function ajouter_a_ma_collection($pdo, $user_id, $song_id) {
    $add_song_msg = '';
    // Vérifier si le chanson n'est pas déjà dans la selection de l'utilisateur
    $result = $pdo->prepare('SELECT * FROM selection WHERE user_id = ? AND song_id = ?');
    $result->execute([$user_id, $song_id]);
    $my_song = $result->fetch();

    if($my_song) {
        // Chanson déjà présente dans la selection de l'utilisateur
        $add_song_msg = "Cette œuvre est déjà dans votre collection";
    } else {
        // Ajouter le chanson à la selection de l'utilisateur
        $result = $pdo->prepare('INSERT INTO selection (user_id, song_id) VALUES (?, ?)');
        $result->execute([$user_id, $song_id]);

        $add_song_msg = "L'œuvre a été ajoutée à votre collection";
    }

    return $add_song_msg;
}


function verifier_si_la_chanson_est_dans_ma_collection($pdo, $user_id, $song_id) {
    $sql = "SELECT COUNT(*) FROM selection WHERE user_id = :user_id AND song_id = :song_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return ($count > 0);
}

function supprimer_de_ma_collection($pdo, $user_id, $song_id) {
    // Vérifier si l'utilisateur a déjà ajouté cette chanson à sa sélection
    $query = $pdo->prepare("SELECT * FROM selection WHERE user_id = :user_id AND song_id = :song_id");
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->bindParam(':song_id', $song_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $query->closeCursor();
  
    if ($result) {
      // Si la chanson est déjà dans la sélection, la supprimer
      $query = $pdo->prepare("DELETE FROM selection WHERE user_id = :user_id AND song_id = :song_id");
      $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $query->bindParam(':song_id', $song_id, PDO::PARAM_INT);
      $query->execute();
      $query->closeCursor();
  
      return true;
    } else {
      // Si la chanson n'est pas dans la sélection, ne rien faire
      return false;
    }
  }
  

function supprimer_definitivement_chanson($pdo, $id) {
    // Supprimer toutes les sélections contenant cette chanson
    $sql1 = "DELETE FROM selection WHERE song_id = ?";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute([$id]);
    // Supprimer toutes les approbations contenant cette chanson
    $sql2 = "DELETE FROM approval WHERE song_id = ?";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([$id]);

    // Supprimer la chanson de la base de données
    $sql3 = "DELETE FROM songs WHERE id = ?";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute([$id]);
    return true;
}

// Lister tous les vers (ligne contenant '---') de chanson arabes de catégorie (zikr', 'salat', 'madh')
function lister_tous_vers($pdo){
    $stmt = $pdo->prepare('SELECT * FROM songs WHERE category != ? AND  approval >= ? ');
    $stmt->execute(['quran', 1]);
    $chansons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $vers = [];
    foreach ($chansons as $chanson) {
        $id = $chanson['id'] ;
        $title = $chanson['title'] ;
        $texte = $chanson['content'];
        $this_vers = explode("\n", $texte);
        foreach ($this_vers as $v) {
            if (strpos($v, '---') !== false) {
                $vs = [$id, $title, $v];
                $vers[] = $vs;
            }
        }
    }
    return $vers;
}

// Inscription à la Newsletter
function newsletter_inscription($pdo, $email, $app){
    $errors = '';

    // Vérifiez si l'e-mail est déjà inscrit
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM newsletter WHERE email=?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $errors = "Cet e-mail est déjà inscrit.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Si l'adresse email n'est pas valide, afficher un message d'erreur
        $errors = "Veuillez entrer une adresse email valide.";
    } else {
        // L'e-mail n'est pas encore inscrit, insérez-le dans la base de données
        $date = date('Y-m-d H:i:s'); // Utilisez le format de date SQL
        $insertSql = "INSERT INTO newsletter (email, join_date) VALUES (:email, :dt)";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->bindParam(":email", $email, PDO::PARAM_STR);
        $insertStmt->bindParam(":dt", $date, PDO::PARAM_STR);
        $insertStmt->execute();
        return "Réussie! Merci de vous être inscrit à notre newsletter. Vous recevrez toutes les nouveautés de " . $app['name'] . "."; 
    }
    
    return $errors;
}


// Enregistrer transaction
function enregistrer_transaction($pdo, $nom, $email, $montant){
    // Enregistrement de la transaction dans la base de données
    $stmt = $pdo->prepare("INSERT INTO transactions (name, mail, amount, date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $montant, date('Y-m-d H:i:s')]);
    return true;
}



?>

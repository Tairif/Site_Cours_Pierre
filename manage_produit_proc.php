<?php
    $bdd = new Data();

    // Todo Suppression des Images du produit
    if(isset($_GET['delete_img']) && !empty($_GET['delete_img'])) {
        // On recupere le nom du fichier pour le supprimer du disque
        $file = $bdd->squery('SELECT nom_fichier FROM t_produit_image WHERE id=' . $_GET['delete_img']);

        // On supprime le fichier di disque
        @unlink('images/produit/' . $file);

        // On supprime l'entrée dans la base de données (table t_produit_image)
        $bdd->sql_delete('t_produit_image', $_GET['delete_img']);

        header('Location: index.php?page=manage_produit&id_produit=' . $_GET['id_produit']);
        exit();
    }

    // Traitement du formulaire
    if(isset($_POST) && !empty($_POST)){
        // On revient d'un formulaire

        $h = array();
        $h['isActif'] = (isset($_POST['form_isActif'])?1:0);

        // Date debut promotion
        if(!empty($_POST['form_date_debut_promotion'])){
            $tab_deb = explode('-',$_POST['form_date_debut_promotion']);
            $h['date_debut_promotion'] = mktime(0,0,0,$tab_deb[1],$tab_deb[2],$tab_deb[0]);
        } else {
            $h['date_debut_promotion'] = 0;
        }

        // Date fin promotion
        if(!empty($_POST['form_date_fin_promotion'])){
            $tab_deb = explode('-',$_POST['form_date_fin_promotion']);
            $h['date_fin_promotion'] = mktime(0,0,0,$tab_deb[1],$tab_deb[2],$tab_deb[0]);
        } else {
            $h['date_fin_promotion'] = 0;
        }

        // Test pour savoir si on ajoute ou on modifie
        if($_POST['id_produit'] > 0){
            // Update de BDD
            $h['date_modification'] = time();
            $id_produit = $_POST['id_produit'];
            $bdd->sql_update('t_produit',$id_produit, $h);
        }else{
            // Ajout en BDD
            $h['date_creation'] = time();
            $h['date_modification'] = time();
            $h['fk_user'] = $_SESSION[SESSION_NAME]['id_user'];
            $id_produit = $bdd->sql_insert('t_produit',$h);
        }

        // Gestion des images
        if(isset($_FILES) && !empty($_FILES)) {
            require 'class/image.class.php';

            $file_array = $_FILES['form_file'];
            foreach ($file_array['tmp_name'] as $key => $tmp_name) {

                // Generation d'un nom unique
                $tab_name = explode('.',$file_array['name'][$key]);
                $unique_name = uniqid('img_').'.'.$tab_name[count($tab_name)-1];

                // Préparation de l'upload
                $uploaddir = 'images/produit/';
                $uploadfile = $uploaddir . $unique_name;

                if (move_uploaded_file($file_array['tmp_name'][$key], $uploadfile)) {
                    // On sauvegarde une image carrée de 300x300
                    $img = new Image($uploadfile);
                    $img->resizeByMin(300);
                    $img->cropSquare();
                    $img->store($uploadfile);

                    // Enregistrement en BDD
                    $h_img = array();
                    $h_img['fk_produit'] = $id_produit;
                    $h_img['nom_fichier'] = $unique_name;
                    $bdd->sql_insert('t_produit_image',$h_img);
                }
           }
       }

        // Gestion Traduction
        $sql = "DELETE FROM t_produit_trad WHERE fk_produit=".$id_produit;
        $bdd->query($sql);

        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach($datas_langue as $data_langue) {
            $h_trad = array();
            $h_trad['fk_produit'] = $id_produit;
            $h_trad['fk_langue'] = $data_langue['id'];
            $h_trad['titre'] = $_POST['form_titre_'.$data_langue['id']];
            $h_trad['description_courte'] = $_POST['form_description_courte_'.$data_langue['id']];
            $h_trad['description_longue'] = $_POST['form_description_longue_'.$data_langue['id']];
            $bdd->sql_insert('t_produit_trad',$h_trad);
        }

        // Gestion des stock
        $bdd->query("DELETE FROM t_produit_stock WHERE fk_produit=".$id_produit);
        $sql = "SELECT * FROM t_stock WHERE isActif=1";
        $datas_stock = $bdd->getData($sql);
        foreach($datas_stock as $data_stock) {
            if($_POST['form_stock_'.$data_stock['id']]) {
                $h = array();
                $h['fk_produit'] = $id_produit;
                $h['fk_stock'] = $data_stock['id'];
                $h['qte'] = $_POST['form_stock_' . $data_stock['id']];
                $bdd->sql_insert('t_produit_stock', $h);
            }
        }

        // Gestion des rayons
        $bdd->query("DELETE FROM t_produit_rayon WHERE fk_produit=".$id_produit);
        $sql = "SELECT * FROM t_rayon WHERE isActif=1";
        $datas_rayon = $bdd->getData($sql);
        foreach($datas_rayon as $data_rayon) {
            if(isset($_POST['form_rayon_'.$data_rayon['id']])) {
                $h = array();
                $h['fk_produit'] = $id_produit;
                $h['fk_rayon'] = $data_rayon['id'];
                $bdd->sql_insert('t_produit_rayon', $h);
            }
        }


        // Redirection
        header('Location: index.php?page=manage_produit&id_produit='.$id_produit);
    }

    // Vérification pour Ajout / Modification
    if (isset($_GET['id_produit']) && !empty($_GET['id_produit'])) {
        // Modification
        $id_produit = $_GET['id_produit'];
        $data_produit = $bdd->build_r_from_id('t_produit',$id_produit);
    }else{
        // On est en Creation
        $id_produit = 0;
        $data_produit = array();
        $data_produit['fk_tva'] = 0;
        $data_produit['fk_promotion'] = 0;
        $data_produit['fk_user'] = $_SESSION[SESSION_NAME]['id_user'];
        $data_produit['prixHT'] = 0;
        $data_produit['poids'] = 0;
        $data_produit['date_creation'] = time();
        $data_produit['date_modification'] = time();
        $data_produit['date_debut_promotion'] = '';
        $data_produit['date_fin_promotion'] = '';
        $data_produit['isActif'] = 1;
    }

    // Mise en forme du formulaire
    $html = '   <div class="form-style">';

    // Gestion du Titre de la page (Modification ou Ajout)
    if($id_produit > 0){
        $html .= '       <h1>Modification Cours<span>Modifier un cours...</span></h1>';
    }else{
        $html .= '       <h1>Ajout Cours<span>Ajouter un Cours...</span></h1>';
    }

    // Debut du Formulaire HTML
    $html.= '       <form method="POST" action="index.php?page=manage_produit" enctype="multipart/form-data">';


    // 1 -- x Information Produit sur la table traduction (t_produit_traduction)
    $sql = "SELECT * FROM t_langue";
    $datas_langue = $bdd->getData($sql);
    $i = 0;
    $first = true;
    foreach($datas_langue as $data_langue) {
        $titre_trad = $bdd->squery("SELECT titre FROM t_produit_trad WHERE fk_produit=".$id_produit." AND fk_langue=".$data_langue['id']);
        $description_courte_trad = $bdd->squery("SELECT description_courte FROM t_produit_trad WHERE fk_produit=".$id_produit." AND fk_langue=".$data_langue['id']);
        $description_longue_trad = $bdd->squery("SELECT description_longue FROM t_produit_trad WHERE fk_produit=".$id_produit." AND fk_langue=".$data_langue['id']);

        if($first) {
            $first = false;
            $html.= '           <div class="section"><span>'.++$i.'</span>Traductions '.$data_langue['nom'].' et Informations Produit</div>';
            $html .= '           <div class="inner-wrap-l">';
            $html .= '               <label> Titre du Cours <input type="text" name="form_titre_' . $data_langue['id'] . '" value="' . $titre_trad . '" /></label>';
            $html .= '               <label> Description courte <textarea name="form_description_courte_' . $data_langue['id'] . '" rows="5">' . $description_courte_trad . '</textarea></label>';
            $html .= '           </div>';
            // Infos cours
            $html .= '           <div class="inner-wrap-r">';
            $html .= '              <div class="section">Informations Cours</div>';
            $html.= '               <ul>';
            $html.= '                   <li> Date de Création : '.($data_produit['date_creation']?date('d/m/Y H:i',$data_produit['date_creation']):'-').'</li>';
            $html.= '                   <li> Date de Modification : '.($data_produit['date_modification']?date('d/m/Y H:i',$data_produit['date_modification']):'-').'</li>';
            $html.= '                   <li> Auteur : '.$bdd->squery("SELECT CONCAT(prenom, ' ', nom) FROM t_user WHERE id=".$data_produit['fk_user']).'</li>';
            if($data_produit['isActif'])
                $html .= '                  <li>Actif : <input type="checkbox"  name="form_isActif" value="1" checked="checked"/></li>';
            else
                $html .= '                  <li>Actif : <input type="checkbox"  name="form_isActif" value="1" /></li>';
            $html.= '               </ul>';
            $html .= '           </div>';
            $html.= '           <div style="clear:both;"></div>';
            $html .= '           <div class="inner-wrap">';
            $html .= '               <label> Description Longue <textarea name="form_description_longue_' . $data_langue['id'] . '" rows="10">' . $description_longue_trad . '</textarea></label>';
            $html .= '           </div>';
        }else {
            $html.= '           <div class="section"><span>'.++$i.'</span>Traductions '.$data_langue['nom'].'</div>';
            $html .= '           <div class="inner-wrap">';
            $html .= '               <label> Titre du Produit <input type="text" name="form_titre_' . $data_langue['id'] . '" value="' . $titre_trad . '" /></label>';
            $html .= '               <label> Description courte <textarea name="form_description_courte_' . $data_langue['id'] . '" rows="5">' . $description_courte_trad . '</textarea></label>';
            $html .= '               <label> Description Longue <textarea name="form_description_longue_' . $data_langue['id'] . '" rows="10">' . $description_longue_trad . '</textarea></label>';
            $html .= '           </div>';
        }
    }
    $html.= '           <div style="clear:both;"></div>';








    // Gestion des Images du Produit
    $html.= '           <div class="section"><span>'.++$i.'</span>Images du Cours</div>';
    $html.= '           <div class="inner-wrap-l">';
    $html.= '               <label>Ajout image <input type="file" name="form_file[]" multiple/></label>';
    $html.= '           </div>';
    $html.= '           <div class="inner-wrap-r zone_image_produit">';

    // Recuperation des images du produit
    $sql = "SELECT * FROM t_produit_image WHERE fk_produit=".$id_produit;
    $datas_image_produit = $bdd->getData($sql);
    if($datas_image_produit) {
        foreach ($datas_image_produit as $data_image_produit) {
            $html.= '<div class="one_image">';
            $html.= '   <img src="images/produit/'.$data_image_produit['nom_fichier'].'" /><br/>';
            $html.= '   <a onclick="" href="index.php?page=manage_produit&id_produit='.$id_produit.'&delete_img='.$data_image_produit['id'].'"><img src="images/interface/suppr.png" class="suppr_img" /></a>';
            $html.= '</div>';
        }
    }
    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';


    // Gestion des Rayons (Version simple et statique...)
    $html.= '           <div class="section"><span>'.++$i.'</span>Gestion des Rayons</div>';
    $html.= '           <div class="inner-wrap">';

    // Recuperation des rayons (avec traduction)
    $sql = "SELECT ";
    $sql.= " r.id AS id_rayon, ";
    $sql.= " rt.nom AS nom";
    $sql.= " FROM t_rayon r ";
    $sql.= " LEFT JOIN t_rayon_trad rt ON rt.fk_rayon=r.id";
    $sql.= " WHERE ";
    $sql.= " r.isActif=1 ";
    $sql.= " AND rt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
    $sql.= " ORDER by nom ASC ";

    $datas_rayon = $bdd->getData($sql);
    if($datas_rayon) {
        $html.= '<table style="width: 400px;">';

        foreach ($datas_rayon as $data_rayon) {
            // Pour chaque stock on, affiche un champs input type text avec l'attribut name = form_stock_ID_STOCK
            $gotRayon = $bdd->squery('SELECT 1 FROM t_produit_rayon WHERE fk_produit='.$id_produit.' AND fk_rayon='.$data_rayon['id_rayon']);
            $html.= '    <tr>';
            if($gotRayon > 0) {
                $html .= '               <td><label for="form_rayon_' . $data_rayon['id_rayon'] . '"> ' . $data_rayon['nom'] . '</label></td>';
                $html .= '               <td><input type="checkbox" value="1" checked="checked" name="form_rayon_' . $data_rayon['id_rayon'] . '" id="form_rayon_' . $data_rayon['id_rayon'] . '" /></td>';
            } else {
                $html .= '               <td><label for="form_rayon_' . $data_rayon['id_rayon'] . '"> ' . $data_rayon['nom'] . '</label></td>';
                $html .= '               <td><input type="checkbox" value="1" name="form_rayon_' . $data_rayon['id_rayon'] . '" id="form_rayon_' . $data_rayon['id_rayon'] . '" /></td>';
            }
            $html.= '    </tr>';
        }
        $html.= '</table>';
    }
    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';


    // Bouton Enregistrer
    $html.= '           <div class="button-section">';
    $html.= '               <input type="submit" value="Enregistrer" />';
    $html.= '           </div>';

    // Champs caché...
    $html.= '           <input type="hidden" name="id_produit" id="id_produit" value="'.$id_produit.'" />';

    $html.= '       </form>';
    $html.= '   </div>';
?>
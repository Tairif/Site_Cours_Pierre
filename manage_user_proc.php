<?php
    $bdd = new Data();

    if(isset($_POST) && !empty($_POST)){
        // On revient d'un formulaire

        // Préparation des informations récuperées du formulaire
        $h = array();
        $h['nom'] = $_POST['form_nom'];
        $h['prenom'] = $_POST['form_prenom'];
        $h['adresse_1'] = $_POST['form_adresse_1'];
        $h['cp'] = $_POST['form_cp'];
        $h['fk_ville'] = $_POST['form_ville'];
        $h['fk_pays'] = $_POST['form_pays'];
        $h['fk_langue'] = $_POST['form_langue'];
        $h['login'] = $_POST['form_login'];
        if(!empty($_POST['form_password']))
            $h['password'] = md5($_POST['form_password']);

        // Gestion de l'avatar
        if(isset($_FILES) && !empty($_FILES) && !empty($_FILES['my_file']['name'])){

            // Generation d'un nom unique
            $tab_name = explode('.',$_FILES['my_file']['name']);
            $unique_name = uniqid('img_').'.'.$tab_name[count($tab_name)-1];

            // Préparation de l'upload
            $uploaddir = 'images/avatar/';
            $uploadfile = $uploaddir . $unique_name;
            if (move_uploaded_file($_FILES['my_file']['tmp_name'], $uploadfile)) {
                $h['avatar'] = $unique_name;
            }
        }

        // Test pour savoir si on ajoute ou on modifie
        if($_POST['id_user'] > 0){
            // Update de BDD
            $id_user = $_POST['id_user'];
            $bdd->sql_update('t_user',$id_user, $h);
        }else{
            // Ajout en BDD
            $id_user = $bdd->sql_insert('t_user',$h);
        }

        // Redirection
        header('Location: index.php');
    }

    // Vérification pour Ajout / Modification
    if (userConnected()) {
        // Modification
        $id_user = $_SESSION[SESSION_NAME]['id_user'];
        $data_user = $bdd->build_r_from_id('t_user',$id_user);
    }else{
        // On est en Creation
        $id_user = 0;
        $data_user = array();
        $data_user['nom'] = '';
        $data_user['prenom'] = '';
        $data_user['login'] = '';
        $data_user['password'] = '';
        $data_user['avatar'] = '';
        $data_user['adresse_1'] = '';
        $data_user['cp'] = '';
        $data_user['fk_ville'] = 0;
        $data_user['fk_pays'] = 0;
        $data_user['fk_langue'] = 0;
    }


    // Mise en forme du formulaire
    $html = '   <div class="form-style">';

    // Gestion du Titre de la page (Modification ou Ajout)
    if($id_user > 0){
        $html.= '        <h1>';
        $html.= '            <div class="list_cmd">';
        $html.= '                <a href="index.php?page=fo_commande">';
        $html.= '                    <img src="images/interface/commande.png"/>';
        $html.= '                </a>';
        $html.= '            </div>';
        $html.= '            Mon Profil<span>Modifier mes informations personnelles...</span>';
        $html.= '        </h1>';
    }else{
        $html .= '       <h1>Inscrivez vous !<span>Saisir vos informations personnelles...</span></h1>';
    }

    // Debut du Formulaire HTML
    $html.= '       <form method="POST" action="index.php?page=fo_user" enctype="multipart/form-data">';

    // Nom et Prénom
    $html.= '           <div class="section"><span>1</span>Nom et Prénom</div>';
    $html.= '           <div class="inner-wrap-l">';
    $html.= '               <label>Nom <input type="text" name="form_nom" value="'.$data_user['nom'].'"/></label>';
    $html.= '           </div>';
    $html.= '           <div class="inner-wrap-r">';
    $html.= '               <label>Prénom <input type="text" name="form_prenom" value="'.$data_user['prenom'].'"/></label>';
    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';

    // Information Connexion
    $html.= '           <div class="section"><span>2</span>Informations connexion</div>';
    $html.= '           <div class="inner-wrap-l">';
    $html.= '               <label>Login <input type="text" name="form_login" value="'.$data_user['login'].'"/></label>';
    $html.= '           </div>';
    $html.= '           <div class="inner-wrap-r">';
    $html.= '               <label>Mot de passe <input type="password" name="form_password" value=""/></label>';
    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';

    // Adresse
    $html.= '           <div class="section"><span>3</span>Adresse</div>';
    $html.= '           <div class="inner-wrap-l">';
    $html.= '               <label>Adresse <textarea name="form_adresse_1" rows="8">'.$data_user['adresse_1'].'</textarea></label>';
    $html.= '           </div>';
    $html.= '           <div class="inner-wrap-r">';
    $html.= '               <label>Code Postal <input type="text" pattern="[0-9]{5}" name="form_cp" value="'.$data_user['cp'].'"/></label>';
    $html.= '               <label>Ville';
    $sql_ville = "SELECT * FROM t_ville ORDER BY nom ASC";
    $datas_ville = $bdd->getData($sql_ville);
    $html.= '                   <select name="form_ville" id="form_ville">';
    $html.= '                       <option value="0">Sélection Ville</option>';
    foreach($datas_ville as $data_ville){
        $html.= '                   <option value="'.$data_ville['id'].'" '.(($data_user['fk_ville'] == $data_ville['id'])?' selected ':'').'>'.$data_ville['nom'].'</option>';
    }

    $html.= '                   </select>';
    $html.= '               </label>';

    // Pays
    $html.= '               <label>Pays';
    $sql_pays = "SELECT * FROM t_pays ORDER BY nom ASC";
    $datas_pays = $bdd->getData($sql_pays);
    $html.= '                   <select name="form_pays" id="form_pays">';
    $html.= '                       <option value="0">Sélection Pays</option>';
    foreach($datas_pays as $data_pays){
        $html.= '                   <option value="'.$data_pays['id'].'" '.(($data_user['fk_pays'] == $data_pays['id'])?' selected ':'').'>'.$data_pays['nom'].'</option>';
    }
    $html.= '                   </select>';
    $html.= '               </label>';

    // Langue
    $html.= '               <label>Langue';
    $sql_langue = "SELECT * FROM t_langue ORDER BY nom ASC";
    $datas_langue = $bdd->getData($sql_langue);
    $html.= '                   <select name="form_langue" id="form_langue">';
    $html.= '                       <option value="0">Sélection Langue</option>';
    foreach($datas_langue as $data_langue){
        $html.= '                   <option value="'.$data_langue['id'].'" '.(($data_user['fk_langue'] == $data_langue['id'])?' selected ':'').'>'.$data_langue['nom'].'</option>';
    }
    $html.= '                   </select>';
    $html.= '               </label>';


    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';

    // Avatar
    $html.= '           <div class="section"><span>4</span>Avatar</div>';
    $html.= '           <div class="inner-wrap">';
    $html.= '               <label>Avatar <input type="file" name="my_file"/>';
    if(is_file('images/avatar/'.$data_user['avatar'])){
        $html.= '               <div class="avatar"><img src="images/avatar/'.$data_user['avatar'].'" /></div>';
        $html.= '               <div style="clear:both;"></div>';
    }
    $html.= '               </label>';
    $html.= '           </div>';

    $html.= '           <div style="clear:both;"></div>';

    // Bouton Enregistrer
    $html.= '           <div class="button-section">';
    $html.= '               <input type="submit" value="Enregistrer" />';
    $html.= '           </div>';

    // Champs caché...
    $html.= '           <input type="hidden" name="id_user" id="id_user" value="'.$id_user.'" />';

    $html.= '       </form>';
    $html.= '   </div>';
?>
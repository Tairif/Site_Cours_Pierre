<?php
    $bdd = new Data();

    if(isset($_POST) && !empty($_POST)){
        // On revient d'un formulaire

        // Préparation des informations récuperées du formulaire
        $h = array();
        $h['url'] = $_POST['form_url'];
        $h['ordre'] = $_POST['form_ordre'];
        $h['fk_parent'] = $_POST['form_fk_parent'];

        // Test pour savoir si on ajoute ou on modifie
        if($_POST['id_menu'] > 0){
            // Update de BDD
            $id_menu = $_POST['id_menu'];
            $bdd->sql_update('t_menu',$id_menu, $h);
        }else{
            // Ajout en BDD
            $id_menu = $bdd->sql_insert('t_menu',$h);
        }

        // Gestion Traduction
        $sql = "DELETE FROM t_menu_trad WHERE fk_menu=".$id_menu;
        $bdd->query($sql);

        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach($datas_langue as $data_langue) {
            $h = array();
            $h['fk_menu'] = $id_menu;
            $h['fk_langue'] = $data_langue['id'];
            $h['libelle'] = $_POST['form_libelle_'.$data_langue['id']];
            $bdd->sql_insert('t_menu_trad',$h);
        }

        // Redirection
        header('Location: index.php?page=manage_menu&id_menu='.$id_menu);
    }

    // Vérification pour Ajout / Modification
    if (isset($_GET['id_menu']) && !empty($_GET['id_menu'])) {
        // Modification
        $id_menu = $_GET['id_menu'];
        $data_menu = $bdd->build_r_from_id('t_menu',$id_menu);
    }else{
        // On est en Creation
        $id_menu = 0;
        $data_menu = array();
        $data_menu['url'] = '';
        $data_menu['ordre'] = '';
        $data_menu['fk_parent'] = 0;
    }

    // Vérification si sous menu
    $nb_sous_menu = $bdd->squery("SELECT COUNT(id) FROM t_menu WHERE fk_parent=".$id_menu);
    if(!$id_menu) $nb_sous_menu = 0;

    // Mise en forme du formulaire
    $html = '   <div class="form-style">';

    // Gestion du Titre de la page (Modification ou Ajout)
    if($id_menu > 0){
        $html .= '       <h1>Modification Menu<span>Modifier un Menu...</span></h1>';
    }else{
        $html .= '       <h1>Ajout Menu<span>Ajouter un Menu...</span></h1>';
    }

    // Debut du Formulaire HTML
    $html.= '       <form method="POST" action="index.php?page=manage_menu" enctype="multipart/form-data">';

    // url et libellé
    $html.= '           <div class="section"><span>1</span>Nom et Prénom</div>';
    $html.= '           <div class="inner-wrap-l">';
    $html.= '               <label>URL <input type="text" name="form_url" value="'.$data_menu['url'].'"/></label>';
    $html.= '           </div>';
    $html.= '           <div class="inner-wrap-r">';

    $sql = "SELECT * FROM t_langue";
    $datas_langue = $bdd->getData($sql);
    foreach($datas_langue as $data_langue) {
        $sql = "SELECT libelle FROM t_menu_trad WHERE fk_menu=".$id_menu." AND fk_langue=".$data_langue['id'];
        $temp = $bdd->getData($sql);
        if(!$temp)
            $libelle = '';
        else
            $libelle = $temp[0]['libelle'];


        $html.= '               <label>Libellé ('.$data_langue['nom'].') <input type="text" name="form_libelle_'.$data_langue['id'].'" value="'.$libelle.'"/></label>';
    }
    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';

    // Ordre et Parent
    $html.= '           <div class="section"><span>2</span>Ordre et Menu Parent</div>';
    $html.= '           <div class="inner-wrap-l">';
//    $html.= '               <label>Ordre <input type="checkbox" name="form_ordre" value="'.$data_menu['ordre'].'"/></label>';
    $html.= '               <label>Ordre <input type="number" name="form_ordre" value="'.$data_menu['ordre'].'"/></label>';
    $html.= '           </div>';
    $html.= '           <div class="inner-wrap-r">';
    $html.= '               <label>Menu Parent';
    if($nb_sous_menu) {
        $html.= ' Impossible car déjà des enfants...';
        $html.= '<input type="hidden" name="form_fk_parent" value="0" />';
    } else {
        $html .= '                   <select name="form_fk_parent">';
        $html .= '                       <option value="0">-- Menu Parent ? --</option>';

        // Liste des parents potentiels...
        $sql = "SELECT m.id AS id, mt.libelle AS libelle ";
        $sql .= " FROM t_menu m ";
        $sql .= " LEFT JOIN t_menu_trad mt ON mt.fk_menu=m.id";
        $sql .= " WHERE mt.fk_langue=1 AND m.fk_parent=0 AND m.id<>" . $id_menu;

        $list_menu = $bdd->getData($sql);
        foreach ($list_menu as $menu) {
            $html .= '                       <option value="' . $menu['id'] . '" ';
            if($menu['id'] == $data_menu['fk_parent'])
                $html.= ' selected="selected" ';
            $html .= '>' . $menu['libelle'] . '</option>';
        }
        $html .= '                   </select>';
    }
    $html.= '               </label>';
    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';

    // Listing du sous menu eventuellement
    if($nb_sous_menu) {
        $html.= '           <div class="section"><span>3</span>Listing du sous-menu</div>';

        $sql = 'SELECT';
        $sql.= ' m.id AS id_menu,';
        $sql.= ' m.url AS url,';
        $sql.= ' mt.libelle AS libelle_menu ';
        $sql.= ' FROM t_menu m';
        $sql.= ' LEFT JOIN t_menu_trad mt ON mt.fk_menu = m.id';
        $sql.= ' WHERE';
        $sql.= ' m.fk_parent = '.$id_menu;
        $sql.= ' AND';
        $sql.= ' mt.fk_langue = 1;';

        $datas_sous_menu = $bdd->getData($sql);
        $html .= '            <table style="width:80%;margin:auto;padding:20px;" cellspacing="0" cellpadding="0">';
        $html .= '                <tr class="tab_header">';
        $html .= '                    <td class="tab_td">ID</td>';
        $html .= '                    <td class="tab_td">Url</td>';
        $html .= '                    <td class="tab_td">Libellé</td>';
        $html .= '                    <td class="tab_td" style="width:100px;">&nbsp;</td>';
        $html .= '                </tr>';

        // Si je suis ici => Tout va bien ! la requete est correcte et il y a au moins un enregistrement
        // Etape 3 : Je parcours les enregistrements de ma requete
        $i = 0;
        foreach($datas_sous_menu as $data){
            if ($i % 2)
                $html .= '       <tr class="tab_row_1">';
            else
                $html .= '       <tr class="tab_row_2">';
            $html.= '            <td class="tab_td">'.$data['id_menu'].'</td>';
            $html.= '            <td class="tab_td">'.$data['url'].'</td>';
            $html.= '            <td class="tab_td">'.$data['libelle_menu'].'</td>';

            // Actions
            $html.= '            <td class="tab_td">';
            $html.= '                <a href="index.php?page=manage_menu&id_menu='.$data['id_menu'].'"><img src="images/interface/edit.png"></a>';
            $html.= '                <a onclick="if(window.confirm(\'Etes vous sur ?\')){ return true; }else{ return false;}" href="index.php?page=listing_menu&delete_id='.$data['id_menu'].'" > <img src="images/interface/suppr.png"> </a>';
            $html.= '            </td>';
            $html.= '        </tr>';
            $i++;
        }
        $html.= '        </table>';
        $html.= '        <div style="clear:both;"></div>';
    }
    // Bouton Enregistrer
    $html.= '           <div class="button-section">';
    $html.= '               <input type="submit" value="Enregistrer" />';
    $html.= '           </div>';

    // Champs caché...
    $html.= '           <input type="hidden" name="id_menu" id="id_menu" value="'.$id_menu.'" />';

    $html.= '       </form>';
    $html.= '   </div>';
?>
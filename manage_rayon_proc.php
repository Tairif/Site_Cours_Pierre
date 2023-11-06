<?php
    $bdd = new Data();

    if(isset($_POST) && !empty($_POST)){
        // On revient d'un formulaire

        // Préparation des informations récuperées du formulaire
        $h = array();
        $h['isActif'] = (isset($_POST['form_isActif'])?1:0);

        // Test pour savoir si on ajoute ou on modifie
        if($_POST['id_rayon'] > 0){
            // Update de BDD
            $id_rayon = $_POST['id_rayon'];
            $bdd->sql_update('t_rayon',$id_rayon, $h);
        }else{
            // Ajout en BDD
            $id_rayon = $bdd->sql_insert('t_rayon',$h);
        }

        // Gestion Traduction
        $sql = "DELETE FROM t_rayon_trad WHERE fk_rayon=".$id_rayon;
        $bdd->query($sql);

        $sql = "SELECT * FROM t_langue";
        $datas_langue = $bdd->getData($sql);
        foreach($datas_langue as $data_langue) {
            $h = array();
            $h['fk_rayon'] = $id_rayon;
            $h['fk_langue'] = $data_langue['id'];
            $h['nom'] = $_POST['form_nom_'.$data_langue['id']];
            $bdd->sql_insert('t_rayon_trad',$h);
        }

        // Redirection
        header('Location: index.php?page=manage_rayon&id_rayon='.$id_rayon);
    }

    // Vérification pour Ajout / Modification
    if (isset($_GET['id_rayon']) && !empty($_GET['id_rayon'])) {
        // Modification
        $id_rayon = $_GET['id_rayon'];
        $data_rayon = $bdd->build_r_from_id('t_rayon',$id_rayon);
    }else{
        // On est en Creation
        $id_rayon = 0;
        $data_rayon = array();
        $data_rayon['isActif'] = 1;
    }

    // Mise en forme du formulaire
    $html = '   <div class="form-style">';

    // Gestion du Titre de la page (Modification ou Ajout)
    if($id_rayon > 0){
        $html .= '       <h1>Modification Tag<span>Modifier un tag...</span></h1>';
    }else{
        $html .= '       <h1>Ajout tag<span>Ajouter un tag...</span></h1>';
    }

    // Debut du Formulaire HTML
    $html.= '       <form method="POST" action="index.php?page=manage_rayon" enctype="multipart/form-data">';

    // url et libellé
    $html.= '           <div class="section"><span>1</span>Information Rayon</div>';
    $html.= '           <div class="inner-wrap-l">';
    $sql = "SELECT * FROM t_langue";
    $datas_langue = $bdd->getData($sql);
    foreach($datas_langue as $data_langue) {
        $sql = "SELECT nom FROM t_rayon_trad WHERE fk_rayon=".$id_rayon." AND fk_langue=".$data_langue['id'];
        $trad = $bdd->squery($sql);
        $html.= '               <label>Nom du rayon ('.$data_langue['nom'].') <input type="text" name="form_nom_'.$data_langue['id'].'" value="'.$trad.'"/></label>';
    }


    $html.= '           </div>';
    $html.= '           <div class="inner-wrap-r">';
    if($data_rayon['isActif'])
        $html .= '               <label>Actif ? <input type="checkbox"  name="form_isActif" value="1" checked="checked"/></label>';
    else
        $html .= '               <label>Actif ? <input type="checkbox"  name="form_isActif" value="1" /></label>';

    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';

    // Bouton Enregistrer
    $html.= '           <div class="button-section">';
    $html.= '               <input type="submit" value="Enregistrer" />';
    $html.= '           </div>';

    // Champs caché...
    $html.= '           <input type="hidden" name="id_rayon" id="id_rayon" value="'.$id_rayon.'" />';

    $html.= '       </form>';
    $html.= '   </div>';
?>
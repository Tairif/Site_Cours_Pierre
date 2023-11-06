<?php
    $bdd = new Data();

    if(isset($_POST) && !empty($_POST)){
        // On revient d'un formulaire

        // Préparation des informations récuperées du formulaire
        $h = array();
        $h['nom'] = $_POST['form_nom'];

        // Test pour savoir si on ajoute ou on modifie
        if($_POST['id_ville'] > 0){
            // Update de BDD
            $id_ville = $_POST['id_ville'];
            $bdd->sql_update('t_ville',$id_ville, $h);
        }else{
            // Ajout en BDD
            $id_ville = $bdd->sql_insert('t_ville',$h);
        }

        // Redirection
        header('Location: index.php?page=manage_ville&id_ville='.$id_ville);
    }

    // Vérification pour Ajout / Modification
    if (isset($_GET['id_ville']) && !empty($_GET['id_ville'])) {
        // Modification
        $id_ville = $_GET['id_ville'];
        $data_ville = $bdd->build_r_from_id('t_ville',$id_ville);
    }else{
        // On est en Creation
        $id_ville = 0;
        $data_ville = array();
        $data_ville['nom'] = '';
    }


    // Mise en forme du formulaire
    $html = '   <div class="form-style">';

    // Gestion du Titre de la page (Modification ou Ajout)
    if($id_ville > 0){
        $html .= '       <h1>Modification Ville<span>Modifier une ville...</span></h1>';
    }else{
        $html .= '       <h1>Ajout Ville<span>Ajouter une ville...</span></h1>';
    }

    // Debut du Formulaire HTML
    $html.= '       <form method="POST" action="index.php?page=manage_ville" enctype="multipart/form-data">';

    // Nom et Prénom
    $html.= '           <div class="section"><span>1</span>Nom</div>';
    $html.= '           <div class="inner-wrap">';
    $html.= '               <label>Nom <input type="text" name="form_nom" value="'.$data_ville['nom'].'"/></label>';
    $html.= '           </div>';
    $html.= '           <div style="clear:both;"></div>';

    // Bouton Enregistrer
    $html.= '           <div class="button-section">';
    $html.= '               <input type="submit" value="Enregistrer" />';
    $html.= '           </div>';

    // Champs caché...
    $html.= '           <input type="hidden" name="id_ville" id="id_ville" value="'.$id_ville.'" />';

    $html.= '       </form>';
    $html.= '   </div>';
?>
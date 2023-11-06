<?php
    $bdd = new Data();

    // Suppression ToDo :) ?
    if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
        // Suppression d'un pays'

        $id_ville = $_GET['delete_id'];
        $bdd->sql_delete('t_ville',$id_ville);

        // Redirection vers le listing des utilisateurs
        header("location: index.php?page=listing_ville");
    }

    // Etape 1 : Préparation de la requete
    $sql = 'SELECT';
    $sql.= ' id,';
    $sql.= ' nom ';
    $sql.= ' FROM t_ville;';

    // Etape 2 : Execution de la requete sur le serveur de BDD
    $datas_ville = $bdd->getData($sql);


    // Préparation du retour
    $html  = '   <div class="form-style">';
    $html .= '       <h1>Listing Ville<span>Listing des Villes...</span></h1>';

    // Lien Ajout utilisateur
    $html .= '   <div class="new_ville">';
    $html .= '       <a href="index.php?page=manage_ville">';
    $html .= '           <img src="images/interface/add_ville.png" />';
    $html .= '       </a>';
    $html .= '   </div>';

    // Première ligne du tableau
    $html .= '        <table style="width:80%;margin:auto;padding:20px;" cellspacing="0" cellpadding="0">';
    $html .= '              <tr class="tab_header">';
    $html .= '                  <td class="tab_td">ID</td>';
    $html .= '                  <td class="tab_td">Nom</td>';
    $html .= '                  <td class="tab_td" style="width:100px;">&nbsp;</td>';
    $html .= '              </tr>';

    // Si je suis ici => Tout va bien ! la requete est correcte et il y a au moins un enregistrement
    // Etape 3 : Je parcours les enregistrements de ma requete
    $i = 0;
    foreach($datas_ville as $data){
        if ($i % 2)
            $html .= '       <tr class="tab_row_1">';
        else
            $html .= '       <tr class="tab_row_2">';
        $html.= '            <td class="tab_td">'.$data['id'].'</td>';
        $html.= '            <td class="tab_td">'.$data['nom'].'</td>';

        // Actions
        $html.= '            <td class="tab_td">';
        $html.= '                <a href="index.php?page=manage_ville&id_ville='.$data['id'].'"><img src="images/interface/edit.png"></a>';
        $html.= '                <a onclick="if(window.confirm(\'Etes vous sur ?\')){ return true; }else{ return false;}" href="index.php?page=listing_ville&delete_id='.$data['id'].'" > <img src="images/interface/suppr.png"> </a>';
        $html.= '            </td>';
        $html.= '        </tr>';
        $i++;
    }
    $html.= '        </table>';
    $html.= '   </div>';
?>
<?php
    $bdd = new Data();

    // Suppression ToDo :) ?
    if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
        $id_rayon = $_GET['delete_id'];

        // Suppression de la traduction
        $bdd->query("DELETE FROM t_rayon_trad WHERE fk_rayon=".$id_rayon);

        // Suppression du rayon
        $bdd->sql_delete('t_rayon',$id_rayon);

        // Redirection vers le listing des rayon
        header("location: index.php?page=listing_rayon");
    }

    // Etape 1 : Préparation de la requete
    $sql = 'SELECT';
    $sql.= '    r.id AS id_rayon,';
    $sql.= '    rt.nom AS nom,';
    $sql.= '    r.isActif AS isActif ';
    $sql.= ' FROM t_rayon r';
    $sql.= ' LEFT JOIN t_rayon_trad rt ON rt.fk_rayon = r.id';
    $sql.= ' WHERE';
    $sql.= '    rt.fk_langue = '.$_SESSION[SESSION_NAME]['id_langue'];

    // Etape 2 : Execution de la requete sur le serveur de BDD
    $datas_rayon = $bdd->getData($sql);

    // Préparation du retour
    $html  = '   <div class="form-style">';
    $html .= '       <h1>Listing Tags<span>Listing des Tags...</span></h1>';

    // Lien Ajout utilisateur
    $html .= '   <div class="new_rayon">';
    $html .= '       <a href="index.php?page=manage_rayon">';
    $html .= '           <img src="images/interface/add.png" />';
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
    if($datas_rayon) {
        foreach ($datas_rayon as $data) {
            if ($i % 2)
                $html .= '       <tr class="tab_row_1">';
            else
                $html .= '       <tr class="tab_row_2">';
            $html .= '            <td class="tab_td">' . $data['id_rayon'] . '</td>';
            $html .= '            <td class="tab_td">' . $data['nom'] . '</td>';
           
            // Actions
            $html .= '            <td class="tab_td">';
            $html .= '                <a href="index.php?page=manage_rayon&id_rayon=' . $data['id_rayon'] . '"><img src="images/interface/edit.png"></a>';
            $html .= '                <a onclick="if(window.confirm(\'Etes vous sur ?\')){ return true; }else{ return false;}" href="index.php?page=listing_rayon&delete_id=' . $data['id_rayon'] . '" > <img src="images/interface/suppr.png"> </a>';
            $html .= '            </td>';
            $html .= '        </tr>';
            $i++;
        }
    }
    $html.= '        </table>';
    $html.= '   </div>';
?>
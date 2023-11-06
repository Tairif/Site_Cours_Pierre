<?php
    $bdd = new Data();

    // Suppression ToDo :) ?
    if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
        // Suppression de l'utilisateur

        $id_menu = $_GET['delete_id'];
        $bdd->sql_delete('t_menu',$id_menu);

        // Redirection vers le listing des utilisateurs
        header("location: index.php?page=listing_menu");
    }

    // Etape 1 : Préparation de la requete
    $sql = 'SELECT';
    $sql.= '    m.id AS id_menu,';
    $sql.= '    m.url AS url,';
    $sql.= '    mt.libelle AS libelle_menu, ';
    $sql.= '    (SELECT COUNT(id) FROM t_menu WHERE fk_parent=m.id) AS nb_ss_menu';
    $sql.= ' FROM t_menu m';
    $sql.= ' LEFT JOIN t_menu_trad mt ON mt.fk_menu = m.id';
    $sql.= ' WHERE';
    $sql.= '    m.fk_parent = 0';
    $sql.= '    AND';
    $sql.= '    mt.fk_langue = '.$_SESSION[SESSION_NAME]['id_langue'];
    $sql.= ' ORDER BY m.ordre ASC';

    // Etape 2 : Execution de la requete sur le serveur de BDD
    $datas_menu = $bdd->getData($sql);


    // Préparation du retour
    $html  = '   <div class="form-style">';
    $html .= '       <h1>Listing Menu<span>Listing du Menu dans le site...</span></h1>';

    // Lien Ajout utilisateur
    $html .= '   <div class="new_menu">';
    $html .= '       <a href="index.php?page=manage_menu">';
    $html .= '           <img src="images/interface/add_menu.png" />';
    $html .= '       </a>';
    $html .= '   </div>';

    // Première ligne du tableau
    $html .= '        <table style="width:80%;margin:auto;padding:20px;" cellspacing="0" cellpadding="0">';
    $html .= '              <tr class="tab_header">';
    $html .= '                  <td class="tab_td">ID</td>';
    $html .= '                  <td class="tab_td">Url</td>';
    $html .= '                  <td class="tab_td">Libellé</td>';
    $html .= '                  <td class="tab_td">Nombre Sous Menu</td>';
    $html .= '                  <td class="tab_td" style="width:100px;">&nbsp;</td>';
    $html .= '              </tr>';

    // Si je suis ici => Tout va bien ! la requete est correcte et il y a au moins un enregistrement
    // Etape 3 : Je parcours les enregistrements de ma requete
    $i = 0;
    foreach($datas_menu as $data){
        if ($i % 2)
            $html .= '       <tr class="tab_row_1">';
        else
            $html .= '       <tr class="tab_row_2">';
        $html.= '            <td class="tab_td">'.$data['id_menu'].'</td>';
        $html.= '            <td class="tab_td">'.$data['url'].'</td>';
        $html.= '            <td class="tab_td">'.$data['libelle_menu'].'</td>';
        $html.= '            <td class="tab_td">'.$data['nb_ss_menu'].'</td>';

        // Actions
        $html.= '            <td class="tab_td">';
        $html.= '                <a href="index.php?page=manage_menu&id_menu='.$data['id_menu'].'"><img src="images/interface/edit.png"></a>';
        $html.= '                <a onclick="if(window.confirm(\'Etes vous sur ?\')){ return true; }else{ return false;}" href="index.php?page=listing_menu&delete_id='.$data['id_menu'].'" > <img src="images/interface/suppr.png"> </a>';
        $html.= '            </td>';
        $html.= '        </tr>';
        $i++;
    }
    $html.= '        </table>';
    $html.= '   </div>';
?>
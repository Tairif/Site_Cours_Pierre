<?php
    $bdd = new Data();

    // Suppression ToDo :) ?
    if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
        // Suppression des produits
        $id_produit = $_GET['delete_id'];

        // Traduction
        $bdd->query("DELETE FROM t_produit_trad WHERE fk_produit=".$id_produit);

        // imamges
        $sql = "SELECT nom_fichier FROM t_produit_image WHERE fk_produit=".$id_produit;
        $datas_image = $bdd->getData($sql);
        if($datas_image) {
            foreach ($datas_image as $data) {
                @unlink('images/produit/' . $data['nom_fichier']);
            }
        }
        $bdd->query("DELETE FROM t_produit_image WHERE fk_produit=".$id_produit);

        // Stock
        $bdd->query("DELETE FROM t_produit_stock WHERE fk_produit=".$id_produit);

        // Produit
        $bdd->sql_delete('t_produit',$id_menu);

        // Redirection vers le listing des utilisateurs
        header("location: index.php?page=listing_produit");
    }

    // Etape 1 : Préparation de la requete
    $sql = 'SELECT';
    $sql.= '    p.id AS id_produit,';
    $sql.= '    pt.titre AS titre,';
    $sql.= '    (SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=p.id) AS qte,';
    $sql.= '    p.prixHT AS prixHT,';
    $sql.= '    t.nom_tva AS tva,';
    $sql.= '    (p.prixHT + (p.prixHT / 100 * t.value)) AS prixTTC,';
    $sql.= '    p.isActif AS isActif';
    $sql.= ' FROM t_produit p';
    $sql.= ' LEFT JOIN t_produit_trad pt ON pt.fk_produit = p.id';
    $sql.= ' LEFT JOIN t_tva t ON t.id=p.fk_tva';
    $sql.= ' WHERE';
    $sql.= '    pt.fk_langue = '.$_SESSION[SESSION_NAME]['id_langue'];
    $sql.= ' ORDER BY id_produit ASC';

    // Etape 2 : Execution de la requete sur le serveur de BDD
    $datas_produit = $bdd->getData($sql);

    // Préparation du retour
    $html  = '   <div class="form-style">';
    $html .= '       <h1>Listing Produit<span>Listing des produit de la boutique...</span></h1>';

    // Lien Ajout utilisateur
    $html .= '   <div class="new_produit">';
    $html .= '       <a href="index.php?page=manage_produit">';
    $html .= '           <img src="images/interface/add.png" />';
    $html .= '       </a>';
    $html .= '   </div>';

    // Première ligne du tableau
    $html .= '        <table style="width:80%;margin:auto;padding:20px;" cellspacing="0" cellpadding="0">';
    $html .= '              <tr class="tab_header">';
    $html .= '                  <td class="tab_td">ID</td>';
    $html .= '                  <td class="tab_td">Titre</td>';
    $html .= '                  <td class="tab_td" style="width:100px;">&nbsp;</td>';
    $html .= '              </tr>';

    // Si je suis ici => Tout va bien ! la requete est correcte et il y a au moins un enregistrement
    // Etape 3 : Je parcours les enregistrements de ma requete
    $i = 0;
    if($datas_produit) {
        foreach ($datas_produit as $data) {
            if ($i % 2)
                $html .= '       <tr class="tab_row_1">';
            else
                $html .= '       <tr class="tab_row_2">';
            $html .= '            <td class="tab_td">' . $data['id_produit'] . '</td>';
            $html .= '            <td class="tab_td">' . $data['titre'] . '</td>';
            // Actions
            $html .= '            <td class="tab_td">';
            $html .= '                <a href="index.php?page=manage_produit&id_produit=' . $data['id_produit'] . '"><img src="images/interface/edit.png"></a>';
            $html .= '                <a onclick="if(window.confirm(\'Etes vous sur ?\')){ return true; }else{ return false;}" href="index.php?page=listing_produit&delete_id=' . $data['id_produit'] . '" > <img src="images/interface/suppr.png"> </a>';
            $html .= '            </td>';
            $html .= '        </tr>';
            $i++;
        }
    }
    $html.= '        </table>';
    $html.= '   </div>';
?>
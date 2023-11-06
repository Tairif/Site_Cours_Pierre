<?php
    $bdd = new Data();

    // Gestion du Panier
    if(isset($_GET['add_id_produit']) && !empty($_GET['add_id_produit'])) {
        // L'utilisateur a voulou ajouter un produit au panier

        $gotProduct = false;
        $nb_total_produit = $bdd->squery("SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=".$_GET['add_id_produit']);

        foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
            if($_SESSION[SESSION_NAME]['panier'][$key]['id_produit'] == $_GET['add_id_produit']) {
                if($nb_total_produit > $_SESSION[SESSION_NAME]['panier'][$key]['qte']) {
                    $_SESSION[SESSION_NAME]['panier'][$key]['qte'] = $_SESSION[SESSION_NAME]['panier'][$key]['qte'] + 1;
                }
                $gotProduct = true;
            }
        }

        if(!$gotProduct) {
            $data_produit = array(
                'id_produit' => $_GET['add_id_produit'],
                'qte' => 1
            );
            $_SESSION[SESSION_NAME]['panier'][] = $data_produit;
        }

        header('Location: index.php?page=fo_produit&id_produit='.$_GET['id_produit']);
    }

    // Gestion de l'ID du produit
    if(isset($_GET['id_produit']) && !empty($_GET['id_produit'])) {
        $id_produit = $_GET['id_produit'];
    } else {
        header('Location: index.php?page=fo_home');
    }

    $sql  = " SELECT ";
    $sql .= "    p.id AS id_produit, ";
    $sql .= "    pt.titre AS titre, ";
    $sql .= "    pt.description_courte AS description_courte, ";
    $sql .= "    pt.description_longue AS description_longue, ";
    $sql .= "    p.prixHT AS prixHT, ";
    $sql .= "    p.poids AS poids, ";
    $sql .= "    (p.prixHT + (p.prixHT / 100 * t.value)) AS prixTTC, ";
    $sql .= "    pr.reduction AS reduction,";
    $sql .= "    GROUP_CONCAT(DISTINCT(pi.nom_fichier) SEPARATOR '#') AS fichier_image, ";
    $sql .= "    (SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=p.id) AS qte ";
    $sql .= "  FROM ";
    $sql .= "    t_produit p ";
    $sql .= "    LEFT JOIN t_produit_trad pt ON pt.fk_produit=p.id AND pt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
    $sql .= "    LEFT JOIN t_produit_image pi ON pi.fk_produit=p.id ";
    $sql .= "    LEFT JOIN t_promotion pr ON pr.id=p.fk_promotion ";
    $sql .= "    LEFT JOIN t_tva t ON t.id=p.fk_tva ";
    $sql .= "    LEFT JOIN t_produit_rayon pra ON pra.fk_produit=p.id ";
    $sql .= "  WHERE 1 = 1 ";
    $sql .= "  AND p.id=".$id_produit;
    $sql .= "  GROUP BY p.id ";

    $data_produit = $bdd->getData($sql);
    $data_produit = $data_produit[0];

    // préparation de la mise en forme
    $html = '<div class="main_shop">';
    $html .= '    <div class="zone_product">';

    // Gestion des images
    $html .= '        <div class="zone_image_product">';
    $tab_image = explode('#',$data_produit['fichier_image']);
    if(!empty($tab_image)) {
        $first_image = array_shift($tab_image);
        $html .= '<img src="images/produit/'.$first_image.'" class="product_image_big"/>';
        if(!empty($tab_image)) {
            $html .= '<div class="zone_min_image_product">';
            foreach($tab_image as $image) {
                $html.= '<img src="images/produit/'.$image.'" />';
            }
            $html .= '</div>';
        }
    } else {
        // Pas d'image => image par defaut
        $html .= '<img src="images/interface/default_product.png" />';
    }
    $html .= '        </div>';

    // Gestion information produit
    $html .= '        <div class="zone_information_product">';
    $html .= '            <div class="zone_information_product_title">';
    $html .= '                '.$data_produit['titre'];
    $html .= '            </div>';

    $html .= '            <div class="zone_information_product_short_desc">';
    $html .= '                '.$data_produit['description_courte'];
    $html .= '            </div>';
    
    $html .= '            <div class="zone_information_product_addcard">';
    if($data_produit['qte']>0) {
        $html .= '                <div class="product_link_add_cart">';
        $html .= '                    <a href="index.php?page=fo_produit&id_produit=' . $data_produit['id_produit'] . '&add_id_produit=' . $data_produit['id_produit'] . '">';
        $html .= '                        <img src="images/interface/cart.png" /> &nbsp Ajouter au Panier';
        $html .= '                    </a>';
        $html .= '                </div>';
    }
    $html .= '            </div>';
    $html .= '        </div>';
    $html .= '        <div style="clear:both;"></div>';

    // Gestion Description longue
    $html .= '        <div class="zone_description_produit">';
    $html .= '            '.nl2br($data_produit['description_longue']);
    $html .= '        </div>';
    $html .= '    </div>';
    $html .= '</div>';


?>
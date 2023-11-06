<?php
$ajax_action_post = '';
$ajax_action_get = '';
$bdd = new Data();

if(isset($_POST['_ajax_action'])) $ajax_action_post = $_POST['_ajax_action'];
if(isset($_GET['_ajax_action'])) $ajax_action_get = $_GET['_ajax_action'];

$ajax_action = $ajax_action_get;
if($ajax_action_post != '') $ajax_action = $ajax_action_post;

if($ajax_action){
    switch($ajax_action){
        case 'charge_rayon':
            $id_rayon = $_POST['id_rayon'];

            if(!$id_rayon) {
                // reset session sur id_rayon
                unset($_SESSION[SESSION_NAME]['id_rayon']);
            } else {
                $_SESSION[SESSION_NAME]['id_rayon'] = $id_rayon;
            }

            // reset de l'offset
            $_SESSION[SESSION_NAME]['offset'] = 0;

            $sql  = " SELECT ";
            $sql .= "    p.id AS id_produit, ";
            $sql .= "    pt.titre AS titre, ";
            $sql .= "    pt.description_courte AS description, ";
            $sql .= "    (p.prixHT + (p.prixHT / 100 * t.value)) AS prixTTC, ";
            $sql .= "    pr.reduction AS reduction,";
            $sql .= "    GROUP_CONCAT(pi.nom_fichier SEPARATOR '#') AS fichier_image, ";
            $sql .= "    (SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=p.id) AS qte ";
            $sql .= "  FROM ";
            $sql .= "    t_produit p ";
            $sql .= "    LEFT JOIN t_produit_trad pt ON pt.fk_produit=p.id ";
            $sql .= "    LEFT JOIN t_produit_image pi ON pi.fk_produit=p.id ";
            $sql .= "    LEFT JOIN t_promotion pr ON pr.id=p.fk_promotion ";
            $sql .= "    LEFT JOIN t_tva t ON t.id=p.fk_tva ";
            $sql .= "    LEFT JOIN t_produit_rayon pra ON pra.fk_produit=p.id ";
            $sql .= "  WHERE pt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
            if($id_rayon) {
                $sql .= " AND pra.fk_rayon=" . $id_rayon;
            }
            $sql .= "  GROUP BY p.id ";

            // Gestion de la pagination : On affiche le rayon pour la premiere fois => debut pour le limit
            $sql.= " LIMIT 0, ".NB_PRODUIT_PAGE.";";

            $datas_produit = $bdd->getData($sql);

            $html = '';
            // Gestion affichage des vignettes pour les produits
            if($datas_produit) {
                foreach($datas_produit as $data_produit) {
                    $html.= '<div class="one_product">';

                    // Gestion image produit
                    if(!empty($data_produit['fichier_image'])) {
                        // On a une ou plusieurs images.. (au hazard si plusieurs)
                        $tab_image = explode('#', $data_produit['fichier_image']);
                        // Si on veut de l'aléatoire, on peut melanger le tableau avant...
                        // shuffle($tab_image);
                        $image = 'images/produit/' . $tab_image[0];
                    } else {
                        // image par defaut => le produit n'a pas d'image...
                        $image = 'images/interface/default_product.png';
                    }
                    $html.= '   <div class="product_image">';
                    $html.= '       <img src="'.$image.'" alt="'.$data_produit['titre'].'" />';
                    $html.= '   </div>';
                    $html.= '   <div class="product_information">';
                    $html.= '       <div class="product_link">';
                    $html.= '           <a href="index.php?page=fo_produit&id_produit='.$data_produit['id_produit'].'">';
                    $html.= '               Voir le cours';
                    $html.= '           </a>';
                    $html.= '       </div>';
                    $html.= '       <div class="product_link_add_cart" '.(($data_produit['qte']>0)? '' : ' style="visibility: hidden;" ' ).'>';
                    $html.= '           <a onclick="add_product_cart('.$data_produit['id_produit'].')" href="#">';
                    $html.= '               <img src="images/interface/cart.png" />';
                    $html.= '           </a>';
                    $html.= '       </div>';
                    $html.= '       <div class="product_title">';
                    $html.= '           '.$data_produit['titre'];

                    // Lien direct vers edition du produit (uniquement si Administrateur. cf nouveau champs dans la table t_user)
                    if(isset($_SESSION[SESSION_NAME]['isAdmin']) && $_SESSION[SESSION_NAME]['isAdmin'] == 1) {
                        $html.= '<a href="index.php?page=manage_produit&id_produit='.$data_produit['id_produit'].'">';
                        $html.= '    <img src="images/interface/edit.png" />';
                        $html.= '</a>';
                    }

                    $html.= '       </div>';
                    $html.= '       <div class="product_description">';
                    $html.= '           '.substr($data_produit['description'],0,50).'...';
                    $html.= '       </div>';
                    $html.= '   </div>';
                    $html.= '</div>';
                }
            } else {
                // Aucun produit
                $html = '<div class="article_titre">Aucun produit trouvé...</div>';
            }

            // Retour Ajax
            to_ajax('set','listing_shop', $html);

            // Gestion Pagination
            $html = '';
            if(isset($_SESSION[SESSION_NAME]['id_rayon']) && $_SESSION[SESSION_NAME]['id_rayon'] ) {
                $sql = " SELECT COUNT(p.id) ";
                $sql.= " FROM t_produit p ";
                $sql.= " LEFT JOIN t_produit_rayon pr ON pr.fk_produit=p.id ";
                $sql.= " WHERE pr.fk_rayon = ".$_SESSION[SESSION_NAME]['id_rayon'];
            } else {
                $sql = "SELECT COUNT(id) FROM t_produit WHERE isActif=1";
            }
            $nb_produit = $bdd->squery($sql);
            $nb_page = ceil($nb_produit / NB_PRODUIT_PAGE);
            $current_page = $_SESSION[SESSION_NAME]['offset'] + 1;
            $first_page = 0;
            $last_page = $nb_page - 1;

            // On calcul les pages avant la page courante avec limit sur la premiere page
            $prev_pages = range(max($first_page, $current_page - 1 - NB_MAX_PAGINATION), $current_page-1);

            // On calcul les pages apres la page courante avec limit sur la derniere page
            $next_pages = range($current_page + 1, min($last_page, $current_page - 1 + NB_MAX_PAGINATION));

            // On fusionne ces deux tableaux
            $pages_to_show_tmp = array_merge([$first_page], $prev_pages, [$current_page], $next_pages, [$last_page]);

            // On supprime les doublons eventuels
            $pages_to_show_tmp = array_unique($pages_to_show_tmp);

            // On ordonne le tableau pour l'affichage (sens croissant)
            sort($pages_to_show_tmp);

            $pages_to_show = array();
            // Gestion des ... pour les trous dans la pagination
            $last = 0;
            $first = true;
            foreach($pages_to_show_tmp as $page) {
                if($first) {
                    $first = false;
                    $last = $page;
                    $pages_to_show[] = $page;
                } else {
                    if($page == ($last + 1)) {
                        // C'est la suite
                        $pages_to_show[] = $page;
                    } else {
                        // Il y a un trou
                        $pages_to_show[] = '#';
                        $pages_to_show[] = $page;
                    }
                    $last = $page;
                }
            }

            // On affiche les contenu du tableau de pagination
            foreach ($pages_to_show as $page) {
                if($page === '#') {
                    $html .= '    <div class="one_offset_dot">';
                    $html .= '        ....';
                    $html .= '    </div>';
                } else {
                    if ($page <= $last_page) {
                        if ($page == $_SESSION[SESSION_NAME]['offset']) {
                            $html .= '    <div class="one_offset bg_white">' . ($page + 1) . '</div>';
                        } else {
                            $html .= '    <div class="one_offset">';
                            $html .= '        <a href="#" onclick="load_page(' . $page . '); return false;" >' . ($page + 1) . ' </a>';
                            $html .= '    </div>';
                        }
                    }
                }
            }

            to_ajax('set','zone_pagination', $html);
            break;
        case 'add_product_cart':
            $id_produit = $_POST['id_produit'];

            $gotProduct = false;
            $nb_total_produit = $bdd->squery("SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=".$id_produit);

            foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
                if($_SESSION[SESSION_NAME]['panier'][$key]['id_produit'] == $id_produit) {
                    if($nb_total_produit > $_SESSION[SESSION_NAME]['panier'][$key]['qte']) {
                        $_SESSION[SESSION_NAME]['panier'][$key]['qte'] = $_SESSION[SESSION_NAME]['panier'][$key]['qte'] + 1;
                    }
                    $gotProduct = true;
                }
            }

            if(!$gotProduct) {
                $data_produit = array(
                    'id_produit' => $id_produit,
                    'qte' => 1
                );
                $_SESSION[SESSION_NAME]['panier'][] = $data_produit;
            }

            // Préparation du retour
            $nb_item = 0;
            if(isset($_SESSION[SESSION_NAME]['panier']) && count($_SESSION[SESSION_NAME]['panier'])) {
                foreach($_SESSION[SESSION_NAME]['panier'] as $data) {
                    $nb_item += $data['qte'];
                }
                to_ajax('set','nb_product_car','('.$nb_item.')');
            }

            break;
        case 'load_page':
            $offset = $_POST['page'];

            // Sauvegarde en session pour un retour sur la meme page....
            $_SESSION[SESSION_NAME]['offset'] = $offset;

            $sql  = " SELECT ";
            $sql .= "    p.id AS id_produit, ";
            $sql .= "    pt.titre AS titre, ";
            $sql .= "    pt.description_courte AS description, ";
            $sql .= "    (p.prixHT + (p.prixHT / 100 * t.value)) AS prixTTC, ";
            $sql .= "    pr.reduction AS reduction,";
            $sql .= "    GROUP_CONCAT(pi.nom_fichier SEPARATOR '#') AS fichier_image, ";
            $sql .= "    (SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=p.id) AS qte ";
            $sql .= "  FROM ";
            $sql .= "    t_produit p ";
            $sql .= "    LEFT JOIN t_produit_trad pt ON pt.fk_produit=p.id ";
            $sql .= "    LEFT JOIN t_produit_image pi ON pi.fk_produit=p.id ";
            $sql .= "    LEFT JOIN t_promotion pr ON pr.id=p.fk_promotion ";
            $sql .= "    LEFT JOIN t_tva t ON t.id=p.fk_tva ";
            $sql .= "    LEFT JOIN t_produit_rayon pra ON pra.fk_produit=p.id ";
            $sql .= "  WHERE pt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
            if(isset($_SESSION[SESSION_NAME]['id_rayon']) && $_SESSION[SESSION_NAME]['id_rayon']) {
                $sql .= " AND pra.fk_rayon=" . $_SESSION[SESSION_NAME]['id_rayon'];
            }
            $sql .= "  GROUP BY p.id ";

            // Gestion de la pagination
            $sql.= " LIMIT ".($offset * NB_PRODUIT_PAGE).", ".NB_PRODUIT_PAGE.";";

            $datas_produit = $bdd->getData($sql);

            $html = '';
            // Gestion affichage des vignettes pour les produits
            if($datas_produit) {
                foreach($datas_produit as $data_produit) {
                    $html.= '<div class="one_product">';

                    // Gestion image produit
                    if(!empty($data_produit['fichier_image'])) {
                        // On a une ou plusieurs images.. (au hazard si plusieurs)
                        $tab_image = explode('#', $data_produit['fichier_image']);
                        // Si on veut de l'aléatoire, on peut melanger le tableau avant...
                        // shuffle($tab_image);
                        $image = 'images/produit/' . $tab_image[0];
                    } else {
                        // image par defaut => le produit n'a pas d'image...
                        $image = 'images/interface/default_product.png';
                    }
                    $html.= '   <div class="product_image">';
                    $html.= '       <img src="'.$image.'" alt="'.$data_produit['titre'].'" />';
                    $html.= '   </div>';
                    $html.= '   <div class="product_information">';
                    $html.= '       <div class="product_link">';
                    // On dois supprimer le data_produit
                    $html.= '           <a href="index.php?page=fo_produit&id_produit='.$data_produit['id_produit'].'">';
                    $html.= '               Voir le cours';
                    $html.= '           </a>';
                    $html.= '       </div>';
                    $html.= '       <div class="product_link_add_cart" '.(($data_produit['qte']>0)? '' : ' style="visibility: hidden;" ' ).'>';
                    $html.= '           <a onclick="add_product_cart('.$data_produit['id_produit'].')" href="#">';
                    $html.= '               <img src="images/interface/cart.png" />';
                    $html.= '           </a>';
                    $html.= '       </div>';
                    $html.= '       <div class="product_title">';
                    $html.= '           '.$data_produit['titre'];

                    // Lien direct vers edition du produit (uniquement si Administrateur. cf nouveau champs dans la table t_user)
                    if(isset($_SESSION[SESSION_NAME]['isAdmin']) && $_SESSION[SESSION_NAME]['isAdmin'] == 1) {
                        $html.= '<a href="index.php?page=manage_produit&id_produit='.$data_produit['id_produit'].'">';
                        $html.= '    <img src="images/interface/edit.png" />';
                        $html.= '</a>';
                    }

                    $html.= '       </div>';
                    $html.= '       <div class="product_description">';
                    $html.= '           '.substr($data_produit['description'],0,50).'...';
                    $html.= '       </div>';
                    $html.= '   </div>';
                    $html.= '</div>';
                }
            } else {
                // Aucun produit
                $html = '<div class="article_titre">Aucun produit trouvé...</div>';
            }

            // Retour Ajax
            to_ajax('set','listing_shop', $html);

            // Gestion Pagination
            $html = '';
            if(isset($_SESSION[SESSION_NAME]['id_rayon']) && $_SESSION[SESSION_NAME]['id_rayon'] ) {
                $sql = " SELECT COUNT(p.id) ";
                $sql.= " FROM t_produit p ";
                $sql.= " LEFT JOIN t_produit_rayon pr ON pr.fk_produit=p.id ";
                $sql.= " WHERE pr.fk_rayon = ".$_SESSION[SESSION_NAME]['id_rayon'];
            } else {
                $sql = "SELECT COUNT(id) FROM t_produit WHERE isActif=1";
            }
            $nb_produit = $bdd->squery($sql);
            $nb_page = ceil($nb_produit / NB_PRODUIT_PAGE);
            $current_page = $_SESSION[SESSION_NAME]['offset'] + 1;
            $first_page = 0;
            $last_page = $nb_page - 1;

            // On calcul les pages avant la page courante avec limit sur la premiere page
            $prev_pages = range(max($first_page, $current_page - 1 - NB_MAX_PAGINATION), $current_page - 1);

            // On calcul les pages apres la page courante avec limit sur la derniere page
            $next_pages = range($current_page + 1, min($last_page, $current_page - 1 + NB_MAX_PAGINATION));

            // On fusionne ces deux tableaux
            $pages_to_show_tmp = array_merge([$first_page], $prev_pages, [$current_page], $next_pages, [$last_page]);

            // On supprime les doublons eventuels
            $pages_to_show_tmp = array_unique($pages_to_show_tmp);

            // On ordonne le tableau pour l'affichage (sens croissant)
            sort($pages_to_show_tmp);

            $pages_to_show = array();
            // Gestion des ... pour les trous dans la pagination
            $last = 0;
            $first = true;
            foreach($pages_to_show_tmp as $page) {
                if($first) {
                    $first = false;
                    $last = $page;
                    $pages_to_show[] = $page;
                } else {
                    if($page == ($last + 1)) {
                        // C'est la suite
                        $pages_to_show[] = $page;
                    } else {
                        // Il y a un trou
                        $pages_to_show[] = '#';
                        $pages_to_show[] = $page;
                    }
                    $last = $page;
                }
            }

            // On affiche les contenu du tableau de pagination
            foreach ($pages_to_show as $page) {
                if($page === '#') {
                    $html .= '    <div class="one_offset_dot">';
                    $html .= '        ....';
                    $html .= '    </div>';
                } else {
                    if ($page <= $last_page) {
                        if ($page == $_SESSION[SESSION_NAME]['offset']) {
                            $html .= '    <div class="one_offset bg_white">' . ($page + 1) . '</div>';
                        } else {
                            $html .= '    <div class="one_offset">';
                            $html .= '        <a href="#" onclick="load_page(' . $page . '); return false;" >' . ($page + 1) . ' </a>';
                            $html .= '    </div>';
                        }
                    }
                }
            }

            // Retour Ajax pour la pagination
            to_ajax('set','zone_pagination', $html);

            break;
    }
}

?>

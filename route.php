<?php
    $route = array();

    // Ajax
    $route['ajax_home'] = 'mod/fo/home/ajax_home.php';
    $route['ajax_work'] = 'mod/work/ajax_work.php';


    $route['fo_home'] = 'mod/fo/home/home.php';
    $route['fo_produit'] = 'mod/fo/produit/produit.php';
    $route['fo_panier'] = 'mod/fo/panier/panier.php';
    $route['fo_commande'] = 'mod/fo/commande/listing_commande.php';
    $route['fo_manage_commande'] = 'mod/fo/commande/manage_commande.php';
    $route['fo_user'] = 'mod/fo/user/manage_user.php';
    $route['login'] = 'mod/admin/login/login.php';
    $route['logout'] = 'mod/admin/logout/logout.php';
    $route['work'] = 'mod/work/work.php';

    if(userCanAdmin()) {
        $route['home'] = 'mod/admin/home/home.php';
        $route['listing_langue'] = 'mod/admin/langue/listing_langue.php';
        $route['logout'] = 'mod/admin/logout/logout.php';
        $route['listing_commande'] = 'mod/admin/shop/commande/listing_commande.php';
        $route['listing_menu'] = 'mod/admin/menu/listing_menu.php';
        $route['listing_pays'] = 'mod/admin/pays/listing_pays.php';
        $route['listing_photo'] = 'mod/admin/listing_photo/listing_photo.php';
        $route['listing_produit'] = 'mod/admin/shop/produit/listing_produit.php';
        $route['listing_promotion'] = 'mod/admin/shop/promotion/listing_promotion.php';
        $route['listing_rayon'] = 'mod/admin/shop/rayon/listing_rayon.php';
        $route['listing_statut_cmd'] = 'mod/admin/shop/statut_commande/listing_statut_commande.php';
        $route['listing_stock'] = 'mod/admin/shop/stock/listing_stock.php';
        $route['listing_tva'] = 'mod/admin/shop/tva/listing_tva.php';
        $route['listing_user'] = 'mod/admin/user/listing_user.php';
        $route['listing_ville'] = 'mod/admin/ville/listing_ville.php';
        $route['manage_commande'] = 'mod/admin/shop/commande/manage_commande.php';
        $route['manage_langue'] = 'mod/admin/langue/manage_langue.php';
        $route['manage_menu'] = 'mod/admin/menu/manage_menu.php';
        $route['manage_pays'] = 'mod/admin/pays/manage_pays.php';
        $route['manage_produit'] = 'mod/admin/shop/produit/manage_produit.php';
        $route['manage_promotion'] = 'mod/admin/shop/promotion/manage_promotion.php';
        $route['manage_rayon'] = 'mod/admin/shop/rayon/manage_rayon.php';
        $route['manage_statut_cmd'] = 'mod/admin/shop/statut_commande/manage_statut_commande.php';
        $route['manage_stock'] = 'mod/admin/shop/stock/manage_stock.php';
        $route['manage_tva'] = 'mod/admin/shop/tva/manage_tva.php';
        $route['manage_user'] = 'mod/admin/user/manage_user.php';
        $route['manage_ville'] = 'mod/admin/ville/manage_ville.php';
        $route['maze'] = 'mod/admin/maze/maze.php';
        $route['shop'] = 'mod/admin/shop/home_shop/home_shop.php';
    }
?>
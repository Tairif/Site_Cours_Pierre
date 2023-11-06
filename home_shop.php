<?php
    $link = array(
        array(
            'image'=>'shop.png',
            'text'=>'Apercu<br/>Cours',
            'url'=>'index.php?page=fo_home',
        ),
        array(
            'image'=>'produit.png',
            'text'=>'Ajouter<br/>Cours',
            'url'=>'index.php?page=listing_produit',
        ),
        array(
            'image'=>'rayon.png',
            'text'=>'Ajouter<br/>Tags',
            'url'=>'index.php?page=listing_rayon',
        )
    );

    $page = new Page(true, 'Accueil Cours Back Office', $link);
    $page->build_content($html);
    $page->show();
?>
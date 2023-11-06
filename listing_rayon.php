<?php
    $link = array(
        array(
            'image'=>'shop.png',
            'text'=>'Apercu<br/>Cours',
            'url'=>'index.php?page=fo_home',
        ),
        array(
            'image'=>'produit.png',
            'text'=>'Ajouer<br/>Cours',
            'url'=>'index.php?page=listing_produit',
        ),
        array(
            'image'=>'rayon.png',
            'text'=>'Ajouer<br/>Tags',
            'url'=>'index.php?page=listing_rayon',
        )
    );
    $page = new Page(true, 'Listing des Tags', $link);
    $page->build_content($html);
    $page->show();
?>
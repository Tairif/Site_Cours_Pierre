<?php
    $link = array(
        array(
            'image'=>'user.png',
            'text'=>'Listing<br/>Utilisateur',
            'url'=>'index.php?page=listing_user',
        ),
        array(
            'image'=>'ville.png',
            'text'=>'Listing<br/>Ville',
            'url'=>'index.php?page=listing_ville',
        ),
        array(
            'image'=>'pays.png',
            'text'=>'Listing<br/>Pays',
            'url'=>'index.php?page=listing_pays',
        ),
        array(
            'image'=>'pays.png',
            'text'=>'Listing<br/>Langues',
            'url'=>'index.php?page=listing_langue',
        )
    );
    $page = new Page(true, 'Listing des Villes', $link);
    $page->build_content($html);
    $page->show();
?>
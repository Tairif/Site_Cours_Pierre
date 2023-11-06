<?php
    $html_wrap = true;
    require('inc/framework.php');

    // Gestion de la session par defaut
    if(!isset($_SESSION[SESSION_NAME])) {
        $_SESSION[SESSION_NAME]['id_langue'] = DEFAULT_ID_LANGUE;
        $_SESSION[SESSION_NAME]['panier'] = array();
        $_SESSION[SESSION_NAME]['nbProduitPage'] = NB_PRODUIT_PAGE;
    }

    // Gestion des routes !
    if (isset($_GET['page']) && isset($route[$_GET['page']])) {
        // La page demandé existe => on va pouvoir l'afficher !
        $url_php = $route[$_GET['page']];
    } else {
        // Forcer l'affichage de la page d'accueil du Front Office
        $url_php = $route['fo_home'];
    }

    // Gestion de la procedure
    $url_php_proc = str_replace('.php','_proc.php',$url_php);
    if(is_file($url_php_proc)){
        include $url_php_proc;
    }
    if($html_wrap){
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <!-- Meta -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <meta name="mobile-web-app-capable" content="yes">

        <!-- Style CSS Responsive -->
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
        <link rel="stylesheet" type="text/css" href="css/interface.css"/>
        <link rel="stylesheet" type="text/css" href="css/interface_hd.css"/>

        <!-- Inclusion Police TTF -->
        <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>

        <!-- JS -->
        <script type="text/javascript" src="js/ajax_v2.js"></script>
        <?php
            $url_php_head = str_replace('.php','_head.php',$url_php);
            if(is_file($url_php_head)){
                include $url_php_head;
            }else{
                echo "<title>Formation IFR</title>";
            }
        ?>
    </head>

    <?php
        } // Fin du test if($html_wrap){
        include $url_php;
        if($html_wrap){
    ?>
</html>
<?php
            //dbug($_SESSION[SESSION_NAME]['panier']);
        } // Fin du test if($html_wrap){
?>
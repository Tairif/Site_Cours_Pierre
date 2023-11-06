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
        case 'load_message':

            $titre = $_POST['titre'];
            $id_rayon = $_POST['id_rayon'];

            $html='Variables recupérées depuis le javascript : <ul>';
            $html.= '   <li> Titre recu : '.$titre.'</li>';
            $html.= '   <li> id Rayon recu : '.$id_rayon.'</li>';
            $html.= '</ul>';

            to_ajax('set', 'zone_ajax' , $html);

            // Traitement image
            if (isset($_FILES['form_file'])) {
                $file = $_FILES['form_file'];
                to_ajax_dbug($file);
            }
            break;
    }
}

?>

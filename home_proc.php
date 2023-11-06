<?php
$bdd = new Data();

$html = '<div class="main_shop">';
$html.= '   <div class="header_shop">';

// Gestion de l'affichage des rayons pour navigation
$html.= '       <div class="recherche_rayon">';
$html.= '           Nos Rayons : <br/>';

$sql = "SELECT r.id, rt.nom FROM t_rayon r LEFT JOIN t_rayon_trad rt ON rt.fk_rayon=r.id WHERE rt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
$datas_rayon = $bdd->getData($sql);
$link = array();
$link[] = '<a onclick="charge_rayon(0);" href="#">Tous</a>';
if($datas_rayon) {
    foreach ($datas_rayon as $data_rayon) {
        $link[] = '<a onclick="charge_rayon(' . $data_rayon['id'] . ');" href="#">' . $data_rayon['nom'] . '</a>';
    }
}
$html.= '           '.implode(' / ',$link);
$html.= '       </div>';
$html.= '   </div>';

// Preparation de la zone pour le listing (chargé en ajax)
$html.= '   <div class="listing_shop" id="listing_shop"></div>';

// Preparation de la zone pour la pagination (chargé en ajax)
$html.= '   <div class="zone_pagination" id="zone_pagination"></div>';

$html.= '<br/><br/><br/>';

$html.= '</div>';
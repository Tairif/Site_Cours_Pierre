<?php

   $html = '<label> Titre pour le Test : <input type="text" id="form_titre" name="form_titre"/></label><br/>';
   $html .= '<label> Nombre pour le Test : <input type="number" id="form_nombre" name="form_nombre"/></label><br/>';
   $html .= '<label> fichier pour le Test : <input type="file" id="form_file" name="form_file"/></label><br/>';
   $html .= '<a href="#" onclick="load_ajax();"> Poster ! </a><br/>';
   $html .= '<div id="zone_ajax"></div>';
$i = 0;
while ($i < $nb_page) {
   $html .= ' <a href="index.php?page=fo_home&offset=' . $i . '">' . ($i + 1) . '</a>';
   $i = $i++;
}

?>

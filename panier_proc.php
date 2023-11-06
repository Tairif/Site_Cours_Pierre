<?php
   $bdd = new Data();
   if(isset($_GET['del_id_produit']) && !empty($_GET['del_id_produit'])) {
      // L'utilisateur a voulu retier un produit au panier

      foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
         if($_SESSION[SESSION_NAME]['panier'][$key]['id_produit'] == $_GET['del_id_produit']) {
             unset($_SESSION[SESSION_NAME]['panier'][$key]);
         }
      }

      header('Location: index.php?page=fo_panier');
   }

   if(isset($_GET['update_panier']) && !empty($_GET['update_panier'])) {
      // Update du panier
      $id_produit = $_GET['id_produit'];
      $new_qte = $_GET['new_qte'];
      foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
         if($_SESSION[SESSION_NAME]['panier'][$key]['id_produit'] == $id_produit) {

            // On vérifie si la nouvelle qte est disponible
            $qte = $bdd->squery("SELECT SUM(qte) FROM t_produit_stock WHERE fk_produit=".$id_produit);
            if($qte >= $new_qte)
               $_SESSION[SESSION_NAME]['panier'][$key]['qte'] = $new_qte;
            else
               $_SESSION[SESSION_NAME]['panier'][$key]['qte'] = $qte;
         }
      }
      header('Location: index.php?page=fo_panier');
   }

   $info_paiement = '';
   if(isset($_POST) && !empty($_POST)) {
      // Traitement du formulaire => On prepare la commande
      // Gestion table t_commande
      $h = array();
      $h['fk_user'] = $_SESSION[SESSION_NAME]['id_user'];
      $h['date_creation'] = time();
      $h['fk_statut'] = $bdd->squery("SELECT id FROM t_statut_commande WHERE isDefault=1 LIMIT 1");

      $last_cmd = $bdd->squery("SELECT n_commande FROM t_commande ORDER BY id DESC LIMIT 1");
      if($last_cmd) {
         $next_cmd = intval(str_replace('CWEB_','',$last_cmd)) + 1;
         $h['n_commande'] = 'CWEB_'.str_pad($next_cmd, 7, '0', STR_PAD_LEFT);
      } else {
         // Premiere commande
         $h['n_commande'] = 'CWEB_0000001';
      }

      $id_commande = $bdd->sql_insert('t_commande',$h);

      // Gestion table t_commande_produit
      foreach ($_SESSION[SESSION_NAME]['panier'] as $key => $data_produit) {
         $h = array();
         $h['fk_commande'] = $id_commande;
         $h['fk_produit'] = $data_produit['id_produit'];
         $h['qte'] = $data_produit['qte'];

         // Gestion prixHT, tva, prixTTC, reduction
         $sql = "SELECT ";
         $sql.= " p.prixHT AS prixHT, ";
         $sql.= " t.value AS tva, ";
         $sql.= " pr.reduction AS reduction ";
         $sql.= " FROM t_produit p ";
         $sql.= " LEFT JOIN t_tva t ON t.id = p.fk_tva ";
         $sql.= " LEFT JOIN t_promotion pr ON pr.id = p.fk_promotion ";
         $sql.= " WHERE p.id=".$data_produit['id_produit'];

         $info_produit = $bdd->getData($sql);
         $info_produit = $info_produit[0];

         $h['tva'] = $info_produit['tva'];

         if($info_produit['reduction']>0) {
            $h['prixHT'] = $info_produit['prixHT'] - ($info_produit['prixHT'] * $info_produit['reduction'] / 100);
            $h['prixTTC'] = $h['prixHT'] + ($h['prixHT'] * $info_produit['tva'] / 100);
            $h['reduction'] = $info_produit['reduction'];
         } else {
            $h['prixHT'] = $info_produit['prixHT'];
            $h['prixTTC'] = $info_produit['prixHT'] + ($info_produit['prixHT'] * $info_produit['tva'] / 100);
            $h['reduction'] = 0;
         }
         $bdd->sql_insert('t_commande_produit',$h);
      }

      // On réinitialise le panier
      $_SESSION[SESSION_NAME]['panier'] = array();
      $info_paiement = "Votre Commande est passée. Nous faisons tout pour la traiter le plus rapidement possible.<br/><br/>";
      $info_paiement.= "Vous pouvez suivre l'avancement de votre commande dans la gestion de votre profil (historique des commandes)";

   }

   $total_price_ht = 0;
   $total_price_ttc = 0;
   $total_promo = 0;
   $html = '<div class="titre_panier">Votre Panier</div>';

   if($info_paiement) {
      $html.= '<div class="information_paiement">';
      $html.= '   <img src="images/interface/validation_commande.png" /><br/><br/><br/>'.$info_paiement;
      $html.= '</div>';
   }

   if(!empty($_SESSION[SESSION_NAME]['panier'])) {

      $html .= '<div style="width:70%; margin: auto;">';
      $html .= '    <table style="width:100%;margin:auto;padding:20px;" cellspacing="0" cellpadding="0">';

      $html .= '        <tr class="tab_header">';
      $html .= '            <td class="tab_td" style="width: 52%;">Produit</td>';
      $html .= '            <td class="tab_td" style="width: 12%;">Prix HT</td>';
      $html .= '            <td class="tab_td" style="width: 12%;">Prix TTC</td>';
      $html .= '            <td class="tab_td" style="width: 12%;">Quantité</td>';
      $html .= '            <td class="tab_td" style="width: 12%;">Sous total</td>';
      $html .= '        </tr>';

      $i = 0;
      foreach ($_SESSION[SESSION_NAME]['panier'] as $data_produit_cart) {
         $image = $bdd->squery("SELECT nom_fichier FROM t_produit_image WHERE fk_produit=".$data_produit_cart['id_produit']." LIMIT 1");
         $sql = "SELECT * FROM t_produit WHERE id=".$data_produit_cart['id_produit'];
         $data_produit = $bdd->getData($sql);
         $data_produit = $data_produit[0];

         $tva = $bdd->squery("SELECT value FROM t_tva WHERE id=".$data_produit['fk_tva']);
         $sql = "SELECT titre FROM t_produit_trad WHERE fk_produit=".$data_produit_cart['id_produit']." AND fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
         $nom = $bdd->squery($sql);

         if($data_produit['fk_promotion']) {
            $reduction = $bdd->squery("SELECT pr.reduction AS reduction FROM t_produit p LEFT JOIN t_promotion pr ON pr.id=p.fk_promotion WHERE p.id=".$data_produit_cart['id_produit']);
            $total_promo += (($data_produit['prixHT'] * $reduction / 100) * $data_produit_cart['qte']);
            $prixHT = $data_produit['prixHT'] - ($data_produit['prixHT'] * $reduction / 100);
            $prixTTC = $prixHT + ($prixHT * $tva / 100);
         } else {
            $prixHT = $data_produit['prixHT'];
            $prixTTC = $data_produit['prixHT'] + ($prixHT * $tva / 100);
         }

         // Gestion Total (prix)
         $total_price_ht += $prixHT * $data_produit_cart['qte'];
         $total_price_ttc += $prixTTC * $data_produit_cart['qte'];

         $html .= '        <tr '.(($i++ % 2)?'class="tab_row_1"':'class="tab_row_2"').'>';
         $html .= '            <td>';
         $html .= '                <a onclick="if(window.confirm(\'Etes vous sur ?\')) return true; else return false;" href="index.php?page=fo_panier&del_id_produit='.$data_produit_cart['id_produit'].'" style="text-decoration:none;">';
         $html .= '                    <img src="images/interface/suppr.png" style="margin-left: 10px; vertical-align: middle;"/>';
         $html .= '                </a>';
         $html .= '                <img src="images/produit/'.$image.'" style="width:64px; height: 64ox; margin: 10px; vertical-align: middle;" />&nbsp;&nbsp;'.$nom;
         if($data_produit['fk_promotion']) {
            ///$reduction
            $html .= '                <span class="zone_information_panier_product_price_promo">&nbsp;Promo&nbsp;&nbsp; -'.ceil($reduction).' %</span>';
         }

         $html .= '            </td>';
         $html .= '            <td>'.number_format($prixHT,2).' €</td>';
         $html .= '            <td>'.number_format($prixTTC,2).' €</td>';
         $html .= '            <td><input type="text" class="product-input" style="width: 80%;" value="'.$data_produit_cart['qte'].'" id="produit_'.$data_produit_cart['id_produit'].'" attr="'.$data_produit_cart['id_produit'].'" /></td>';
         $html .= '            <td>'.number_format(($prixTTC * $data_produit_cart['qte']),2).' €</td>';
         $html .= '        </tr>';
      }
      $html .= '    </table>';

      // Gestion tableau Total
      $html .= '<table style="width:45%;margin-right: 0px;margin-left:calc(60% - 84px);padding:20px;border-collapse: collapse;" cellspacing="0" cellpadding="0" >';
      $html .= '    <tr>';
      $html .= '        <td class="tab_header panier_total"> Total HT </td>';
      $html .= '        <td class="panier_info_total"> '.number_format($total_price_ht,2).' €</td>';
      $html .= '    </tr>';
      $html .= '    <tr>';
      $html .= '        <td class="tab_header panier_total"> Total TTC </td>';
      $html .= '        <td class="panier_info_total"> '.number_format($total_price_ttc,2).' €</td>';
      $html .= '    </tr>';
      $html .= '    <tr>';
      $html .= '        <td class="tab_header panier_total"> Total A Payer  </td>';
      $html .= '        <td class="panier_info_total"><span> '.number_format($total_price_ttc,2).' €</span></td>';
      $html .= '    </tr>';
      if($total_promo) {
         $html .= '    <tr>';
         $html .= '        <td colspan="2" class="info_promo_panier"> Vous avez économisez ' . number_format($total_promo,2) . ' € sur votre Commande !</td>';
         $html .= '    </tr>';
      }
      $html .= '</table>';


      // Validation Panier
      if(userConnected()) {
         $html .= '<form method="POST" action="index.php?page=fo_panier" enctype="multipart/form-data">';
         $html .= '    <div class="button-submit">';
         $html .= '        <input type="submit" value="Payer" />';
         $html .= '        <input type="hidden" name="valide_cart" id="valide_cart" value="1" />';
         $html .= '    </div>';
         $html .= '</form>';
      } else {
         $html .= 'Vous devez etre connecté pour continuer...';
      }
      $html .= '</div>';

   }

  // dbug($_SESSION[SESSION_NAME]['panier']);
?>

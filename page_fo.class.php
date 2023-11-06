<?php
    /**
     * Class  Page
     *
     * Permet de gérer la création d'une page HTML (du <body> au </body>
     *
     * Auteur : MOI
     * Date : 25-07-20232
     * Version : 1.0
     */

    class Page_FO{
        private $header = '';
        private $footer = '';
        private $corps = '';

        /** public function __construct($show_interface=true, $title='',$link=array())
         *
         * Description : Constructeur de la class, Initialise le header et footer de la page
         * Type        : Public
         * Parametre   : $show_interface : permet d'afficher ou non l'interface (Menu, Header, Footer de page...)
         *               $title : Titre de la page (afficher dans le Header)
         *               $link : Array() qui permettra d'afficher ou non des sous menu dans les page (dans le Header)
         * */
        public function __construct($show_interface=true, $title='',$link=array()){
            if($show_interface) {
                $this->build_header($title,$link);
                $this->build_footer();
            }else{
                $this->header = '<body>';
                $this->footer = '</body>';
            }
        }

        /** public function build_content($html='')
         *
         * Description : Permet d'ajouter le contenu de la page dans l'interface
         * Type        : Public
         * Parametre   : $html chaine de caractère contenu le code HTML de la page a afficher (le contenu)
         * */
        public function build_content($html=''){
            $this->corps = $html;
        }

        /** public function show()
         *
         * Description : Permet d'afficher la poge à l'ecran
         * Type        : Public
         * Parametre   : null
         * */
        public function show(){
            echo $this->header;
            echo $this->corps;
            echo $this->footer;
        }

        /** public function build_header($title,$link)
         *
         * Description : Permet de préparer le header du corps de fichier (le logo, le menu etc )
         * Type        : Privée
         * Parametre   : $title => Titre afficher dans le Header de la page
         *               $link => Array() qui va contenir eventuellement un sous menu qu'on pourra afficher dans le Header
         * */
        private function build_header($title,$link){
            $dataBDD = new Data();
            $this->header = '<body>';

            $this->header.= '   <div class="main_header">';
            $this->header.= '       <div class="main_header_left">';
            $this->header.='        </div>';

            // Gestion du Menu Principal du site
            $this->header.= '       <div class="main_header_menu">';
            $this->header.= '           <ul class="menu">';
            // On recupère la liste des articles attachés au menu en cours de modification
            $sql = "SELECT m.*, m.id AS id_menu, ";
            $sql.= " mt.libelle AS libelle ";
            $sql.= " FROM t_menu m";
            $sql.= " LEFT JOIN t_menu_trad mt ON mt.fk_menu=m.id AND mt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
            $sql.= " WHERE fk_parent=0 ORDER BY ordre ASC";

            $datas_menu = $dataBDD->getData($sql);
            if($datas_menu){
                //$cpt = 0;
                foreach($datas_menu as $data_menu) {
                    $this->header.= '        <li class="menu_li">';
                    if($data_menu['url'])
                        $url = $data_menu['url'];
                    else
                        $url = $this->getCleanURL($data_menu['id_menu']);
                    $this->header.= '            <a class="menu_a" href="'.$url.'">'.$data_menu['libelle'].'</a>';
                    $gotChild = $dataBDD->squery('SELECT 1 FROM t_menu WHERE fk_parent='.$data_menu['id_menu']);
                    if($gotChild){
                        // Sous menu
                        $this->header.= '            <ul class="submenu">';
                        $sql = "SELECT m.*, m.id AS id_menu, ";
                        $sql.= " mt.libelle AS libelle ";
                        $sql.= " FROM t_menu m";
                        $sql.= " LEFT JOIN t_menu_trad mt ON mt.fk_menu=m.id AND mt.fk_langue=".$_SESSION[SESSION_NAME]['id_langue'];
                        $sql.= " WHERE fk_parent=".$data_menu['id_menu']." ORDER BY ordre ASC";

                        $datas_menu_child = $dataBDD->getData($sql);
                        if($datas_menu_child){
                            foreach($datas_menu_child as $data_menu_child) {
                                $this->header.= '                <li>';
                                if($data_menu_child['url'])
                                    $url = $data_menu_child['url'];
                                else
                                    $url = $this->getCleanURL($data_menu_child['id_menu']);
                                $this->header.= '                    <a href="'.$url.'" class="submenu_a">'.$data_menu_child['libelle'].'</a>';
                                $this->header.= '                </li>';
                            }
                        }
                        $this->header.= '            </ul>';
                    }
                    $this->header.= '             </li>';
                }
            }
            $this->header.= '             <li class="menu_li">';
            $this->header.= '               <a href="index.php?page=fo_panier" class="menu_a">Mon Panier';
            $nb_item = 0;
            if(isset($_SESSION[SESSION_NAME][`panier`]) && count($_SESSION[SESSION_NAME][`panier`])) {
                foreach($_SESSION[SESSION_NAME][`panier`] as $data) {
                    $nb_item += $data['qte'];
                }
                $this->header.= '                &nbsp;&nbsp;<span id="nb_product_car">('.$nb_item.')</span>';
            } else {
                $this->header.= '                &nbsp;&nbsp;<span id="nb_product_car"></span>';
            }
            $this->header.= '                 </a>';
            $this->header.= '              </li>';
            $this->header.= '              <li class="menu_li">';
            if(userConnected()) {
                if(is_file('images/avatar/'.$_SESSION[SESSION_NAME]['avatar'])) {
                    $this->header .= '                <a href="index.php?page=fo_user" class="menu_a"><img class="fo_avatar" src="images/avatar/'.$_SESSION[SESSION_NAME]['avatar'].'" style="vertical-align: text-bottom;"/></a>';
                } else {
                    $this->header .= '                <a href="index.php?page=fo_user" class="menu_a"><img src="images/interface/compte.png" style="vertical-align: text-bottom;"/></a>';
                }
                $this->header .= '                <a href="index.php?page=logout" class="menu_a"><img class="fo_logout" src="images/interface/logout.png" style="vertical-align: text-bottom;"/></a>';
            } else {
                $this->header .= '                <a href="index.php?page=login" class="menu_a"><img src="images/interface/compte.png" style="vertical-align: text-bottom;"/></a>';
            }
            $this->header.= '              </li>';
            $this->header.= '           </ul>'; // Fermeture menu racine
            $this->header.= '       </div>';

            // TODO: Gestion du choix de la langue
            $this->header.= '       <div class="main_header_right">';

            $this->header.= '       </div>';
            if(userCanAdmin()) {
                $this->header .= '       <div class="acces_bo">';
                $this->header .= '          <a href="index.php?page=home">';
                $this->header .= '             <img src="images/interface/param.png" />';
                $this->header .= '          </a>';
                $this->header .= '       </div>';
            }
            $this->header.= '   </div>';

            $this->header.= '	<div class="zone_contenu_clean">';
        }

        /** public function build_footer()
         *
         * Description : Permet de préparer le footer du corps de fichier (copyright, reseaux sociaux, information contact... )
         * Type        : Privée
         * Parametre   : null
         * */
        private function build_footer(){
            $this->footer = '	</div>';
            $this->footer.= '    <div class="fo_footer">';

            $this->footer.= '    </div>';
            $this->footer.= '</body>';
        }
    }
?>
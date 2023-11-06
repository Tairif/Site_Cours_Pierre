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

    class Page{
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
            $this->header = '<body>';

            $this->header.= '    <div class="header">';
            $this->header.= '        <img src="images/interface/logo_site.png" class="logo_site"/><br/>';

            $this->header.= '        <a href="index.php">';
            $this->header.= '            <img src="images/interface/home.png" /><br/>Accueil';
            $this->header.= '        </a>';
            $this->header.= '       <a href="index.php?page=shop">';
            $this->header.= '            <img src="images/interface/shop.png" /><br/>Cours';
            $this->header.= '        </a>';
            $this->header.= '       <a href="index.php?page=listing_user">';
            $this->header.= '            <img src="images/interface/user.png" /><br/>Utilisateur';
            $this->header.= '        </a>';
            $this->header.= '    </div>';
            $this->header.= '	<div class="header_top">';
            $this->header.= '		<div class="header_login">';

            // Information utilisateur connecté et deconnexion
            $this->header.= '           <div class="header_logout_btn">';
            $this->header.= '               <a href="index.php?page=logout">';
            $this->header.= '                   <img src="images/interface/logout.png" />';
            $this->header.= '               </a>';
            $this->header.= '           </div>';
            $this->header.= '           <div class="header_info_user">';
            $this->header.= '               <a href="index.php?page=panier">';
            $this->header.= '                   <img src="images/interface/cart.png" />';
            $this->header.= '               </a>';
            $nb_item = 0;
            if(count($_SESSION[SESSION_NAME]['panier'])) {
                foreach($_SESSION[SESSION_NAME]['panier'] as $data) {
                    $nb_item += $data['qte'];
                }
                $this->header .= '               <span class="nb_item_panier"> (' . $nb_item . ') </span>';
            }
            $this->header.= '               '.$_SESSION[SESSION_NAME]['nom_user'];
            if(is_file('images/avatar/'.$_SESSION[SESSION_NAME]['avatar'] ))
                $this->header.= '            <a href="index.php?page=fo_user&id_user='.$_SESSION[SESSION_NAME]['id_user'].'">';
                $this->header.= '               <img src="images/avatar/'.$_SESSION[SESSION_NAME]['avatar'] .'" />';
                $this->header.= '            </a>';
            $this->header.= '           </div>';

            $this->header.= '		</div>';
            $this->header.= '		<div class="header_title">';
            $this->header.= '			'.$title;
            $this->header.= '		</div>';
            $this->header.= '		<div class="header_link">';
            foreach($link as $data_link){
                $this->header.= '           <div class="one_link">';
                $this->header.= '               <a href="'.$data_link['url'].'">';
                $this->header.= '                   <img src="images/interface/'.$data_link['image'].'" /><br/>'.$data_link['text'] ;
                $this->header.= '               </a>';
                $this->header.= '           </div>';
            }
            $this->header.= '		</div>';
            $this->header.= '	</div>';
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
            $this->footer.= '</body>';
        }
    }
?>
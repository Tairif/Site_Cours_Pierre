<title>Formation IFR - Accueil Cours</title>
<script>
    function charge_rayon(id_rayon) {
        _post_onload.URL = 'index.php?page=ajax_home';
        _post.id_rayon = id_rayon;
        _ajax_post('charge_rayon');
    }

    function add_product_cart(id_produit) {
        _post_onload.URL = 'index.php?page=ajax_home';
        _post.id_produit = id_produit;
        _ajax_post('add_product_cart');
    }

    function load_page(page){
        _post_onload.URL = 'index.php?page=ajax_home';
        _post.page = page;
        _ajax_post('load_page');
    }

    window.addEventListener("load", function() {
        // Execution uniquement quand tout est chargé sur la page...
        load_page(0);
    });

</script>
<link rel="stylesheet" type="text/css" href="css/shop.css"/>
<style type=text/css>
    .zone_pagination{
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    .one_offset{
        width: 24px;
        height: 24px;
        border-radius: 3px;
        background-color: #eeeeee;
        border: 1px solid #cccccc;
        text-align: center;
        font-size: 11px;
        line-height: 24px;
        margin-left:4px;
        margin-right: 4px;
    }

    .one_offset_dot{
        width: 24px;
        height: 24px;
        background-color: none;
        border: none;
        text-align: center;
        font-size: 11px;
        line-height: 24px;
        margin-left:4px;
        margin-right: 4px;
    }

    .bg_white{
        background-color: #ffffff;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 0px 3px;
    }
    .one_offset a{
        text-decoration: none;
        font-size: 11px;
        display: block;
        width: 24px;
        height: 24px;
        line-height: 24px;
        color: black;
    }
</style>
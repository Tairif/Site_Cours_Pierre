<title>Formation IFR - Work</title>
<script>

    function load_ajax() {
        // Etape 1 : On defini l'URL de la page Ajax
        _post_onload.URL = 'index.php?page=ajax_work';

        // On poste les valeurs des champs input de la page
        _post.titre = document.getElementById('form_titre').value;
        _post.id_rayon = document.getElementById('form_nombre').value;

        // On peut meme poster une image (ou un fichier) en ajax
        _upload_file('form_file');

        // On lance la requete
        _ajax_post('load_message');
    }

</script>
<link rel="stylesheet" type="text/css" href="css/shop.css"/>
<?php
    $bdd = new Data();
    $html = '';

    // Test retour formulaire
    if(isset($_POST) && !empty($_POST)){
        // Verification login et mot de passe avec les données en BDD
        $login = $_POST['login'];
        $password = md5($_POST['password']);

        $sql="SELECT * FROM t_user WHERE login='".addslashes($login)."' LIMIT 1;";

        $rs = $bdd->query($sql);

        if($rs && mysqli_num_rows($rs)){
            $data = mysqli_fetch_assoc($rs);
            if(!empty($password) && $password == $data['password']){
                // Enregistrement des informations en session
                $_SESSION[SESSION_NAME]['id_user'] = $data['id'];
                $_SESSION[SESSION_NAME]['nom_user'] = $data['prenom'].' '.$data['nom'];
                $_SESSION[SESSION_NAME]['avatar'] = $data['avatar'];
                $_SESSION[SESSION_NAME]['id_langue'] = $data['fk_langue'];
                $_SESSION[SESSION_NAME]['isAdmin'] = $data['isAdmin'];

                header("location: index.php?page=home");
            }else{
                $html = '<div class="login_info_error">Mot de passe incorrect !</div>';
            }
        }else{
            $html = '<div class="login_info_error">Login Introuvable</div>';
        }



        // Seconde methode (plus sécurisé mais moins ergonomique pour l'utilisateur
        /*$sql = "SELECT * FROM t_user WHERE login='".$login."' AND password='".$password."' LIMIT 1;";
        $rs = query($sql);
        if($rs && mysqli_num_rows($rs)){
            // Login OK
            $data = mysqli_fetch_assoc($rs);
            $_SESSION[SESSION_NAME]['id_user'] = $data['id'];
            $_SESSION[SESSION_NAME]['nom_user'] = $data['prenom'].' '.$data['nom'];
            $_SESSION[SESSION_NAME]['avatar'] = 'pic/upload/avatar/'.$data['avatar'];

            header("location: index.php");
        }else{
            // Login KO
            $message_error = '<div class="login_ko">Login impossible</div>';
        }*/


    }

    // Creation de l'interface...
    $html.= '<div class="container">';
    $html.= '   <form action="index.php?page=login" method="POST">';
    $html.= '        <p>Bienvenue</p>';
    $html.= '        <input type="text" name="login" id="login" placeholder="Login"/><br>';
    $html.= '        <input type="password" name="password" id="password" placeholder="Password"/><br/>';
    $html.= '        <input type="submit" value="Connexion"><br>';
    $html.= '        <a href="index.php?page=fo_user" class="inscription"> Inscrivez vous ! </a>';
    $html.= '    </form>';
    $html.= '    <div class="drop drop-1"></div>';
    $html.= '    <div class="drop drop-2"></div>';
    $html.= '    <div class="drop drop-3"></div>';
    $html.= '    <div class="drop drop-4"></div>';
    $html.= '    <div class="drop drop-5"></div>';
    $html.= '</div>';

?>

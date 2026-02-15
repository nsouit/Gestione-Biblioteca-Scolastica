<?php
session_start();
try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");

    if (isset($_POST['login'])) {
        $_SESSION['active_form'] = "login";

        $email = $_POST['email'];
        $passwd = hash(PASSWD_HASH_TYPE, $_POST['passwd']);

        //echo $passwd;

        $sql = "SELECT * FROM utente WHERE email = '".$email."';";

        $results = $conn->query($sql);

        if ($results->rowCount() > 0) {
            $user = $results->fetch();

            //echo $user['nome'];
            if ($passwd == $user['passwd']) {
                echo "Correttamente autenticato.";
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['email'] = $user['email'];
                header("Location: ../index.php");
                exit();
            }
            else {
                // genero errore con session
                //echo "Password incorretta";
                $_SESSION['login_error'] = "Password errata!";
                header("Location: login_register.php");
                exit();
            }
        }
        
        // utente non trovato, genero errore con session
        $_SESSION['login_error'] = "Utente inesistente!";
        header("Location: login_register.php");
        exit();
    }
    else if (isset($_POST['register'])) {
        $_SESSION['active_form'] = "register";

        $cf = $_POST['cf'];
        $nome = $_POST['nome'];
        $cnome = $_POST['cnome'];
        $datan = $_POST['datan'];
        $tipo = $_POST['tipo'];
        $email = $_POST['email'];
        $passwd = hash(PASSWD_HASH_TYPE, $_POST['passwd']);
        $passwd_conf = hash(PASSWD_HASH_TYPE, $_POST['passwd_conf']);

        if ($passwd == $passwd_conf) {
            if ((strlen($_POST['passwd']) < PASSWD_MIN_LEN)) {
                // session error
                //echo "hey, non mi freghi";
                $_SESSION['register_error'] = "Password troppo corta!";
            }
            else if (strlen($cf) != CF_LEN) {
                $_SESSION['register_error'] = "Codice Fiscale non corretto!";
            }
            else {
                // prima di inserire l'utente controllo che i suoi dati non siano già presenti
                $sql = "SELECT * FROM utente WHERE email = '".$email."';";
                $results = $conn->query($sql);

                if ($results->rowCount() <= 0) {
                    $sql = "SELECT * FROM utente WHERE cf = '".$cf."';";
                    $results = $conn->query($sql);

                    if ($results->rowCount() <= 0) {
                        $sql = "INSERT INTO utente (cf, nome, cognome, data_nascita, email, passwd, tipo) VALUES ('".$cf."', '".$nome."', '".$cnome."', '".$datan."', '".$email."', '".$passwd."', ".$tipo.");";    
                        $conn->query($sql);
                        //echo $results;
                        $_SESSION['active_form'] = "login";
                    }
                    $_SESSION['register_error'] = "Codice Fiscale già registrato!";
                }
                $_SESSION['register_error'] = "Email già registrata!";
            }
        }
        else
            $_SESSION['register_error'] = "Inserimento password errato!";

        header("Location: login_register.php");
        exit();
    }
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
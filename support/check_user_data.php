<?php
session_start();
try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");

    $errors = array();

    function checkSet($field, $error_message, & $errors){

        if(isset($_POST[$field]) && trim($_POST[$field]) != "") {
            return true;
        }
        else {
            $errors[] = $error_message;
            return false;
        }
    }

    /*=================================
                    LOGIN
    =================================*/
    if (isset($_POST['login'])) {
        $_SESSION['active_form'] = "login";

        $sql = "SELECT * FROM utente WHERE email = '$_POST[email]';";

        $results = $conn->query($sql);

        if ($results->rowCount() > 0) {
            $user = $results->fetch();

            $salt = $user['salt'];

            $salt_div = str_split($salt, strlen($salt)/2);
            
            $passwd = hash('sha256', $salt_div[0].$_POST['passwd'].$salt_div[1]);

            // cerco il tipo di utente
            $sql = "SELECT tipo FROM tipo_utente WHERE IDtipo = $user[tipo];";
            $results = $conn->query($sql);

            //echo $user['nome'];
            if ($passwd == $user['passwd'] && ($results->rowCount() > 0)) {
                $usr_type = $results->fetch();

                echo "Correttamente autenticato.";
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['ruolo'] = $usr_type['tipo'];
                header("Location: ../index.php");
                exit();
            }
            else {
                // genero errore con session
                //echo "Password incorretta";
                $_SESSION['login_error'] = "Password errata!";
            }
        }
        else
            // utente non trovato, genero errore con session
            $_SESSION['login_error'] = "Utente inesistente!";

        header("Location: ../login.php");
        exit();
    }


    /*====================================
                    REGISTER
    ====================================*/
    else if (isset($_POST['register'])) {
        $_SESSION['active_form'] = "register";


        /*
        $cf = $_POST['cf'];
        $nome = $_POST['nome'];
        $cnome = $_POST['cnome'];
        $datan = $_POST['datan'];
        $tipo = $_POST['tipo'];
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];
        $passwd_conf = $_POST['passwd_conf'];
        */

        if ($_POST['passwd'] == $_POST['passwd_conf']) {
            if ((strlen($_POST['passwd']) < PASSWD_MIN_LEN)) {
                // session error
                //echo "hey, non mi freghi";
                $_SESSION['register_error'] = "Password troppo corta!";
            }
            else if (strlen($_POST['cf']) != CF_LEN) {
                $_SESSION['register_error'] = "Codice Fiscale non corretto!";
            }
            else {
                // prima di inserire l'utente controllo che i suoi dati non siano già presenti
                $sql = "SELECT * FROM utente WHERE email = '$_POST[email]' OR cf = '$_POST[cf]';";
                $results = $conn->query($sql);

                if ($results->rowCount() <= 0) {
                    // calcolo salt + password con salt
                    /*if (strlen($_POST["passwd"]) != 64) {
                        $_SESSION['register_error'] = "Hash password non valido";
                        header("Location: register.php");
                        
                    }*/

                    if ($_POST["passwd"] === hash('sha256', '')) {
                        $_SESSION['register_error'] = "Password non inserita";
                        header("Location: ../register.php");
                    }

                    $salt = hash('sha256', rand());
                    

                    //var_dump($_POST);
                    
                    //echo "<br>Salt: " . $salt;
                    
                    $salt_div = str_split($salt, strlen($salt)/2);
                    
                    //echo "<br>Salt: " . strlen($salt);
                    
                    //echo "<br>Salt 1: " . $salt_div[0];
                    //echo "<br>Salt 2: " . $salt_div[1];
                    
                    $saved_pwd = hash('sha256', $salt_div[0].$_POST['passwd'].$salt_div[1]);

                    $sql = "INSERT INTO utente (cf, nome, cognome, data_nascita, email, passwd, salt, tipo) VALUES ('$_POST[cf]', '$_POST[nome]', '$_POST[cnome]', '$_POST[datan]', '$_POST[email]', '$saved_pwd', '$salt', $_POST[tipo]);";    
                    $conn->query($sql);
                    header("Location: ../index.php");
                } else {
                    
                    $_SESSION['register_error'] = "Codice Fiscale o email già registrata!";
                }
                
            }
        }
        else
            $_SESSION['register_error'] = "Inserimento password errato!";

        header("Location: ../register.php");
        exit();
    }
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
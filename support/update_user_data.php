<?php
session_start();

//var_dump($_POST);

if (!isset($_SESSION["IDutente"]) || !isset($_SESSION["nome"]) || !isset($_SESSION["email"]) || !isset($_SESSION["ruolo"])) {
    header("location: index.php");
    exit();
}


try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");

    function checkSetNoErrors($field){

        if(isset($_POST[$field]) && trim($_POST[$field]) != "")
            return true;
        else
            return false;
    }
    
    if (isset($_POST['update_data'])) {
        $_SESSION['active_form'] = "update_data";

        if (!checkSetNoErrors('nome')) {
            $_SESSION['update_error'] = "Nessun nome inserito";
            header("Location: ../personal_profile.php");
            exit();
        }

        

        if (!checkSetNoErrors('cognome')) {
            $_SESSION['update_error'] = "Nessun cognome inserito";
            header("Location: ../personal_profile.php");
            exit();
        }

        if (!checkSetNoErrors('cf')) {
            $_SESSION['update_error'] = "Nessun codice fiscale inserito";
            header("Location: ../personal_profile.php");
            exit();
        } else if (strlen($_POST['cf']) != PASSWD_HASH_LEN) {
            $_SESSION['update_error'] = "Codice fiscale non valido";
            header("Location: ../personal_profile.php");
            exit();
        }

        if (!checkSetNoErrors('datan')) {
            $_SESSION['update_error'] = "Nessuna data di nascita inserita";
            header("Location: ../personal_profile.php");
            exit();
        }

        if (!checkSetNoErrors('email')) {
            $_SESSION['update_error'] = "Nessuna email inserito";
            header("Location: ../personal_profile.php");
            exit();
        }

        // sono sicuro che è stato inserito un genere
        // controllo se è goà presente o no

        $sql = "UPDATE utente
                SET nome = '$_POST[nome]', cognome = '$_POST[cognome]', cf = '$_POST[cf]', data_nascita = '$_POST[datan]', email = '$_POST[email]'
                WHERE IDutente = $_SESSION[IDutente];";

        $results = $conn->query($sql);

        header("Location: ../personal_profile.php");
        exit();
    }

    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
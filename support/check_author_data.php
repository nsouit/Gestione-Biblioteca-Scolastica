<?php
session_start();

//var_dump($_POST);


try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");

    function checkSetNoErrors($field){

        if(isset($_POST[$field]) && trim($_POST[$field]) != "")
            return true;
        else
            return false;
    }
    
    if (isset($_POST['register'])) {
        $_SESSION['active_form'] = "register";

        if (!checkSetNoErrors('nome_autore') || !checkSetNoErrors('cognome_autore') || !checkSetNoErrors('data_nascita')) {
            $_SESSION['register_error'] = "Nessun autore inserito";
            header("Location: ../insert_author.php");
            exit();
        }

        // sono sicuro che è stato inserito un genere
        // controllo se è goà presente o no

        $sql = "SELECT * FROM autore WHERE nome_autore = '$_POST[nome_autore]' OR cognome_autore = '$_POST[cognome_autore]';";

        $results = $conn->query($sql);

        if ($results->rowCount() < 1) {
            // non trovo una corrispondenza, quindi lo aggiungo.
            $sql = "INSERT INTO autore (nome_autore, cognome_autore, data_nascita_autore) VALUE ('$_POST[nome_autore]', '$_POST[cognome_autore]', '$_POST[data_nascita]');";

            $results = $conn->query($sql);

            header("Location: ../index.php");
            exit();
        }

        // genere già presente
        $_SESSION['register_error'] = "Autore già esistente";
        header("Location: ../insert_author.php");
        exit();
    }

    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
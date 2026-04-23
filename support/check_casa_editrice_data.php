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

        if (!checkSetNoErrors('nome_casa_editrice')) {
            $_SESSION['register_error'] = "Nessun genere inserito";
            header("Location: ../insert_casa_editrice.php");
            exit();
        }

        // sono sicuro che è stato inserito un genere
        // controllo se è goà presente o no

        $sql = "SELECT * FROM casa_editrice WHERE nome_casa_editrice = '$_POST[nome_casa_editrice]';";

        $results = $conn->query($sql);

        if ($results->rowCount() < 1) {
            // non trovo una corrispondenza, quindi lo aggiungo.
            $sql = "INSERT INTO casa_editrice (nome_casa_editrice) VALUE ('$_POST[nome_casa_editrice]');";

            $results = $conn->query($sql);

            header("Location: ../index.php");
            exit();
        }

        // genere già presente
        $_SESSION['register_error'] = "Casa editrice già esistente";
        header("Location: ../insert_casa_editrice.php");
        exit();
    }

    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
<?php
session_start();

//var_dump($_POST);


try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");
    
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
    }
    else if (isset($_POST['delete'])) {
        // DELETE FROM table_name WHERE condition; 
        try {
            $sql = "DELETE FROM casa_editrice WHERE IDcasa_editrice = $_POST[casa_editrice];";
        
            $results = $conn->query($sql);
        } catch (PDOException $e) {
             $_SESSION['delete_error'] = "Impossibile eliminare la casa editrice. Violazione Database.";
        }
    }
    else if (isset($_POST['modify'])) {
        $campi = array(
            "nome_casa_editrice",
            "casa_editrice",
        );

        foreach ($campi as $campo) {
            if (!checkSetNoErrors($campo)) {
                $_SESSION['modify_error'] = "Aggiornamento casa editrice errato";
                header("Location: ../insert_casa_editrice.php#modify");
                exit();
            }
        }

        $sql = "
        UPDATE casa_editrice
        SET nome_casa_editrice = '$_POST[nome_casa_editrice]'
        WHERE IDcasa_editrice = $_POST[casa_editrice];
        ";

        $results = $conn->query($sql);
    }

    
    header("Location: ../insert_casa_editrice.php");
    exit();

    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
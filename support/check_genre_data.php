<?php
session_start();

//var_dump($_POST);


try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");
    
    if (isset($_POST['register'])) {
        $_SESSION['active_form'] = "register";

        if (!checkSetNoErrors('nome_genere')) {
            $_SESSION['register_error'] = "Nessun genere inserito";
            header("Location: ../insert_genre.php");
            exit();
        }

        // sono sicuro che è stato inserito un genere
        // controllo se è goà presente o no

        $sql = "SELECT * FROM genere WHERE nome_genere = '$_POST[nome_genere]';";

        $results = $conn->query($sql);

        if ($results->rowCount() < 1) {
            // non trovo una corrispondenza, quindi lo aggiungo.
            $sql = "INSERT INTO genere (nome_genere) VALUE ('$_POST[nome_genere]');";

            $results = $conn->query($sql);

            header("Location: ../index.php");
            exit();
        }

        // genere già presente
        $_SESSION['register_error'] = "Genere già esistente";
    }
    else if (isset($_POST['delete'])) {
        // DELETE FROM table_name WHERE condition; 

        try {
            $sql = "DELETE FROM genere WHERE IDgenere = $_POST[genere];";

            $results = $conn->query($sql);
        } catch (PDOException $e) {
             $_SESSION['delete_error'] = "Impossibile eliminare il genere. Violazione Database.";
        }
    }
    else if (isset($_POST['modify'])) {
        $campi = array(
            "nome_genere",
            "genere",
        );

        foreach ($campi as $campo) {
            if (!checkSetNoErrors($campo)) {
                $_SESSION['modify_error'] = "Aggiornamento genere errato";
                header("Location: ../insert_genre.php#modify");
                exit();
            }
        }

        $sql = "
        UPDATE genere
        SET nome_genere = '$_POST[nome_genere]'
        WHERE IDgenere = $_POST[genere];
        ";

        $results = $conn->query($sql);
    }

    
    header("Location: ../insert_genre.php");
    exit();

    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
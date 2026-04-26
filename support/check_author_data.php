<?php
session_start();

//var_dump($_POST);


try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");

    // aggiungo i campi così con un foreach risolvo tutto e risparmio righe di codice
    $campi = array(
        "nome_autore",
        "cognome_autore",
        "data_nascita",
    );
    
    if (isset($_POST['register'])) {
        $_SESSION['active_form'] = "register";

        foreach ($campi as $campo) {
            if (!checkSetNoErrors($campo)) {
                $_SESSION['register_error'] = "Inserimento autore errato";
                header("Location: ../insert_author.php");
                exit();
            }
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
        
    }
    else if (isset($_POST['delete'])) {
        // DELETE FROM table_name WHERE condition; 
        try {
            $sql = "DELETE FROM autore WHERE IDautore = $_POST[autore];";
        
            $results = $conn->query($sql);
        } catch (PDOException $e) {
             $_SESSION['delete_error'] = "Impossibile eliminare l'autore. Violazione Database.";
        }
    }
    else if (isset($_POST['modify'])) {
        foreach ($campi as $campo) {
            if (!checkSetNoErrors($campo)) {
                $_SESSION['modify_error'] = "Aggiornamento autore errato";
                header("Location: ../insert_author.php#modify");
                exit();
            }
        }

        // per la variabile autore (id) la faccio esterna perché non è presente nell'array campi (perché lo uso anche per l'inserimento)
        if (!checkSetNoErrors("autore")) {
            $_SESSION['modify_error'] = "Aggiornamento autore errato";
            header("Location: ../insert_author.php#modify");
            exit();
        }

        /*
        UPDATE table_name
        SET column1 = value1, column2 = value2, ...
        WHERE condition; 
        */

        $sql = "
        UPDATE autore
        SET nome_autore = '$_POST[nome_autore]', cognome_autore = '$_POST[cognome_autore]', data_nascita_autore = '$_POST[data_nascita]'
        WHERE IDautore = $_POST[autore];
        ";

        $results = $conn->query($sql);
    }

    
    header("Location: ../insert_author.php");
    exit();
    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
<?php
session_start();

//var_dump($_POST);


try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");

    $campi = array(
        "isbn",
        "titolo",
        "anno_pubblicazione",
        "autore",
        "genere",
        "casa_editrice",
    );
    
    if (isset($_POST['register'])) {
        $_SESSION['active_form'] = "register";

        foreach ($campi as $campo) {
            if (!checkSetNoErrors($campo)) {
                $_SESSION['register_error'] = "Dati libro errati";
                header("Location: ../insert_book.php");
                exit();
            }
        }

        // sono sicuro che tutti i dati sono stati inseriti
        // controllo se è goà presente o no

        $sql = "SELECT * FROM libro WHERE isbn = '$_POST[isbn]';";

        $results = $conn->query($sql);

        if ($results->rowCount() < 1) {
            // non trovo una corrispondenza, quindi lo aggiungo.
            $sql = "INSERT INTO libro VALUE ('$_POST[isbn]', '$_POST[titolo]', $_POST[anno_pubblicazione], '$_POST[abstract]', $_POST[casa_editrice], $_POST[genere]);";



            echo $sql;
            //die();
            /*
            INSERT INTO libro VALUES
            ('8820101319','Se questo e un uomo',1947,'Primo Levi racconta la sua deportazione ad Auschwitz. Una testimonianza lucida sulla disumanizzazione e la sopravvivenza nei lager nazisti.',00007,07),
            */


            $results = $conn->query($sql);

            // aggiorno la tabella ausiliaria

            $sql = "INSERT INTO libri_scritti_autore VALUE ('$_POST[isbn]', $_POST[autore]);";
            $results = $conn->query($sql);
            // aggiorno stato copia libro
            //$sql = "INSERT INTO copia_libro ('$_POST[isbn]', IDstato_libro) VALUES";

            header("Location: ../index.php");
            exit();
        }
        $_SESSION['register_error'] = "Libro già esistente";
    }
    else if (isset($_POST['delete'])) {
        // DELETE FROM table_name WHERE condition; 
        try {
            $sql = "DELETE FROM libro WHERE isbn = $_POST[libro];";
        
            $results = $conn->query($sql);
        } catch (PDOException $e) {
            $_SESSION['delete_error'] = "Impossibile eliminare il libro. Violazione Database.";
        }
    }
    else if (isset($_POST['add_author'])) {
        if (!checkSetNoErrors("libro") || !checkSetNoErrors("autore")) {
            $_SESSION['add_author_error'] = "Dati errati";
            header("Location: ../insert_book.php#add_author");
            exit();
        }

        /*
        INSERT INTO libri_scritti_autore VALUE ('9788845210662',1);
        */

        try {
            $sql = "INSERT INTO libri_scritti_autore VALUE ('$_POST[libro]', $_POST[autore]);";
        
            $results = $conn->query($sql);
        } catch (PDOException $e) {
            $_SESSION['add_author_error'] = "Libro e autore già presenti";
            header("Location: ../insert_book.php#add_author");
            exit();
        }
    }
    
    header("Location: ../insert_book.php");
    exit();

    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
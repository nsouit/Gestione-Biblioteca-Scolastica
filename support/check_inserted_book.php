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

        if (!checkSetNoErrors('isbn')) {
            $_SESSION['register_error'] = "Nessun isbn inserito";
            header("Location: ../insert_book.php");
            exit();
        }

        if (!checkSetNoErrors('titolo')) {
            $_SESSION['register_error'] = "Nessun titolo inserito";
            header("Location: ../insert_book.php");
            exit();
        }

        if (!checkSetNoErrors('anno_pubblicazione')) {
            $_SESSION['register_error'] = "Nessun anno inserito";
            header("Location: ../insert_book.php");
            exit();
        }

        if (!checkSetNoErrors('autore')) {
            $_SESSION['register_error'] = "Nessun autore selezionato";
            header("Location: ../insert_book.php");
            exit();
        }

        if (!checkSetNoErrors('genere')) {
            $_SESSION['register_error'] = "Nessun genere selezionato";
            header("Location: ../insert_book.php");
            exit();
        }

        if (!checkSetNoErrors('casa_editrice')) {
            $_SESSION['register_error'] = "Nessuna casa editrice selezionato";
            header("Location: ../insert_book.php");
            exit();
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

        // genere già presente
        $_SESSION['register_error'] = "Libro già esistente";
        header("Location: ../insert_book.php");
        exit();
    }

    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
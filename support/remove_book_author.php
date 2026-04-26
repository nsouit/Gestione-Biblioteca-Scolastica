<?php
session_start();

//var_dump($_POST);

if ($_SESSION['ruolo'] != 'Docente') {
    header("Location: index.php");
    exit();
}


try {
    include("../inc/connection/start.php");
    include("../inc/db_tables_params.php");
    
    if (isset($_GET['isbn']) && isset($_GET['IDautore'])) {
        // DELETE FROM table_name WHERE condition; 
        try{
            $sql = "DELETE FROM libri_scritti_autore WHERE isbn = '".htmlspecialchars($_GET['isbn'])."' AND IDautore = ".htmlspecialchars($_GET['IDautore']).";";

            $results = $conn->query($sql);
        } catch (PDOException $e) {
             $_SESSION['author_list_error'] = "Impossibile eliminare l'autore.";
        }

    } else
        $_SESSION['author_list_error'] = "Dati non completi.";

    
    header("Location: ../insert_book.php#author_list");
    exit();

    
} catch (exception $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
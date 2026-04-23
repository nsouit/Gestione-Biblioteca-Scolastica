<!DOCTYPE html>

<?php
session_start();

if ($_SESSION['ruolo'] != 'Docente')
    header("Location: index.php");

try {
    include("../inc/connection/start.php");

    if (isset($_POST['register_book'])) {
        if (isset($_POST['nome_autore']))
            echo "hello";
        else
            echo "nope";
    }

} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
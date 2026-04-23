<!DOCTYPE html>

<?php
session_start();

if ($_SESSION['ruolo'] != 'Docente')
    header("Location: index.php");

try {
    include("inc/connection/start.php");
?>
<html>
<head>
    <?php
    include("support/head.php");
    ?>
    <link rel="stylesheet" href="css/book_insert.css">
</head>

<body>
    <?php
    include("nav_bar.php");
    ?>

    <?php

    // prendo i nomi delle casi editrici
    $sql = "SELECT * FROM casa_editrice;";
    $results = $conn->query($sql);
    $tab_ce = $results->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM genere;";
    $results = $conn->query($sql);
    $tab_gen = $results->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='main'>";
        echo "<div class='container'>";
            echo "<div class='book-box'>";
                echo "<form action='support/check_inserted_book.php' method='post'>";
                    echo "<div class='box_book_data'>";
                        echo "<input class='form_input' type='text' name='isbn' placeholder='ISBN' required>";
                        echo "<input class='form_input' type='text' name='titolo' placeholder='Titolo' required>";
                        echo "<input class='form_input' type='date' name='anno_pubblicazione' placeholder='Anno pubblicazione' required>";
                        echo "<input class='form_input' type='text' name='abstract' placeholder='(*) Abstract'>";

                        // casa editrice esistente
                        echo "<h4>Scegli una casa editrice esistente</h4>";
                        echo "<select class='form_select' name='casa_editrice'>";
                            echo "<option value='none'>-- Case editrici --</option>";
                            foreach ($tab_ce as $row) {
                                echo "<option value='$row[IDcasa_editrice]'>$row[nome_casa_editrice]</option>";
                            }
                        echo "</select>";

                        // casa editrice nuona
                        echo "<h4>Inserisci una nuova casa editrice</h4>";
                        echo "<input class='form_input' type='text' name='nome_casa_editrice' placeholder='Nuova casa editrice'>";


                        echo "<h4>Scegli un genere esistente</h4>";
                        echo "<select class='form_select' name='genere'>";
                            echo "<option value='none'>-- Generi --</option>";
                            foreach ($tab_gen as $row) {
                                echo "<option value='$row[IDgenere]'>$row[nome_genere]</option>";
                            }
                        echo "</select>";

                        echo "<h4>Inserisci un nuovo genere</h4>";
                        echo "<input class='form_input' type='text' name='nome_genere' placeholder='Nuovo genere'>";
                        
                    echo "</div>";

                    echo "<div class='box_autor_data'>";
                            $sql = "SELECT * FROM autore;";
                            $results = $conn->query($sql);
                            $tab_aut = $results->fetchAll(PDO::FETCH_ASSOC);

                            echo "<h4>Scegli un autore esistente</h4>";
                            echo "<select class='form_select' name='casa_editrice'>";
                                echo "<option value='none'>-- Autori --</option>";
                                foreach ($tab_aut as $row) {
                                    echo "<option value='$row[IDautore]'>$row[nome_autore] $row[cognome_autore]</option>";
                                }
                            echo "</select>";

                            echo "<h4>Inserisci un nuovo autore</h4>";
                            echo "<input class='form_input' type='text' name='nome_autore' placeholder='Nome autore'>";
                            echo "<input class='form_input' type='text' name='cognome_autore' placeholder='Cognome autore'>";
                            echo "<input class='form_input' type='date' name='data_nascita' placeholder='Data nascita' required>";
                            

                    echo "</div>";
                    echo "<button class='log_reg-btn' name='register_book' type='submit'>Registra libro</button>";
                echo "</form>";
            echo "</div>";
        echo "</div>";
    echo "</div>";

    ?>
   
</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
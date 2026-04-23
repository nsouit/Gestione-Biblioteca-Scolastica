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
                echo "<form action='check_inserted_book.php' method='post'>";
                    echo "<div class='box_book_data'>";
                        echo "<input class='form_input' type='text' name='isbn' placeholder='ISBN' required>";
                        echo "<input class='form_input' type='text' name='titolo' placeholder='Titolo' required>";
                        echo "<input class='form_input' type='date' name='anno_pubblicazione' placeholder='Anno pubblicazione' required>";
                        echo "<input class='form_input' type='text' name='abstract' placeholder='(*) Abstract'>";

                        echo "<select class='form_select' name='casa_editrice'>";
                            foreach ($tab_ce as $row) {
                                echo "<option value='$row[IDcasa_editrice]'>$row[nome_casa_editrice]</option>";
                            }
                        echo "</select>";

                        echo "<select class='form_select' name='genere'>";
                            foreach ($tab_gen as $row) {
                                echo "<option value='$row[IDgenere]'>$row[nome_genere]</option>";
                            }
                        echo "</select>";
                    echo "</div>";

                    echo "<div class='box_autor_data'>";
                            echo "<input class='form_input' type='text' name='nome_autore' placeholder='Nome autore' required>";
                            echo "<input class='form_input' type='text' name='nome_autore' placeholder='Nome autore' required>";
                            echo "<input class='form_input' type='date' name='anno_pubblicazione' placeholder='Anno pubblicazione' required>";

                    echo "</div>";
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
<!DOCTYPE html>

<?php
session_start();
try {
    include("inc/connection/start.php");
?>
<html>
<head>
    <?php
    include("support/head.php");
    ?>
    <link rel="stylesheet" href="css/book_detail.css">
</head>

<body>
    <?php
    include("nav_bar.php");
    
    if ($_SERVER["REQUEST_METHOD"] == "GET" AND isset($_GET["isbn"]) AND trim($_GET['isbn']) != "") {
        //echo "<h2>Risultati per: $_GET[isbn]</h2>";

        //$sql = "SELECT * FROM libro WHERE isbn = $_GET[isbn]";

        $sql = "SELECT * FROM ((((
            libro
            INNER JOIN genere
                USING (IDgenere))
                INNER JOIN casa_editrice
                    USING (IDcasa_editrice))
                    INNER JOIN libri_scritti_autore 
                        USING (isbn))
                        INNER JOIN autore
                            USING (IDautore)) WHERE isbn = $_GET[isbn]
        ";

        //echo $sql;

        //$conn->query($sql);

        $results = $conn->query($sql);

        $tab = $results->fetch();

        $n_libri = $results->rowCount();

        if ($n_libri > 0) {
            

            //echo var_dump($tab)."<br/>";


            
            echo "<div class='main'>";
                echo "<div class='container'>";
                    echo "<div class='book-box'>";

                        // colonna 1 - immagine
                        echo "<div class='box-image'>";
                            echo "<img src='https://covers.openlibrary.org/b/isbn/$tab[isbn]-L.jpg'/>";
                        echo "</div>";

                        echo "<div class='box-info'>";
                        // colonna 3 - prenotazione
                            //query per copie libro
                            echo "<h2 class='book_title'>$tab[titolo]</h2>";

                            $sql = "SELECT COUNT(*) AS qt FROM copia_libro WHERE isbn='$tab[isbn]';";

                            $results = $conn->query($sql);

                            $qt_libro = $results->fetch();

                            $sql = "SELECT COUNT(*) AS qt FROM copia_libro INNER JOIN stato_copia_libro USING(IDstato_libro) WHERE isbn='$tab[isbn]' AND stato='Disponibile';";

                            $results = $conn->query($sql);

                            $qt_libro_disp = $results->fetch();

                            echo "<div class='box-actions'>";
                            echo "<ul>";
                                if (!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] != 'Docente')
                                    echo "<a href='reserve.php?isbn=$tab[isbn]'><li id='prenota_btn'>Prenota</li></a>";
                                else
                                    echo "<a href='manage_book.php?isbn=$tab[isbn]'><li id='prenota_btn'>Gestisci</li></a>";

                                echo "<li>Copie totali: $qt_libro[qt]</li>
                                    <li>Disponibili: $qt_libro_disp[qt]</li>
                                </ul>";
                            echo "</div>";
                        
                            // colonna 2 - informazioni
                            echo "<div class='box-text'>";
                                echo "<p><b>Titolo e autore: </b>$tab[titolo], <a href='search.php?search_bar=$tab[nome_autore]+$tab[cognome_autore]'>$tab[nome_autore] $tab[cognome_autore]</a></p>";
                                echo "<p><b>Anno pubblicazione: </b>$tab[anno_pubblicazione]</p>";
                                echo "<p><b>Casa editrice: </b><a href='search.php?search_bar=$tab[nome_casa_editrice]'>$tab[nome_casa_editrice]</a></p>";
                                echo "<p><b>Genere: </b><a href='search.php?search_bar=$tab[nome_genere]'>$tab[nome_genere]</a></p>";
                                echo "<p><b>Abstract: </b>$tab[abstract]</p>";
                            echo "</div>";
                        echo "</div>";


                    echo "</div>";
                echo "</div>";
            echo "</div>";


        } else
             echo "<h3>Nessun libro trovato.</h3>";
    } else {
        echo "<h2>Ricerca non valida</h2>";
    }
    
    ?>

</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
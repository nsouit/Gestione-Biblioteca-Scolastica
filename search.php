<!DOCTYPE html>

<?php
try {
    include("inc/connection/start.php");
?>
<html>
<head>
    <?php
    include("support/head.php");
    ?>
    <link rel="stylesheet" href="css/card.css">
</head>

<body>
    <?php
    include("nav_bar.php");
    ?>

    <?php
    // check is search_bar is set
    if ($_SERVER["REQUEST_METHOD"] == "GET" AND isset($_GET["search_bar"])) {
        echo "<h2>Risultati per: $_GET[search_bar]</h2>";

        $sql = "";

        $key_worlds = explode(" ", $_GET["search_bar"]);

        $sql = "SELECT * FROM libro WHERE ";
        $sql_operation = "AND ";
        foreach ($key_worlds as $world) {
            $sql .= "titolo LIKE '%$world%' $sql_operation";
        }

        $sql = substr($sql, 0, -strlen($sql_operation));

        $sql = "SELECT * FROM ((((
            libro
            INNER JOIN genere
                USING (IDgenere))
                INNER JOIN casa_editrice
                    USING (IDcasa_editrice))
                    INNER JOIN libri_scritti_autore 
                        USING (isbn))
                        INNER JOIN autore
                            USING (IDautore))
        ";

        $sql = "SELECT *
                    FROM ($sql) AS l 
                    WHERE ";
        
        $sql_operation = "AND ";
        foreach ($key_worlds as $world) {
            $sql .= " titolo LIKE '%$world%' $sql_operation";
        }

        $sql = substr($sql, 0, -strlen($sql_operation));

        $sql_operation = "OR";

        foreach ($key_worlds as $world) {
            $sql .= " $sql_operation nome_genere LIKE '%$world%'";
            $sql .= " $sql_operation nome_autore LIKE '%$world%'";
            $sql .= " $sql_operation cognome_autore LIKE '%$world%'";
            $sql .= " $sql_operation nome_casa_editrice LIKE '%$world%'";
        }
        

        //$sql = substr($sql, 0, -strlen($sql_operation));


        //echo $sql;

        $conn->query($sql);

        $results = $conn->query($sql);

        $n_libri = $results->rowCount();

        if ($n_libri > 0) {
            if ($n_libri > 1)
                echo "<h3>$n_libri libri trovati.</h3>";
            else
                echo "<h3>$n_libri libro trovato.</h3>";

            // la query ha trovato riscontri
            $tab = $results->fetchAll(PDO::FETCH_ASSOC);

            //echo var_dump($tab);

            $keys = array_keys($tab[0]);

            //echo var_dump($keys);
           
            /*echo "<table>";

            echo "<tr>";
            foreach ($keys as $key)
                echo "<th>$key</th>";
            echo "</tr>";


            foreach ($tab as $row) {
                echo "<a href='index.php'><tr>";
                for ($i = 0; $i < count($keys); $i++) {
                    echo "<td>".$row[$keys[$i]]."</td>";
                }
                    
                echo "</tr></a>";
            }


            echo "</table>";*/

            foreach ($tab as $row) {
                //echo "<a href='book.php/?isbn=$row[isbn]'>";
                echo "<div class='main'>";
                    echo "<div class='container'>";

                        echo "<a href='book.php?isbn=$row[isbn]'>";

                        echo "<div class='book-box'>";
                            echo "<div class='box-image'>";
                                echo "<img src='https://covers.openlibrary.org/b/isbn/$row[isbn]-S.jpg' alt='logo'/>";
                            echo "</div>";
                            echo "<div class='box-text'>";
                                echo "<h2>$row[nome_autore] $row[cognome_autore]</h2>";
                                echo "<h3>$row[titolo]</h3>";
                                echo "<p>$row[nome_casa_editrice], $row[anno_pubblicazione]</p>";
                            echo "</div>";
                        echo "</div>";

                        echo "</a>";

                    echo "</div>";
                echo "</div>";
                //echo "</a>";
            }

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
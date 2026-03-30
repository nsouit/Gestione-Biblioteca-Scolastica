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
</head>

<body>
    <?php
    include("nav_bar.php");
    ?>

    <?php
    // check is search_bar is set
    if ($_SERVER["REQUEST_METHOD"] == "GET" AND isset($_GET["search_bar"]) AND trim($_GET['search_bar']) != "") {
        echo "<h2>Risultati per: $_GET[search_bar]</h2>";

        $key_worlds = explode(" ", $_GET["search_bar"]);

        $sql = "SELECT * FROM libro WHERE ";
        $sql_operation = "AND ";
        foreach ($key_worlds as $world) {
            $sql .= "titolo LIKE '%$world%' $sql_operation";
        }

        $sql = substr($sql, 0, -strlen($sql_operation));

        //$sql .= ";";

        // cambio l'id del genere mettendone il nome
        /*$sql = "SELECT 
                    libri.isbn,
                    libri.titolo,
                    libri.anno_pubblicazione,
                    libri.casa_editrice,
                    genere.nome AS nome_genere
                FROM ($sql) AS libri
                INNER JOIN genere
                    ON libri.genere = genere.IDgenere";

        //IDcasa_editrice
        // cambio l'id della casa editrice
        $sql = "SELECT
                    libri.isbn,
                    libri.titolo,
                    libri.anno_pubblicazione,
                    libri.nome_genere,
                    casa_editrice.nome AS nome_casa_editrice
                FROM ($sql) AS libri
                INNER JOIN casa_editrice
                    ON libri.casa_editrice = casa_editrice.IDcasa_editrice";

        $sql = "SELECT
                    libri.isbn,
                    libri.titolo,
                    libri.anno_pubblicazione,
                    libri.nome_genere,
                    libri.nome_casa_editrice,
                    libri_scritti_autore.IDautore
                FROM ($sql) AS libri
                INNER JOIN libri_scritti_autoreINNER JOIN libri_scritti_autore
                    ON libri.isbn = libri_scritti_autore.isbn";

        $sql = "SELECT
                    libri.isbn,
                    libri.titolo,
                    libri.anno_pubblicazione,
                    libri.nome_genere,
                    libri.nome_casa_editrice,
                    autore.nome
                FROM ($sql) AS libri
                INNER JOIN autore
                    ON libri.IDautore = autore.IDautore";
        */

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

        $sql = "SELECT titolo, nome_casa_editrice, anno_pubblicazione, nome_genere
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


        echo $sql;

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
           
            echo "<table>";

            echo "<tr>";
            foreach ($keys as $key)
                echo "<th>$key</th>";
            echo "</tr>";


            foreach ($tab as $row) {
                echo "<tr>";
                for ($i = 0; $i < count($keys); $i++) {
                    echo "<td>".$row[$keys[$i]]."</td>";
                }
                    
                echo "</tr>";
            }


            echo "</table>";

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
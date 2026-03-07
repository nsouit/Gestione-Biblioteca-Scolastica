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
    if (isset($_GET["search_bar"])) {
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
        $sql = "SELECT 
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
                    casa_editrice.nome,
                    libri.nome_genere
                FROM ($sql) AS libri
                INNER JOIN casa_editrice
                    ON libri.casa_editrice = IDcasa_editrice";

        //echo $sql;

        $conn->query($sql);

        $results = $conn->query($sql);

        $n_libri = $results->rowCount();

        echo "<h3>$n_libri libri trovati.</h3>";

        if ($n_libri > 0) {
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
                for ($i = 0; $i < count($keys); $i++)
                    echo "<td>".$row[$keys[$i]]."</td>";
                echo "</tr>";
            }
            echo "</table>";

        }
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
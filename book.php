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
    
    if ($_SERVER["REQUEST_METHOD"] == "GET" AND isset($_GET["catalog"]) AND trim($_GET['catalog']) != "") {
        echo "<h2>Risultati per: $_GET[catalog]</h2>";

        $sql = "SELECT * FROM libro WHERE isbn = $_GET[catalog]";

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
            $tab = $results->fetch();

            //echo var_dump($tab);

            $keys = array_keys($tab);
           
            echo "<table>";

            echo "<tr>";
            foreach ($keys as $key)
                echo "<th>$key</th>";
            echo "</tr>";


            for ($i = 0; i < count($tab); $i++) {
                echo "<td>".$tab[$i]."</td>";
            }

            //

            echo "</table>";

            echo var_dump($tab);

            echo "<img src='https://covers.openlibrary.org/b/isbn/9788806229095-L.jpg' />";

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
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

    //include("support/print_db_data.php");
    ?>

    <div class="datiDB">
    <?php
    $sql = "SELECT COUNT(*) FROM prestito WHERE data_fine_effettiva IS NOT NULL;";
    $results = $conn->query($sql);
    if ($results->rowCount() >= 0) {
        $tab = $results->fetch();
        echo "<p>Prestiti in corso: ".$tab[0]."</p>";
    }

    echo "\t";

    $sql = "SELECT COUNT(*) FROM utente;";
    $results = $conn->query($sql);
    if ($results->rowCount() >= 0) {
        $tab = $results->fetch();
        echo "<p>Utenti attivi: ".$tab[0]."</p>";
    }
    ?>
    </div>

    <h2>Libri disponibili:</h2>

    <?php
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

        $sql = "SELECT titolo AS Titolo, nome_casa_editrice AS `Casa Editrice`, anno_pubblicazione AS Anno, nome_genere AS Genere
                    FROM ($sql) AS l";

    $results = $conn->query($sql);

    if ($results->rowCount() > 0) {
        $tab = $results->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($tab);
        $keys = array_keys($tab[0]);

        echo "<table>";
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

        echo "</table>";

    } else {
        echo "<p>Nessun libro disponibile</p>";
    }

    ?>


</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
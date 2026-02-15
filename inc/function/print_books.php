<?php
try {
    include("../connection/start.php");

    $sql = "SELECT ISBN, titolo AS Titolo, annoPubblicazione AS Anno, genere AS Genere FROM libro;";
    $results = $conn->query($sql);
    if ($results->rowCount() <= 0)
        die("Non ci sono libri in elenco.");

    $tab = $results->fetchAll(PDO::FETCH_ASSOC);

    $keys = array_keys($tab[0]);
    /*foreach ($keys as $key)
        echo "<p>".$key."</p>";*/
?>

<table>
    <tr class="table_head">
        <?php
        foreach ($keys as $key)
            echo "<th>".$key."</th>";
        ?>
    </tr>
    
    
    <?php
    $i = 0;
    foreach ($tab as $row) {
        echo "<tr";
        if ($i % 2 == 0)
            echo " class='rigaAlternata'";
        echo ">";
        echo "<td><a href='visualizza_libro.php/=?".$row['ISBN']."'>".$row['ISBN']."</a></td>";
        echo "<td>".$row['Titolo']."</td>";
        echo "<td>".$row['Anno']."</td>";
        echo "<td>".$row['Genere']."</td>";
        echo "</tr>";
        $i++;
    }
    ?>
    
    
</table>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
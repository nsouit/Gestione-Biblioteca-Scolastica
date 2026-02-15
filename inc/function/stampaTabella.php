<?php
    // automatizza la stampa di una tabella data la query all'interno della variabile $sql
    if (!isset($sql))
        die ("<h2 style='color:red;'>Errore: query mancante</h3>");

    $results = $conn->query($sql);
    
    if ($results->rowCount() < 1)
        echo "La query non ha prodotto risultati";
    else {
        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

        $chiavi = array_keys($tab[0]);

        echo "<table>";
        echo "<tr>";

        //scorro tutte le chiavi e stampo la riga d'intestazione della tabella
        foreach($chiavi as $k)
            echo "<th>$k</th>";
        echo "</tr>";

        $i = 0;
        // ora stampo la tabella
       
        foreach ($tab as $riga) {
            echo "<tr";
            if ($i%2==0)
                echo " class='rigaAlternata'";
            echo ">";
            foreach ($chiavi as $k)
                echo "<td>$riga[$k]</td>";
            echo "</tr>";
            $i++;
        }
        echo "</table>";
    }
?>
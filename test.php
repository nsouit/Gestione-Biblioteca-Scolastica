<?php
try {
    include("inc/connection/start.php");
?>

<?php

$sql = "
SELECT l.isbn, l.titolo, a.nome, a.cognome
FROM libro l
INNER JOIN autore a
ON l.autore = a.IDAutore
ORDER BY l.id;
";

$results = $conn->query($sql);

?>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
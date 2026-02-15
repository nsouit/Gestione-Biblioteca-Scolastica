<?php
include("inc/datiConnessione.php");
try {
    include("inc/startConn.php");

    $sql = "SELECT * FROM libro WHERE 1=1";
    $results = $conn->query($sql);
    if ($results->rowCount() <= 0)
        die("Non ci sono libri in elenco.");

    $tab = $results->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
    <
</table>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
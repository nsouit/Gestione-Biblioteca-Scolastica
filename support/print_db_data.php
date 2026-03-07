<?php
try {
    include("inc/connection/start.php");

    $sql = "SELECT COUNT(*) FROM utente;";

    $results = $conn->query($sql);

    if ($results->rowCount() > 0) {
        $data = $results.fetchAll(FETCH_ASSOC);

        echo $data;
    }

} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
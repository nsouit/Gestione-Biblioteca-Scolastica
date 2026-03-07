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



</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
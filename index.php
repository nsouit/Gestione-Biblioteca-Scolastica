<!DOCTYPE html>

<?php
include("inc/datiConnessione.inc");
try {
    include("inc/startConn.inc");
?>

<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Biblioteca Galattica</title>
    <link rel="icon" type="image/x-icon" href="img/book.ico">
</head>

<body>
    <?php
    include("inc/navBar.inc");
    ?>

    <ul class="login">
        <li><a href="login.php">Login</a></li>
    </ul>
    
    <div class="datiDB">
    <?php
    $sql = "SELECT COUNT(*) AS 'cont' FROM prestito WHERE dataFineEffettiva IS NOT NULL;";
    $results = $conn->query($sql);
    if ($results->rowCount() >= 0) {
        $tab = $results->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>Prestiti in corso: ".$tab[0]['cont']."</p>";
    }

    echo "\t";

    $sql = "SELECT COUNT(*) AS 'iscr' FROM utente;";
    $results = $conn->query($sql);
    if ($results->rowCount() >= 0) {
        $tab = $results->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>Utenti attivi: ".$tab[0]['iscr']."</p>";
    }
    ?>
    </div>
</body>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
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
    <header>
        
    </header>

    
</body>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
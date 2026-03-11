<!DOCTYPE html>

<?php
session_start();

if (!isset($_SESSION["nome"]) || !isset($_SESSION["email"]) || !isset($_SESSION["ruolo"]))
    header("location: index.php");

try {
    include("inc/connection/start.php");
?>


<html>
<head>
    <?php include("support/head.php");?>
</head>
<body>
    <?php
    include("nav_bar.php");
    ?>

    
    
</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
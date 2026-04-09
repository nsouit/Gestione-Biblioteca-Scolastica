<!DOCTYPE html>

<?php
session_start();

if ($_SESSION['ruolo'] != 'Docente')
    header("Location: index.php");

try {
    include("inc/connection/start.php");
?>
<html>
<head>
    <?php
    include("support/head.php");
    ?>
    <link rel="stylesheet" href="css/book_insert.css">
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
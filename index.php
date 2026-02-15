<!DOCTYPE html>

<?php
try {
    include("inc/connection/start.php");
?>

<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Biblioteca Galattica</title>
    <link rel="icon" type="image/x-icon" href="img/book.ico">
</head>

<body>
    <?php
    include("inc/function/nav_bar.php");
    ?>

</body>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
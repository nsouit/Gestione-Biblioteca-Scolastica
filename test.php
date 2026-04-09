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
    ?>

    <div class="book_card">
        <img src="img/book.png">
        <section>
            <h3>Test</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni, dicta minima! Quae quia ducimus cumque rerum recusandae numquam mollitia placeat doloribus eligendi. Alias autem officia voluptatem adipisci officiis perferendis distinctio.</p>
        </section>
    </div>
    

</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
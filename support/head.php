<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="css/style.css">

<title>
    <?php
        //session_start();
        if (isset($_SESSION["nome"]))
            echo $_SESSION["nome"]. " - Biblioteca Galattica";
        else
            echo "Biblioteca Galattica";
    ?>
</title>

<link rel="icon" type="image/x-icon" href="img/book.ico">

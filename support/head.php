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

<!---
-- Latest compiled and minified CSS --
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

-- jQuery library --
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

-- Latest compiled JavaScript --
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
-->
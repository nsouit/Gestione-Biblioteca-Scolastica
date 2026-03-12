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
    <link rel="stylesheet" href="css/card.css">
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

    <h2>Novità</h2>

    <?php
    $sql = "SELECT * FROM ((((
            libro
            INNER JOIN genere
                USING (IDgenere))
                INNER JOIN casa_editrice
                    USING (IDcasa_editrice))
                    INNER JOIN libri_scritti_autore 
                        USING (isbn))
                        INNER JOIN autore
                            USING (IDautore))
        ";

    $results = $conn->query($sql);

    if ($results->rowCount() > 0) {
        $tab = $results->fetchAll(PDO::FETCH_ASSOC);
        //echo var_dump($tab);
        $keys = array_keys($tab[0]);
        /*
        echo "<table>";
        echo "<tr>";
        foreach ($keys as $key)
            echo "<th>$key</th>";
        echo "</tr>";

        foreach ($tab as $row) {
            echo "<a href='index.php'><tr>";
            for ($i = 0; $i < count($keys); $i++) {
                echo "<td>".$row[$keys[$i]]."</td>";
            }
                
            echo "</tr></a>";
        }

        echo "</table>";
        */

        /*
         <div class="main">
            <div class="container">
                <div class="form-box active" id="login-form">
                    <form id="loginForm" action="support/check_user_data.php" method="post">
                        <h2>Login</h2>
                        <input class="form_input" type="email" name="email" placeholder="E-mail" required>
                        <input id="pwd" class="form_input" type="password" name="passwd" placeholder="Password" minlength="8" required>
                        <button class="log_reg-btn" type="submit" name="login">Login</button>
                    </form>
                </div>
            </div>
        </div>
         */

        //echo var_dump($keys);

        foreach ($tab as $row) {
            //echo "<a href='book.php/?isbn=$row[isbn]'>";
            echo "<div class='main'>";
                echo "<div class='container'>";

                    echo "<a href='index.php'>";

                    echo "<div class='book-box'>";
                        echo "<div class='box-image'>";
                            echo "<img src='https://covers.openlibrary.org/b/isbn/$row[isbn]-S.jpg' alt='logo'/>";
                        echo "</div>";
                        echo "<div class='box-text'>";
                            echo "<h2>$row[nome_autore] $row[cognome_autore]</h2>";
                            echo "<h3>$row[titolo]</h3>";
                            echo "<p>$row[nome_casa_editrice], $row[anno_pubblicazione]</p>";
                        echo "</div>";
                    echo "</div>";

                    echo "</a>";

                echo "</div>";
            echo "</div>";
            //echo "</a>";
        }
        


    } else {
        echo "<p>Nessun libro disponibile</p>";
    }

    ?>


</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
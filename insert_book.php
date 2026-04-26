<!DOCTYPE html>

<?php
session_start();

if ($_SESSION['ruolo'] != 'Docente') {
    header("Location: index.php");
    exit();
}

$errors = [
    'register' => $_SESSION['register_error'] ?? '',
    'delete' => $_SESSION['delete_error'] ?? '',
    'add_author' => $_SESSION['add_author_error'] ?? '',
    'author_list' => $_SESSION['author_list_error'] ?? '',
    'edit_book' => $_SESSION['edit_book_error'] ?? '',
];

unset($_SESSION['register_error']);
unset($_SESSION['delete_error']);
unset($_SESSION['add_author_error']);
unset($_SESSION['author_list_error']);
unset($_SESSION['edit_book_error']);
//edit_book_error

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

try {
    include("inc/connection/start.php");
?>
<html>
<head>
    <?php
    include("support/head.php");
    ?>
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <?php
    include("nav_bar.php");
    ?>

    <?php

    // prendo i nomi delle casi editrici
    $sql = "SELECT * FROM casa_editrice;";
    $results = $conn->query($sql);
    $tab_ce = $results->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM genere;";
    $results = $conn->query($sql);
    $tab_gen = $results->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/check_inserted_book.php" method="post">
                    <h2>Registra libro</h2>
                    <?php echo (showError($errors['register'])) ?>
                    <input class="form_input" type="text" name="isbn" placeholder="ISBN" required>
                    
                    <input class="form_input" type="text" name="titolo" placeholder="Titolo" required>
                    
                    <label>Anno di pubblicazione:</label>
                    <input class="form_input" type="number" name="anno_pubblicazione" value='<?php echo date("Y") ?>' required>
                    
                    <label>Casa editrice:</label>
                    <select class="form_input" name="casa_editrice" required>
                        <?php
                            $sql = "SELECT * FROM casa_editrice;";

                            $results = $conn->query($sql);

                            if ($results->rowCount() > 0) {
                                $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                                foreach($tab as $row)
                                    echo "<option value='".$row['IDcasa_editrice']."'>".$row['nome_casa_editrice']."</option>";
                            }
                        ?>
                    </select>
                    
                    <label>Genere:</label>
                    <select class="form_input" name="genere" required>
                        <?php
                            $sql = "SELECT * FROM genere;";

                            $results = $conn->query($sql);

                            if ($results->rowCount() > 0) {
                                $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                                foreach($tab as $row)
                                    echo "<option value='".$row['IDgenere']."'>".$row['nome_genere']."</option>";
                            }
                        ?>
                    </select>

                    <label>Autore:</label>
                    <select class="form_input" name="autore" required>
                        <?php
                            $sql = "SELECT * FROM autore;";

                            $results = $conn->query($sql);

                            if ($results->rowCount() > 0) {
                                $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                                foreach($tab as $row)
                                    echo "<option value='".$row['IDautore']."'>".$row['nome_autore']." ".$row['cognome_autore']."</option>";
                            }
                        ?>
                    </select>

                    <label>(*) Abstract:</label>
                    <textarea class='form_input' rows='8' style='resize: none;' name='abstract' placeholder='Abstract'></textarea>

                    <button class="log_reg-btn" type="submit" name="register">Registra</button>
                </form>
            </div>
        </div>
    </div>


                        <!--MODIFICA-->

    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="edit_book.php" method="post">
                    <h2>Modifica libro</h2>

                    <?php
                    echo (showError($errors['edit_book']));
                    $sql = "SELECT * FROM libro;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<label>Seleziona libro:</label>";
                        echo "<select class='form_input' name='libro'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($tab as $row)
                            echo "<option value='$row[isbn]'>$row[titolo], $row[anno_pubblicazione]</option>";
                        echo "</select>";
                    }
                    else
                        echo "<p>Nessun libro in elenco.</p>";

                    ?>
                    <button class="log_reg-btn" type="submit" name="edit">Modifica</button>
                </form>
            </div>
        </div>
    </div>

     <!-- elenco autori libro -->
    <div class="main" id='author_list'>
        <div class="container">
            <div class="form-box active">
                <form action="insert_book.php#author_list" method="post">
                    <h2>Gestisci autori libro</h2>

                    <?php
                    echo (showError($errors['author_list']));

                    $sql = "SELECT * FROM libro;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<label>Seleziona libro:</label>";
                        echo "<select class='form_input' name='libro'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($tab as $row)
                            echo "<option value='$row[isbn]'>$row[titolo], $row[anno_pubblicazione]</option>";
                        echo "</select>";
                    }
                    else
                        echo "<p>Nessun libro in elenco.</p>";

                    ?>
                    <button class="log_reg-btn" type="submit" name="author_list">Ottieni elenco autori</button>
                </form>

                <?php
                // controllo se è stata effettuata una richiesta e mostro i risultati
                if (isset($_POST["libro"])) {
                    $sql = "SELECT * FROM libro WHERE isbn = '$_POST[libro]';";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        $row_libro = $results->fetch();


                        echo "<label>Elenco autori per '$row_libro[titolo]':</label>";
                        
                        // isbn --> libri scritti autore --> id autore --> autore --> stampo
                        $sql = "SELECT * FROM libri_scritti_autore WHERE isbn = '$row_libro[isbn]';";

                        $results = $conn->query($sql);

                        if ($results->rowCount() > 0) {
                            $tab_autori = $results->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($tab_autori as $row_lib_aut) {
                                $sql = "SELECT * FROM autore WHERE IDautore = $row_lib_aut[IDautore];";

                                $results = $conn->query($sql);

                                if ($results->rowCount() > 0) {
                                    // ho l'autore, lo stampo
                                    $row_autore = $results->fetch();
                                    echo "<div class='book_auth_management'>";
                                        echo "<p class='autori'><a href='search.php?search_bar=$row_autore[nome_autore] $row_autore[cognome_autore]'>$row_autore[nome_autore] $row_autore[cognome_autore]</a></p>";

                                        echo "<p class='delete'><a href='support/remove_book_author.php?isbn=$row_libro[isbn]&IDautore=$row_autore[IDautore]'>Elimina</a></p>";
                                    echo "</div>";
                                }
                            }
                        } else
                            echo "<p>Nessun autore presente.</p>";
                    }


                }



                ?>
            </div>
        </div>
    </div>

    <!--aggiiungi autore libro-->
    <div class="main" id="add_author">
        <div class="container">
            <div class="form-box active">
                <form action="support/check_inserted_book.php" method="post">
                    <h2>Aggiungi autore</h2>

                    <?php
                    echo (showError($errors['add_author']));

                    $sql = "SELECT * FROM libro;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<label>Libro:</label>";
                        echo "<select class='form_input' name='libro'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($tab as $row)
                                echo "<option value='$row[isbn]'>$row[titolo], $row[anno_pubblicazione]</option>";
                        echo "</select>";
                    }
                    else
                        echo "<p>Nessun libro in elenco.</p>";
                    

                    $sql = "SELECT * FROM autore;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<label>Autore:</label>";
                        echo "<select class='form_input' name='autore'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($tab as $row)
                                echo "<option value='$row[IDautore]'>$row[nome_autore] $row[cognome_autore]</option>";
                        echo "</select>";
                    }
                    else
                        echo "<p>Nessun autore in elenco.</p>";
                    ?>
                    <button class="log_reg-btn" type="submit" name="add_author">Aggiungi</button>
                </form>
            </div>
        </div>
    </div>


                        <!-- Eliminazione libro -->

    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/check_inserted_book.php" method="post">
                    <h2>Elimina libro</h2>

                    <?php
                    echo (showError($errors['delete']));

                    $sql = "SELECT * FROM libro;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<label>Seleziona libro:</label>";
                        echo "<select class='form_input' name='libro'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($tab as $row)
                            echo "<option value='$row[isbn]'>$row[titolo], $row[anno_pubblicazione]</option>";
                        echo "</select>";
                    }
                    else
                        echo "<p>Nessun libro in elenco.</p>";

                    ?>
                    <button class="log_reg-btn" type="submit" name="delete">Elimina</button>
                </form>
            </div>
        </div>
    </div>

   
   
</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
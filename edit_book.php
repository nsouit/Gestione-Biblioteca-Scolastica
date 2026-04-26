<!DOCTYPE html>

<?php
session_start();

if ($_SESSION['ruolo'] != 'Docente') {
    header("Location: index.php");
    exit();
}

$errors = [
    'edit_book' => $_SESSION['edit_book_error'] ?? '',
];

unset($_SESSION['edit_book_error']);
//author_list

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

try {
    include("inc/connection/start.php");
    include("inc/db_tables_params.php");

    if (!checkSetNoErrors("libro")) {
        header("Location: insert_book.php");
        exit();
    }
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

    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/check_inserted_book.php" method="post">
                    <h2>Modifica libro</h2>
                    <?php
                    echo (showError($errors['edit_book']));

                    
                    $sql = "SELECT * FROM libro WHERE isbn = '$_POST[libro]';";

                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        $row_libro = $results->fetch();

                        echo "<input class='form_input' type='text' name='isbn' placeholder='ISBN' value='$row_libro[isbn]' readonly>";

                        echo "<input class='form_input' type='text' name='titolo' placeholder='Titolo' value='$row_libro[titolo]' required>";

                        echo "<label>Anno di pubblicazione:</label>";

                        echo "<input class='form_input' type='number' name='anno_pubblicazione' value='$row_libro[anno_pubblicazione]' required>";

                        echo "<label>Casa editrice:</label>";
                        echo "<select class='form_input' name='casa_editrice' required>";
                        
                        $sql = "SELECT * FROM casa_editrice;";

                        $results = $conn->query($sql);

                        if ($results->rowCount() > 0) {
                            $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                            foreach($tab as $row) {
                                echo "<option value='$row[IDcasa_editrice]' ";
                                if ($row['IDcasa_editrice'] === $row_libro['IDcasa_editrice'])
                                    echo "selected";
                                echo ">$row[nome_casa_editrice]</option>";
                            }
                        }

                        echo "</select>";

                        echo "<label>Genere:</label>";
                        echo " <select class='form_input' name='genere' required>";
                        $sql = "SELECT * FROM genere;";

                        $results = $conn->query($sql);

                        if ($results->rowCount() > 0) {
                            $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                            foreach($tab as $row) {
                                echo "<option value='$row[IDgenere]' ";
                                if ($row['IDgenere'] === $row_libro['IDgenere'])
                                    echo "selected";
                                echo ">$row[nome_genere]</option>";
                            }
                        }

                        echo "</select>";


                        echo "<label>(*) Abstract:</label>";
                        echo "<textarea class='form_input' rows='8' style='resize: none;' name='abstract' placeholder='Abstract'>";
                        echo $row_libro['abstract'];
                        echo "</textarea>";

                    } else {
                        $_SESSION['edit_book_error'] = "Dati non validi.";
                        header("Location: insert_book.php");
                        exit();
                    }
                    ?>
                    <button class="log_reg-btn" type="submit" name="edit_book">Modifica</button>

                    
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
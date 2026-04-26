<!DOCTYPE html>

<?php
session_start();

if ($_SESSION['ruolo'] != 'Docente') {
    header("Location: index.php");
    exit();
}

$errors = [
    'register' => $_SESSION['register_error'] ?? '',
    'delete' => $_SESSION['delete_error'] ?? ''
];

unset($_SESSION['register_error']);
unset($_SESSION['delete_error']);

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
                    
                    <lable>Anno di pubblicazione:</lable>
                    <input class="form_input" type="number" name="anno_pubblicazione" value='<?php echo date("Y") ?>' required>
                    
                    <lable>Casa editrice:</lable>
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
                    
                    <lable>Genere:</lable>
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

                    <lable>Autore:</lable>
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

                    <input class="form_input" type="text" name="abstract" placeholder="(*) Abstract">

                    <button class="log_reg-btn" type="submit" name="register">Registra</button>
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
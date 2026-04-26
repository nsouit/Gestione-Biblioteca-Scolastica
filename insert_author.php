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
    'modify' => $_SESSION['modify_error'] ?? '',
];

unset($_SESSION['register_error']);
unset($_SESSION['delete_error']);
unset($_SESSION['modify_error']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}
?>

<?php
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

    <!--INSERISCI-->
    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/check_author_data.php" method="post">
                    <h2>Inserisci autore</h2>
                    <?php echo (showError($errors['register'])) ?>

                    <input class="form_input" type="text" name="nome_autore" placeholder="Nome" required>
                    <input class="form_input" type="text" name="cognome_autore" placeholder="Cognome" required>
                    <lable>Data di nascita:</lable>
                    <input class="form_input" type="date" name="data_nascita" placeholder="Data di nascita" required>
                    <button class="log_reg-btn" type="submit" name="register">Registra</button>
                </form>
            </div>
        </div>
    </div>

    <!--ELIMINA-->
    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/check_author_data.php" method="post">
                    <h2>Elimina autore</h2>

                    <?php
                    echo (showError($errors['delete']));

                    $sql = "SELECT * FROM autore;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<select class='form_input' name='autore'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($tab as $row)
                                echo "<option value='$row[IDautore]'>$row[nome_autore] $row[cognome_autore]</option>";
                        echo "</select>";
                    }
                    else
                        echo "<p>Nessun autore in elenco.</p>";
                    ?>
                    <button class="log_reg-btn" type="submit" name="delete">Elimina</button>
                </form>
            </div>
        </div>
    </div>
    
    <!--MODIFICA-->
    <div class="main" id='modify'>
        <div class="container">
            <div class="form-box active">
                <form action="support/check_author_data.php" method="post">
                    <h2>Modifica autore</h2>
                    <?php echo (showError($errors['modify'])) ?>
                    <?php
                    echo (showError($errors['delete']));

                    $sql = "SELECT * FROM autore;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<select class='form_input' name='autore'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($tab as $row)
                                echo "<option value='$row[IDautore]'>$row[nome_autore] $row[cognome_autore], $row[data_nascita_autore]</option>";
                        echo "</select>";

                        echo "<input class='form_input' type='text' name='nome_autore' placeholder='Nome' required>";
                        echo "<input class='form_input' type='text' name='cognome_autore' placeholder='Cognome' required>";
                        echo "<lable>Data di nascita:</lable>";
                        echo "<input class='form_input' type='date' name='data_nascita' required>";
                    }
                    else
                        echo "<p>Nessun autore in elenco.</p>";
                    ?>                    
                    
                    <button class="log_reg-btn" type="submit" name="modify">Modifica</button>
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
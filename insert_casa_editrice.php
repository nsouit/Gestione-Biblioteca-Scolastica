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

    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/check_casa_editrice_data.php" method="post">
                    <h2>Inserisci casa editrice</h2>
                    <?php echo (showError($errors['register'])) ?>
                    <input class="form_input" type="text" name="nome_casa_editrice" placeholder="Nome casa editrice" required>
                    <button class="log_reg-btn" type="submit" name="register">Registra</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/check_casa_editrice_data.php" method="post">
                    <h2>Elimina</h2>

                    <?php
                    echo (showError($errors['delete']));

                    $sql = "SELECT * FROM casa_editrice;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<select class='form_input' name='casa_editrice'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($tab as $row)
                            echo "<option value='$row[IDcasa_editrice]'>$row[nome_casa_editrice]</option>";
                        echo "</select>";
                    }
                    else
                        echo "<p>Nessuna casa editrice in elenco.</p>";

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
                <form action="support/check_casa_editrice_data.php" method="post">
                    <h2>Modifica</h2>
                    <?php echo (showError($errors['modify']));

                    $sql = "SELECT * FROM casa_editrice;";
                    
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        echo "<select class='form_input' name='casa_editrice'>";

                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($tab as $row)
                                echo "<option value='$row[IDcasa_editrice]'>$row[nome_casa_editrice]</option>";
                        echo "</select>";

                        echo "<input class='form_input' type='text' name='nome_casa_editrice' placeholder='Nome casa editrice' required>";
                    }
                    else
                        echo "<p>Nessuna casa editrice in elenco.</p>";
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
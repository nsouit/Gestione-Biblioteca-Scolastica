<!DOCTYPE html>

<?php
session_start();

if ($_SESSION['ruolo'] != 'Docente') {
    header("Location: index.php");
    exit();
}

$errors = [
    'register' => $_SESSION['register_error'] ?? ''
];

unset($_SESSION['register_error']);

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
    <link rel="stylesheet" href="css/form.css">

</head>

<body>
    <?php
    include("nav_bar.php");
    ?>

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
    

</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
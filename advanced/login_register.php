<?php
session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];

$active_form = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm) {
    return ($formName === $activeForm) ? 'active' : '';
}

function showForm($formName) {
    $_SESSION['active_form'] = $formName;
}
?>

<?php
//echo $errors['login'];
//echo $errors['register'];
//echo strlen($active_form);
try {
    include("../inc/connection/start.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Autenticazione</title>
</head>
<body>
    <div class="container">
        <!--inserisco sia login che registrati, poi li attivo o disattivo con js-->
        <div class="form-box <?php echo isActiveForm('login', $active_form) ?>" id="login-form">
            <form action="check_user_data.php" method="post">
                <h2>Login</h2>
                <?php echo (showError($errors['login'])) ?>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="passwd" placeholder="Password" minlength="6" required>
                <button type="submit" name="login">Login</button>
                <p>Non hai un account? <a href="change_login.php?form=register">Registrati</a>.</p>
            </form>
        </div>

        <div class="form-box <?php echo isActiveForm('register', $active_form) ?>" id="register-form">
            <form action="check_user_data.php" method="post">  
                <h2>Registrati</h2>
                <?php echo (showError($errors['register'])) ?>
                <input type="text" name="cf" placeholder="Codice Fiscale" required>
                <input type="text" name="nome" placeholder="Nome" required>
                <input type="text" name="cnome" placeholder="Cognome" required>
                <lable>Data di nascita:</lable>
                <input type="date" name="datan" placeholder="Data di nascita" required>
                <lable>Tipologia utente:</lable>
                <select name="tipo" required>
                    <?php
                        $sql = "SELECT * FROM tipo_utente;";

                        $results = $conn->query($sql);

                        if ($results->rowCount() > 0) {
                            $tab = $results->fetchAll(PDO::FETCH_ASSOC);

                            foreach($tab as $row)
                                echo "<option value='".$row['IDtipo']."'>".$row['tipo']."</option>";
                        }
                    ?>
                </select>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="passwd" placeholder="Password" minlength="6" required>
                <input type="password" name="passwd_conf" placeholder="Conferma password" minlength="6" required>
                <button type="submit" name="register">Registrati</button>
                <p>Hai un account? Effettua il <a href="change_login.php?form=login">Login</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>



<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
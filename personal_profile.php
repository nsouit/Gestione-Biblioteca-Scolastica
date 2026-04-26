<!DOCTYPE html>

<?php
session_start();

if (!isset($_SESSION["IDutente"]) || !isset($_SESSION["nome"]) || !isset($_SESSION["email"]) || !isset($_SESSION["ruolo"])) {
    header("location: index.php");
    exit();
}


$errors = [
    'update_data' => $_SESSION['update_error'] ?? ''
];

unset($_SESSION['update_error']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}


try {
    include("inc/connection/start.php");
?>


<html>
<head>
    <?php include("support/head.php");?>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <?php
    include("nav_bar.php");
    ?>

    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/update_user_data.php" method="post">
                    <h2>Area personale</h2>
                    <?php echo (showError($errors['update_data'])) ?>
                    <?php
                    $sql = "SELECT * FROM utente WHERE IDutente = $_SESSION[IDutente];";
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        $tab = $results->fetch();

                        echo "<input class='form_input' type='text' name='cf' placeholder='Codice Fiscale' value='$tab[cf]' required>";

                        echo "<input class='form_input' type='text' name='nome' placeholder='Nome' value='$tab[nome]' required>";

                        echo "<input class='form_input' type='text' name='cognome' placeholder='Cognome' value='$tab[cognome]' required>";

                        echo "<lable>Data di nascita:</lable>";
                        echo "<input class='form_input' type='date' name='datan' placeholder='Data di nascita' min='1900-01-01' value='$tab[data_nascita]' required>";

                        echo "<input class='form_input' type='email' name='email' placeholder='E-mail' value='$tab[email]' required>";
                    }
                    ?>
                    <button class="log_reg-btn" type="submit" name="update_data">Aggiorna</button>
                </form>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="container">
            <div class="form-box active">
                <form action="support/" method="post">
                    <h2>Cambio password</h2>
                    <?php
                    $sql = "SELECT * FROM utente WHERE IDutente = $_SESSION[IDutente];";
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        $tab = $results->fetch();
                        echo "<input class='form_input' id='pwd' type='password' name='passwd' placeholder='Vecchia password' minlength='6' required>";

                        echo "<input class='form_input' id='pwd' type='password' name='passwd' placeholder='Nuova password' minlength='6' required>";

                        
                        echo "<input class='form_input' id='pwd_rep' type='password' name='passwd_conf' placeholder='Conferma nuova password' minlength='6' required>";
                    }
                    ?>
                    <button class="log_reg-btn" type="submit" name="register">Aggiorna</button>
                </form>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['ruolo']) && $_SESSION['ruolo'] === 'Studente'): ?>
    <div class="main">
        <div class="container">
            <div class="form-box active">
                <h2>Storico</h2>
                <?php
                $sql = "SELECT COUNT(*) AS n_prenotazioni FROM prenotazione WHERE cf = '$_SESSION[codice_fiscale]';";
                $results = $conn->query($sql);

                if ($results->rowCount() > 0) {
                    $tab = $results->fetch();

                    // prendo il codice fiscale


                    echo "<h3>Prestiti totali</h3>";
                    echo "<p>$tab[n_prenotazioni]</p>";

                    
                }
                ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
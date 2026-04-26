<!DOCTYPE html>

<?php
session_start();

if (!isset($_SESSION["IDutente"]) || !isset($_SESSION["nome"]) || !isset($_SESSION["email"]) || !isset($_SESSION["ruolo"])) {
    header("location: index.php");
    exit();
}


$errors = [
    'update_data' => $_SESSION['update_error'] ?? '',
    'update_passwd' => $_SESSION['update_passwd_error'] ?? '',
    'update_passwd_suc' => $_SESSION['update_passwd_suc_message'] ?? '',
];

//update_passwd_suc

unset($_SESSION['update_error']);
unset($_SESSION['update_passwd_error']);
unset($_SESSION['update_passwd_suc_message']);

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function showSuccess($message) {
    return !empty($message) ? "<p class='success-message'>$message</p>" : '';
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
                <form id="updateForm" action="support/check_user_data.php" method="post">
                    <h2>Cambio password</h2>
                    <?php echo (showError($errors['update_passwd'])) ?>
                    <?php echo (showSuccess($errors['update_passwd_suc'])) ?>
                    <?php
                    $sql = "SELECT * FROM utente WHERE IDutente = $_SESSION[IDutente];";
                    $results = $conn->query($sql);

                    if ($results->rowCount() > 0) {
                        $tab = $results->fetch();
                        echo "<input class='form_input' id='pwd_old' type='password' name='passwd_old' placeholder='Vecchia password' minlength='6' required>";

                        echo "<input class='form_input' id='pwd' type='password' name='passwd' placeholder='Nuova password' minlength='6' required>";

                        
                        echo "<input class='form_input' id='pwd_rep' type='password' name='passwd_conf' placeholder='Conferma nuova password' minlength='6' required>";

                    
                        echo "<input type='hidden' name='IDutente' value='$_SESSION[IDutente]'>";
                    }
                    ?>
                    <button class="log_reg-btn" type="submit" name="update_passwd">Aggiorna</button>
                    <input type="hidden" name="update_passwd" value="1">
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
    

    <noscript>
	  <p>Il tuo Browser non supporta JavaScript.</p>
	  <p>E' necessario abilitarlo per proseguire.</p>
	  
	</noscript>
	
    <script src="script.js"></script>
	<script >
		document.getElementById('updateForm').addEventListener('submit', async (e) => {
            e.preventDefault(); // Stop form from submitting immediately
            
            const passwordInputOld = document.getElementById('pwd_old');
            const passwordValueOld = passwordInputOld.value; // ← corretto

            const passwordInput = document.getElementById('pwd');
            const passwordValue = passwordInput.value;

            const passwordInputRep = document.getElementById('pwd_rep');
            const passwordValueRep = passwordInputRep.value;
            
            // Hash the password
            const hashed_old = await sha256(passwordValueOld);
            const hashed = await sha256(passwordValue);
            const hashed_rep = await sha256(passwordValueRep);
            
            // Replace with hash
            passwordInputOld.value = hashed_old;
            passwordInput.value = hashed;
            passwordInputRep.value = hashed_rep;
            
            //console.log('Original hashed before submission:', hashed);
            // Submit form
            e.target.submit();
            
            e.target.reset();
		});
	</script>
</body>
</html>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
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
?>

<?php
//echo $errors['login'];
//echo $errors['register'];
//echo strlen($active_form);
try {
    include("inc/connection/start.php");
?>

<!DOCTYPE html>
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
            <div class="form-box active" id="register-form">
                <form id="registerForm" action="support/check_user_data.php" method="post">  
                    <h2>Registrati</h2>
                    <?php echo (showError($errors['register'])) ?>
                    <input class="form_input" type="text" name="cf" placeholder="Codice Fiscale" required>
                    <input class="form_input" type="text" name="nome" placeholder="Nome" required>
                    <input class="form_input" type="text" name="cnome" placeholder="Cognome" required>
                    <lable>Data di nascita:</lable>
                    <input class="form_input" type="date" name="datan" placeholder="Data di nascita" min="1900-01-01" required>
                    <lable>Tipologia utente:</lable>
                    <select class="form_input" name="tipo" required>
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
                    <input class="form_input" type="email" name="email" placeholder="E-mail" required>
                    <input class="form_input" id="pwd" type="password" name="passwd" placeholder="Password" minlength="6" required>
                    <input class="form_input" id="pwd_rep" type="password" name="passwd_conf" placeholder="Conferma password" minlength="6" required>
                    <button class="log_reg-btn" type="submit" name="register">Registrati</button>
                    <p>Hai un account? Effettua il <a href="login.php">Login</a>.</p>
                </form>
            </div>
        </div>
    </div>

    <noscript>
	  <p>Il tuo Browser non supporta JavaScript.</p>
	  <p>E' necessario abilitarlo per proseguire.</p>
	  
	</noscript>
	
	<script src="script.js">
		document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault(); // Stop form from submitting immediately
            
            const passwordInput = document.getElementById('pwd');
            const passwordValue = passwordInput.value;

            const passwordInputRep = document.getElementById('pwd_rep');
            const passwordValueRep = passwordInputRep.value;
            
            // Hash the password
            const hashed = await sha256(passwordValue);
            const hashed_rep = await sha256(passwordValueRep);
            
            // Replace with hash
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
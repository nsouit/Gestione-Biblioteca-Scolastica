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
            <div class="form-box active" id="login-form">
                <form id="loginForm" action="support/check_user_data.php" method="post">
                    <h2>Login</h2>
                    <?php echo (showError($errors['login'])) ?>
                    <input class="form_input" type="email" name="email" placeholder="E-mail" required>
                    <input id="pwd" class="form_input" type="password" name="passwd" placeholder="Password" minlength="8" required>
                    <button class="log_reg-btn" type="submit" name="login">Login</button>
                    <input type="hidden" name="login" value="1">
                    <p>Non hai un account? <a href="register.php">Registrati</a>.</p>
                </form>
            </div>
        </div>
    </div>

    <noscript>
	  <p>Il tuo Browser non supporta JavaScript.</p>
	  <p>E' necessario abilitarlo per proseguire.</p>
	</noscript>
	
	<script src="script.js"></script>
	<script >
		document.getElementById('loginForm').addEventListener('submit', async (e) => {
			e.preventDefault(); // Stop form from submitting immediately
			
			const passwordInput = document.getElementById('pwd');
			const passwordValue = passwordInput.value;
			
			// Hash the password
			const hashed = await sha256(passwordValue);
			
			// Replace with hash
			passwordInput.value = hashed;
			
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
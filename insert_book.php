<!DOCTYPE html>

<?php
if ($_SESSION['ruolo'] != 'Bibliotecario')
    header("Location: index.php");

try {
    include("inc/connection/start.php");
?>
<html>
<head>
    <?php
    include("support/head.php");
    ?>
</head>

<body>
    <?php
    include("nav_bar.php");
    ?>

    <div class="container">
        <div class="form-box active" id="login-form">
            <form action="support/check_user_data.php" method="post">
                <h2>Login</h2>
                <?php echo (showError($errors['login'])) ?>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="passwd" placeholder="Password" minlength="6" required>
                <button class="log_reg-btn" type="submit" name="login">Login</button>
                <p>Non hai un account? <a href="support/change_login.php?form=register">Registrati</a>.</p>
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
<?php
session_start();
//if (isset($_SESSION['nome']) && isset($_SESSION['email']))
// <li><a class='nav-login-button' href='login_register.php'>Login</a></li>
?>

<nav>
    <div class="logo">
        <a href="index.php"><img src="img/book.ico" alt="logo"></a>
        <a href="index.php"><h3>Biblioteca</h3></a>
        
    </div>

    <ul class="nav-links">
        <?php
        if (isset($_SESSION['nome']) && isset($_SESSION['email']))
            echo "<li><a class='nav-profile-button' href='personal_profile.php'>Ciao, ".$_SESSION['nome']."!</a></li>";
        else
            echo "<li><a class='nav-login-button' href='login_register.php'>Login</a></li>";
        ?>
        
    </ul>
</nav>
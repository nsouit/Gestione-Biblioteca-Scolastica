<?php

if (session_status() != PHP_SESSION_ACTIVE)
    session_start();
//if (isset($_SESSION['nome']) && isset($_SESSION['email']))
// <li><a class='nav-login-button' href='login_register.php'>Login</a></li>

?>

<nav>
    <div class="logo">
        <a href="index.php"><img src="img/book.ico" alt="logo"></a>
        <a href="index.php"><h3>Biblioteca</h3></a>
        
    </div>

    <form method="get" action="search.php">
        <input name="search_bar" type="text" placeholder="Cerca nella biblioteca"/>
        <button type="submit">Cerca</button>
    </form>

    <ul class="nav-links">
        <?php

        /*
        <div class="dropdown">
            <button class="dropbtn">Dropdown Menu</button>
            <div class="dropdown-content">
                <a href="#">Link 1</a>
                <a href="#">Link 2</a>
                <a href="#">Link 3</a>
            </div>
        </div>
        */
        if (isset($_SESSION['nome']) && isset($_SESSION['email']) && isset($_SESSION['ruolo'])) {
            switch ($_SESSION['ruolo']) {
                case "Bibliotecario":
                    echo "<li><a class='nav-btn' href='insert_book.php'>Registra libro</a></li>";
                    //echo "<li><a class='nav-btn' href='user_search.php'>Cerca utente</a></li>";
                    break;
                default:
                    break;
            }
                
        } else {
            echo "<li><a class='nav-login-button' href='login.php'>Login</a></li>";
        }
        ?>
    </ul>
    <?php
    if (isset($_SESSION['nome']) && isset($_SESSION['email'])) {
        echo "<div class='dropdown'>";
            echo "<button class='dropbtn'>Ciao, ".$_SESSION['nome']."!</button>";
            echo "<div class='dropdown-content'>";
                echo "<a href='personal_profile.php'>Profilo</a>";
                echo "<a href='support/logout.php'>Logout</a>";
            echo "</div>";
        echo "</div>";

        //echo "<li><a class='nav-profile-button' href='personal_profile.php'>Logout</a></li>";
        //echo "<li><a class='nav-profile-button' href='personal_profile.php'>Ciao, ".$_SESSION['nome']."!</a></li>";
    }
    ?>
</nav>
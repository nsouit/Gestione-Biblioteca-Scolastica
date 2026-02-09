<!DOCTYPE html>

<?php
include("inc/datiConnessione.inc");
try {
    include("inc/startConn.inc");
?>

<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/form.css">
    <title>Login - Biblioteca Galattica</title>
    <link rel="icon" type="image/x-icon" href="img/book.ico">
</head>

<body>
    <div class="form">
        <form action="checkUsrLogin.php" method="post">
            <div class="usrIn">
                <lable class="lbl">Username: </lable>
                <input required type="text" name="user_username" placeholder="Username"/>
            </div>

            <div class="usrIn">
                <lable class="lbl">Password: </lable>
                <input required class="passwd" type="password" name="user_passwd" placeholder="Password"/>
            </div>

            <button class="usrIn" type="submit">Login</button>
            <p>Nuovo utente? <a href="registrati.php">Registrati</a>.</p>
        </form>      
        
    </div>
</body>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
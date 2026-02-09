<!DOCTYPE html>

<?php
include("inc/datiConnessione.inc");
try {
    include("inc/startConn.inc");
?>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <title>Biblioteca Galattica</title>
        <link rel="icon" type="image/x-icon" href="img/book.ico">
    </head>

    <body>
        <div class="form">
            <form action="checkUsrRegistra.php" method="post">
                <div class="usrIn">
                    <lable class="lbl">Codice fiscale: </lable>
                    <input required type="text" name="user_codf" placeholder="Codice fiscale"/>
                </div>

                <div class="usrIn">
                    <lable class="lbl">Nome: </lable>
                    <input required type="text" name="user_name" placeholder="Nome"/>
                </div>

                <div class="usrIn">
                    <lable class="lbl">Cognome: </lable>
                    <input required type="text" name="user_lastname" placeholder="Cognome"/>
                </div>

                <div class="usrIn">
                    <lable class="lbl">Data di nascita: </lable>
                    <?php
                    echo "<input type='date' name='user_nascita' value='".date("Y-m-d")."'/>";
                    ?>
                </div>

                <div class="userIn">
                <lable class="lbl">Tipo: </lable>
                <select name="user_type">
                    <?php
                    $sql = "SELECT tipo FROM tipo_utente;";
                    $results = $conn->query($sql);

                    if ($results->rowCount() >= 1){
                        $tab = $results->fetchAll(PDO::FETCH_ASSOC);
                        foreach($tab as $row)
                            echo "<option value=".$row['tipo'].">".$row['tipo']."</option>";
                    }
                    ?>
                </select>
                </div>

                <div class="usrIn">
                    <lable class="lbl">Username: </lable>
                    <input required type="text" name="user_username" placeholder="Username"/>
                </div>

                <div class="usrIn">
                    <lable class="lbl">Password: </lable>
                    <input required class="passwd" type="password" name="user_passwd" placeholder="Password"/>
                </div>

                <button class="usrIn" type="submit">Registrati</button>

            </form>            
        </div>
    </body>
</html>

<script>
    var myInput = document.getElementById("psw");

</script>

<?php
} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
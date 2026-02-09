<?php
//session_start();
include("inc/datiConnessione.inc");
try {
    include("inc/startConn.inc");

//if ($_SERVER["REQUEST_METHOD" == "POST"]) {
if(array_filter($_POST)) {
    /*
    CF VARCHAR(16) NOT NULL,
    nome VARCHAR(30) NOT NULL,
    cognome VARCHAR(30) NOT NULL,
    dataNascita DATE NOT NULL,
    username VARCHAR(30) UNIQUE NOT NULL,
    passwd VARCHAR(64) NOT NULL, sha256
    tipo VARCHAR(30) NOT NULL,

    */
    $sql = "INSERT INTO utente VALUE ('".$_POST['user_codf']."', '".$_POST['user_name']."', '".$_POST['user_lastname']."', '".$_POST['user_nascita']."', '".$_POST['user_username']."', '".hash('sha256', $_POST['user_passwd'])."', '".$_POST['user_type']."');";
    $results = $conn->query($sql);
    

}
else
    echo "DATI NON CORRETTI";
header("Location: index.php");

} catch (PDOException $e) {
    echo "<h2 style='color:red; font-weight:bold'>".$e->getMessage()."</h2>";
}
?>
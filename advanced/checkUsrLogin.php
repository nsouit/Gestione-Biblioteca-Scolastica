


<?php
//include("inc/datiConnessione.php");
if ($_SERVER["REQUEST_METHOD" == "POST"]) {
$user = $_POST['user_username'];
//echo hash("sha256", $_POST['user_passwd']);

//SELECT * FROM utente WHERE username like 'mario@absolute';
//SELECT COUNT(*) FROM utente WHERE username like 'mario@absolute';
$sql = "SELECT passwd FROM utente WHERE username LIKE '".htmlspecialchars($_POST['user_username'])."';";
$results = $conn->query($sql);

if ($results->rowCount() == 1) {
    $row = $results->fetchAll(PDO::FETCH_ASSOC);
    if ($row[0]['passwd'] == hash("sha256", $_POST['user_passwd']))
        echo "Hello, ".$_POST['user_username'];
}

}

header("Location: index.php");
?>
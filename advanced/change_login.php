<?php
session_start();

//$_SESSION['active_form'] = 'login'

(isset($_GET['form'])) ? $_SESSION['active_form'] = $_GET['form'] : 'login';

header("Location: login_register.php");
exit();
?>
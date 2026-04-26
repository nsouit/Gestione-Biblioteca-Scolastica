<?php
define("CF_LEN", 16);
define("PASSWD_HASH_LEN", 64);
define("PASSWD_HASH_TYPE", "sha256");

function checkSetNoErrors($field){
    if(isset($_POST[$field]) && trim($_POST[$field]) != "")
        return true;
    else
        return false;
}
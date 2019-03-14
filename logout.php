<?php 

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    if (isset($_COOKIE['Upeng'])) {
        DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['Upeng'])));
        header("Location: /login.php");
        exit;
    } else {
        DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid'=>Login::isLoggedIn()));
        header("Location: /login.php");
        exit;
    }
} else {
    die;
}

?>
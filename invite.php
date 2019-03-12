<?php

include 'classes/Login.php';
include 'classes/DB.php';
include 'classes/Mail.php';

ini_set("display_errors","E_ALL");


if (Login::isLoggedIn()) {
    if (isset($_POST['invite'])) {
        $cstrong = True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
        $email = $_POST['email'];
        $user_id = Login::isLoggedIn();
        DB::query('INSERT INTO invite_tokens VALUES (NULL, :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
        Mail::sendMail('好友邀请', "欢迎加入有朋，请点击：<a href='http://upeng.fun/register.php?token=$token'>邀请链接</a>", $email);
        header('Location: index.php');
        exit;
    }
} else {
    header("Location: /login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
        <head>
            <title>有朋</title>
            <link rel="stylesheet" href="res/css/invite.css">
        </head>
        <body>
            <header>
                <nav>
                    <div class="container">
                        <div class="dropdown">
                            <button class="dropbtn">设置</button>
                            <div class="dropdown-content">
                                <a href="change-password.php">修改密码</a>
                                <a href="logout.php">退出</a>
                            </div>
                        </div>
                        <a href="discover.php">发现</a>
                        <a href="index.php">首页</a>
                        <a id="logo" href="index.php">有朋</a>
                        <form action="search.php" method="get">
                            <input type="search" name="keyword" placeholder="查找你认识的人 ...">
                            <input type="submit" name="search" value="查询"> 
                        </form>
                    </div>
                </nav>
            </header>
            <div class="content">
                <h2>请输入你想邀请的好友的电子邮箱</h2>
                <form action="invite.php" method="post">
                    <input type="email" name="email">
                    <p></p>
                    <input type="submit" name="invite" value="发送邀请">
                </form>

                <div class="clearfix"></div>
                </div>
            </div>
        </body>
</html>

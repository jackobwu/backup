<?php

include 'classes/DB.php';
include 'classes/Mail.php';

if (isset($_POST['resetpassword'])) {
        $cstrong = True;
        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
        $email = $_POST['email'];
        $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))[0]['id'];
        DB::query('INSERT INTO password_tokens VALUES (NULL, :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
        Mail::sendMail('Forgot Password!', "<a href='http://localhost/change-password.php?token=$token'>http://localhost/change-password.php?token=$token</a>", $email);
        header('Location: login.php');
        exit;
}

?>

<!DOCTYPE html>
<html>
        <head>
                <title>有朋</title>
                <link rel="stylesheet" href="res/css/forgot-password.css">
        </head>
        <body>
                <header>
                        <nav>
                                <div class="container">
                                <a href="register.php">注册</a>
                                <a href="login.php">登入</a>
                                <a id="logo" href="login.php">有朋</a>
                                </div>
                        </nav>
                </header>
                <div class="content">
                        <h2>请输入你要接收重置密码链接的邮箱</h2>
                        <form action="forgot-password.php" method="post">
                                <input type="text" name="email">
                                <p></p>
                                <input type="submit" name="resetpassword" value="重置密码">
                        </form>

                        <div class="clearfix"></div>
                        </div>
                </div>
        </body>
</html>

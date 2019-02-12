<?php

//创建账号，首先是从mysql创建表，第一个表是user，目前主要是邮箱和密码两个选项

include 'classes/DB.php';

if (isset($_POST['register'])) {
    $username = $_POST['lastname'].$_POST['firstname'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    if (!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
        if (strlen($username) >= 3 && strlen($username) <= 32) {
            if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
                if (strlen($password) >= 6 && strlen($password) <= 60) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {
                            DB::query('INSERT INTO users VALUES (NULL, :username, :email, :password, DEFAULT, DEFAULT, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)', array(':username'=>$username, ':email'=>$email, ':password'=>password_hash($password, PASSWORD_BCRYPT)));
                            header("Location: login.php");
                            exit;
                        } else {
                            $mail_eerror = 'Email已经被注册!';
                        }
                    } else {
                        $email_error = 'Email格式不正确!';
                    }
                } else {
                    $password_error = '密码长度符！';
                }
            } else {
                $name_error = '无效的名字';
            }
        } else {
            $name_error = '名字长度不符';
        }
    } else {
        $name_error = '名字已经被使用!';
    }
}
?>

<!-- 创建html的表单用来输入注册信息 -->

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/register.css">
    </head>
    <body>
        <header>
            <nav>
                <div class="container">
                    <a href="register.php">注册</a>
                    <a href="login.php">登入</a>
                    <a id="logo" href="#">有朋</a>
                </div>
            </nav>
        </header>
        <div class="container">
            <div class="login-form">
            <form action="register.php" method="post">
                <p>姓</p>
                <input type="text" name="lastname" required>
                <p>名</p>
                <?php  echo $name_error ?>
                <input type="text" name="firstname" required>
                <p>邮箱</p>
                <?php  echo $email_error ?>
                <input type="email" name="email" required>
                <p>密码</p>
                <?php  echo $password_error ?>
                <input type="password" name="password" required>         
                <input type="submit" name="register" value="注册">
            </form>
            </div>
            <div class="storyboard">
                <h2>欢迎来到有朋</h2>
                <p>有朋是一个实名制的社交网络，用来联络你的朋友和发现身边的人</p>
                <p>你可以利用有朋:</p>
                <p>通过姓名来搜索你认识的朋友</p>
                <p>找到和你同在一个地区，公司和学校的人</p>
                <p>查找朋友的朋友</p>

            </div>
        </div>
    </body>
</html>


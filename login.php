<?php

//用户登入数据库的验证

include 'classes/DB.php';

$password_error = "";
$email_error = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {
        if (password_verify($password, DB::query('SELECT password FROM users WHERE email=:email', array(':email'=>$email))[0]['password'])) {
            $cstrong = True;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))[0]['id'];
            DB::query('INSERT INTO login_tokens VALUES (NULL, :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
            setcookie("Upeng", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
            header("Location: index.php");
            exit;
        } else {
            $password_error = '密码不正确!';
        }
    } else {
        $email_error = '邮箱未注册!';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/login.css">
    </head>
    <body>
        <header>
            <nav>
                <div class="container">
                    <a href="invite-register.php">注册</a>
                    <a href="login.php">登入</a>
                    <a id="logo" href="login.php">有朋</a>
                </div>
            </nav>
        </header>
        <div class="container">
            <div class="login-form">
                <form action="login.php" method="post">
                <p>邮箱</p>
                <input type="text" name="email" required>
                <p><?php echo $email_error ?></p>
                <p>密码</p>
                <input type="password" name="password" required>
                <p><?php echo $password_error ?></p>
                <input type="submit" name="login" value="登入">
                <br>
                <a href="forgot-password.php">忘记了密码?</a>
            </div>
            <div class="storyboard">
                <h2>欢迎来到有朋 （Beta版）</h2>
                <p>有朋是一个实名制的社交网络，用来联络你的朋友和发现身边的人</p>
                <p>现在测试期间，需要好友邀请才可以注册。</p>
                <p>但所有数据都会保留到正式版，测试版用户只是更早注册。</p>
                <p>你可以利用有朋:</p>
                <p>通过姓名来搜索你认识的朋友</p>
                <p>找到和你同在一个地区，公司和学校的人</p>
                <p>查找朋友的朋友</p>
            </div>
        <div>
        <footer>
            <a href="beta.php">测试版</a>
            <a href="about.php">关于有朋</a>
            <a href="mailto:jackobwu@gmail.com">联系我吧</a>
            <p>有朋 © 2019</p>
        </footer>
    </body>
</html>
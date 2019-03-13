<?php

//创建账号，首先是从mysql创建表，第一个表是user，目前主要是邮箱和密码两个选项

include 'classes/DB.php';

$email_error = "";
$name_error = "";
$password_error = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    if (DB::query('SELECT user_id FROM invite_tokens WHERE token=:token', array(':token'=>sha1($token)))) {
        if (isset($_POST['register'])) {
            $username = $_POST['lastname'].$_POST['firstname'];
            $password = $_POST['password'];
            $email = $_POST['email'];
                if (strlen($username) >= 3 && strlen($username) <= 255) {
                    if (strlen($password) >= 6 && strlen($password) <= 64) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))) {
                                DB::query('INSERT INTO users VALUES (NULL, :username, :email, :password, DEFAULT, DEFAULT, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)', array(':username'=>$username, ':email'=>$email, ':password'=>password_hash($password, PASSWORD_BCRYPT)));
                                if (DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))) {
                                    $cstrong = True;
                                    $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                                    $user_id = DB::query('SELECT id FROM users WHERE email=:email', array(':email'=>$email))[0]['id'];
                                    DB::query('INSERT INTO login_tokens VALUES (NULL, :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                                    setcookie("Upeng", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
                                    header("Location: index.php");
                                    exit;
                                } else {
                                    header("Location: login.php");
                                    exit;
                                }
                            } else {
                                $email_error = 'Email已经被注册!';
                            }
                        } else {
                            $email_error = 'Email格式不正确!';
                        }
                    } else {
                        $password_error = '密码长度符！';
                    }
                } else {
                    $name_error = '名字长度不符';
                }
        }
    } else {
        header("Location: invite-register.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
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
                    <a href="login.php">登入</a>
                    <a id="logo" href="#">有朋</a>
                </div>
            </nav>
        </header>
        <div class="container">
            <div class="login-form">
            <form action="register.php?token=<?php echo $token; ?>" method="post">
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
        <footer>
            <a href="beta.php">测试版</a>
            <a href="about.php">关于有朋</a>
            <a href="mailto:jackobwu@gmail.com">联系我吧</a>
            <p>有朋 © 2019</p>
        </footer>
    </body>
</html>


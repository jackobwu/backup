<?php

include 'classes/DB.php';
include 'classes/Login.php';

$tokenIsValid = False;

if (Login::isLoggedIn()) {
        if (isset($_POST['changepassword'])) {
                $oldpassword = $_POST['oldpassword'];
                $newpassword = $_POST['newpassword'];
                $newpasswordrepeat = $_POST['newpasswordrepeat'];
                $userid = Login::isLoggedIn();
                if (password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['password'])) {
                        if ($newpassword == $newpasswordrepeat) {
                                if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
                                        DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), ':userid'=>$userid));
                                        header("Location: index.php");
                                        exit;
                                }
                        } else {
                                echo 'Passwords don\'t match!';
                        }
                } else {
                        echo 'Incorrect old password!';
                }
        }
} else {
        if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $tokenIsValid = True;
        if (DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>sha1($token)))) {
                $userid = DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
                if (isset($_POST['changepassword'])) {
                        $newpassword = $_POST['newpassword'];
                        $newpasswordrepeat = $_POST['newpasswordrepeat'];
                                if ($newpassword == $newpasswordrepeat) {
                                        if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
                                                DB::query('UPDATE users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), ':userid'=>$userid));
                                                DB::query('DELETE FROM password_tokens WHERE user_id=:userid', array(':userid'=>$userid));
                                                header("Location: login.php");
                                                exit;
                                        }
                                } else {
                                        echo 'Passwords don\'t match!';
                                }
                        }
        } else {
                die('Token invalid');
        }
} else {
        die('Not logged in');
}
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/change-password.css">
    </head>
    <body>
        <header>
            <?php if (!$tokenIsValid) { ?>
            <nav>
                <div class="container">
                    <div class="dropdown">
                        <button class="dropbtn">设置</button>
                        <div class="dropdown-content">
                            <a href="change-password.php">修改密码</a>
                            <a href="logout.php">退出</a>
                        </div>
                    </div>
                    <a href="discover.php">推荐</a>
                    <a href="index.php">首页</a>
                    <a id="logo" href="index.php">有朋</a>
                    <form action="search.php" method="get">
                        <input type="search" name="keyword" placeholder="查找你认识的人 ...">
                        <input type="submit" name="search" value="查询"> 
                    </form>
                </div>
            </nav>
            <?php } else { ?>
                <nav>
                    <div class="container">
                        <a href="register.php">注册</a>
                        <a href="login.php">登入</a>
                        <a id="logo" href="login.php">有朋</a>
                    </div>
                </nav>
            <?php } ?>
        </header>
        <div class="content">
            <h2>修改密码</h2>
            <form action="<?php if (!$tokenIsValid) { echo 'change-password.php'; } else { echo 'change-password.php?token='.$token.''; } ?>" method="post">
                    <?php if (!$tokenIsValid) { echo '<p>旧密码</p><input type="password" name="oldpassword">'; } ?>
                    <p>新密码</p>
                    <input type="password" name="newpassword">
                    <p>确认新密码</p>
                    <input type="password" name="newpasswordrepeat">
                    <p></p>
                    <input type="submit" name="changepassword" value="确认修改">
            </form>
            
            <div class="clearfix"></div>
        </div>
        </div>
    </body>
</html>


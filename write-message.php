<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();

    if (isset($_POST['send'])) {
        if (DB::query('SELECT id FROM users WHERE id=:receiver', array(':receiver'=>$_GET['receiver']))) {
            DB::query("INSERT INTO messages VALUES (NULL, :body, :sender, :receiver, 0, DEFAULT)", array(':body'=>$_POST['body'], ':sender'=>$user_id, ':receiver'=>$_GET['receiver']));
            
        }
    }
} else {
    header("Location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/write-message.css">
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
                    <a href="recommend.php">发现</a>
                    <a href="index.php">首页</a>
                    <a id="logo" href="index.php">有朋</a>
                    <form action="search.php" method="get">
                        <input type="search" name="keyword" placeholder="查找你认识的人 ...">
                        <input type="submit" name="search" value="查询"> 
                    </form>
                </div>
            </nav>
        </header>
        <div class="container">
            <div class="sidebar">
                <ul>
                    <li><a href="received-message.php">收件箱</a></li>
                    <li><a href="sent-message.php">发件箱</a></li>
                    <li><a href="#">联系人</a></li>
                </ul>
            </div>
            <div class="main">
                <form action="write-message?receiver=<?php echo $_GET['receiver'] ?>" method="post">
                    <textarea name="body" rows="8" cols="80"></textarea><br>
                    <input type="submit" name="send" value="发送">
                </form>
            </div>
        </div>
    </body>
</html>
<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();
    $user_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$user_id))[0]['username'];
    $message_id = $_GET['id'];
    $message = DB::query('SELECT * FROM messages WHERE id=:id', array(':id'=>$message_id))[0];
    $sender_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$message['sender_id']))[0]['username'];
    if (isset($_POST['send'])) {
        if (DB::query('SELECT id FROM users WHERE id=:receiver', array(':receiver'=>$_GET['receiver']))) {
            DB::query("INSERT INTO messages VALUES (NULL, :body, :sender, :receiver, 0, DEFAULT)", array(':body'=>$_POST['body'], ':sender'=>$user_id, ':receiver'=>$_GET['receiver']));
            header("Location: sent-message.php");
            exit;
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
        <link rel="stylesheet" href="res/css/message.css">
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
        <div class="container">
            <div class="sidebar">
                <ul>
                    <li><a href="received-message.php">收件箱</a></li>
                    <li><a href="sent-message.php">发件箱</a></li>
                    <li><a href="#">联系人</a></li>
                </ul>
            </div>
            <div class="main">
                <?php if ($_GET['id']) { ?>
                    <div class="message">
                        <p>发信人：<?php echo $sender_name ?></p>
                        <p>内容：<?php echo $message['body'] ?></p>
                        <p>发信时间：<?php echo $message['created_at'] ?></p>
                    </div>
                <?php } ?>
                <?php if ($_GET['receiver']) { ?>   
                <form action="message?receiver=<?php echo $_GET['receiver'] ?>" method="post">
                    <textarea name="body" rows="8" cols="80"></textarea><br>
                    <input type="submit" name="send" value="发送">
                </form>
                <?php } ?>
            </div>
        </div>
    </body>
</html>
<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();
    $messages = DB::query('SELECT * FROM messages WHERE sender_id=:user_id', array(':user_id'=>$user_id));
    $user_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$user_id))[0]['username'];
} else {
    header("Location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/sent-message.css">
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
                    <a href="activity.php">活动日志</a>
                    <a href="discover.php">发现</a>
                    <a href="index.php">首页</a>
                    <a href="index.php"><?php echo $user_name ?></a>
                    <a id="logo" href="index.php">有朋</a>
                    <form action="search.php" method="get">
                        <input type="search" name="username" placeholder="查找你认识的人 ...">
                        <input type="submit" name="search" value="查询"> 
                    </form>
                </div>
            </nav>
        </header>
        <div class="container">
            <div class="sidebar">
                <ul>
                    <li><a href="received-message.php"><img src="res/inbox.svg" />收件箱</a></li>
                    <li><a href="sent-message.php" style="color:#1c8adb"><img src="res/outbox.svg" />发件箱</a></li>
                    <li><a href="contact.php"><img src="res/contacts.svg" />联系人</a></li>
                </ul>
            </div>
            <div class="main">
                <?php foreach ($messages as $message) { 
                    $receiver = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$message['receiver_id']))[0]['username'];?>
                    <div class="message">
                        <a href="profile.php?id=<?php echo $message['receiver_id'] ?>"><?php echo $receiver ?></a><a class="email-content" href="message.php?id=<?php echo $message['id'] ?>"><?php echo $message['body'] ?></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>
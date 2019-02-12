<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();
    $messages = DB::query('SELECT * FROM messages WHERE receiver_id=:user_id', array(':user_id'=>$user_id));
} else {
    header("Location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/received-message.css">
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
                <?php foreach ($messages as $message) { ?>
                    <div class="message">
                        <a href="#">jackob</a><a class="email-content" href="#"><?php echo $message[body] ?></a>
                        <a class="email-tail" href="write-message.php?receiver=<?php echo $message[sender_id] ?>">回复</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>
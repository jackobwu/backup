<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();
    $username = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$user_id))[0]['username'];
    if (isset($_POST['agree'])) {
        $request_id = DB::query('SELECT id from users where username=:username', array(':username'=>$_POST['request_name']))[0]['id']; 
        DB::query('UPDATE friendship SET accept=1 WHERE friend_id=:receive_id AND user_id=:request_id', array(':receive_id'=>$user_id, ':request_id'=>$request_id));
        DB::query('INSERT INTO friendship VALUES (NULL, :user_id, :friend_id, :accept, DEFAULT, DEFAULT)', array(':user_id'=>$user_id, ':friend_id'=>$request_id, ':accept'=>1));
        DB::query('INSERT INTO activity_log VALUES ( NULL, :user_id, :friend_id, :event, DEFAULT)', array(':user_id'=>$user_id, ':friend_id'=>$request_id, ':event'=>"成为了好友"));
        header("Refresh:0");
    } else if (isset($_POST['disagree'])) {
        $request_id = DB::query('SELECT id from users where username=:username', array(':username'=>$_POST['request_name']))[0]['id']; 
        DB::query('DELETE FROM friendship WHERE friend_id=:receive_id AND user_id=:request_id', array(':receive_id'=>$user_id, ':request_id'=>$request_id));
        header("Refresh:0");
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/friends-receive.css">
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
                    <a href="index.php"><?php echo $username ?></a>
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
                    <li><a href="friends-receive.php" style="color:#1c8adb"><img src="res/receive.svg" />待处理申请</a></li>
                    <li><a href="friends-request.php"><img src="res/request.svg" />我的申请</a></li>
                    <li><a href="friends.php"><img src="res/contacts.svg" />好友列表</a></li>
                    <li><a href="received-message.php"><img src="res/mailbox.svg" />我的私信</a></li>
                </ul>
            </div>
            <div class="main">
                <?php if (DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=0', array(':friend_id'=>$user_id))) {
                        $friends = DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=0', array(':friend_id'=>$user_id));
                        foreach($friends as $friend) {
                            $request = DB::query('SELECT username from users WHERE id=:id', array(':id'=>$friend[0]))[0]['username'];
                            echo "<div class='request'>
                            <form action='friends-receive.php' method='post'>
                            <label>".$request."</label>
                            <input type=text name='request_name' value=".$request.">
                            <input type=submit name='disagree' value='删除请求'></input>
                            <input type=submit name='agree' value='通过'></input>
                            </form><div class='clearfix'></div></div>";
                        }
                } ?>
            </div>
        </div>
    </body>
</html>
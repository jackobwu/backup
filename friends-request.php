<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();
    if (isset($_POST['reject'])) {
        $receive_id = DB::query('SELECT id from users where username=:username', array(':username'=>$_POST['receive_name']))[0]['id']; 
        DB::query('DELETE FROM friendship WHERE friend_id=:receive_id AND user_id=:request_id', array(':receive_id'=>$receive_id, ':request_id'=>$user_id));
        header("Refresh:0");
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/friends-request.css">
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
                    <li><a href="friends-receive.php">待处理申请</a></li>
                    <li><a href="friends-request.php">我的好友申请</a></li>
                    <li><a href="friends.php">好友列表</a></li>
                    <li><a href="received-message.php">我的私信</a></li>
                </ul>
            </div>
            <div class="main">
                <?php if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=0', array(':user_id'=>$user_id))) {
                        $friends = DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=0', array(':user_id'=>$user_id));
                        foreach($friends as $friend) {
                            $request = DB::query('SELECT username from users WHERE id=:id', array(':id'=>$friend[0]))[0]['username'];
                            echo "<div class='request'>
                            <form action='friends-request.php' method='post'>
                            <label>".$request."</label>
                            <input type=text name='receive_name' value=".$request.">
                            <input type=submit name='reject' value='取消请求'></input>
                            </form><div class='clearfix'></div></div>";
                        }
                } ?>
            </div>
        </div>
    </body>
</html>
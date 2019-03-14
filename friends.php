<?php

include 'classes/DB.php';
include 'classes/Login.php';


if (isset($_GET['id'])) {
    $userid = $_GET['id'];
    $username = "";
    if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid))) {
        $friends = DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid));
        $totalNumbers = count($friends); 
        $limit = 20;
        $pages = ceil($totalNumbers / $limit);
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));
        $offset = ($page - 1)  * $limit;
        $friendsOfPage = array_slice($friends, $offset, $limit);
    } else {
        $friendsOfPage = [];
    }
} else {
    if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
        $username = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$userid))[0]['username'];
        if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid))) {
            $friends = DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid));
            //Pagination
            $totalNumbers = count($friends); 
            $limit = 20;
            $pages = ceil($totalNumbers / $limit);
            $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
                'options' => array(
                    'default'   => 1,
                    'min_range' => 1,
                ),
            )));
            $offset = ($page - 1)  * $limit;
            $friendsOfPage = array_slice($friends, $offset, $limit);
        } else {
            $friendsOfPage = [];
        }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/friends.css">
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
                        <input type="search" name="keyword" placeholder="查找你认识的人 ...">
                        <input type="submit" name="search" value="查询"> 
                    </form>
                </div>
            </nav>
        </header>
        <div class="container">
            <div class="sidebar">
                <ul>
                    <li><a href="friends-receive.php" ><img src="res/receive.svg" />待处理申请</a></li>
                    <li><a href="friends-request.php"><img src="res/request.svg" />我的申请</a></li>
                    <li><a href="friends.php" style="color:#1c8adb"><img src="res/contacts.svg" />好友列表</a></li>
                    <li><a href="received-message.php"><img src="res/mailbox.svg" />我的私信</a></li>
                </ul>
            </div>
            <div class="main">
                <div class="showFriends">
                <?php foreach ($friendsOfPage as $friend) { 
                    $friendName = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friend[0]))[0]['username'];
                    $friendId = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$friendName))[0]['id'];
                    $friendAvatar = DB::query('SELECT avatar FROM users WHERE id=:id', array(':id'=>$friend[0]))[0]['avatar'];
                    if ($friendAvatar != NULL) {
                        echo "<a class='card' href='profile.php?id=".$friendId."'>
                            <img src='res/uploads/".$friendAvatar."' alt='Avatar'>
                            <div class='content'>
                                <h4><b>$friendName</b></h4> 
                            </div>
                            </a>";
                        } else {
                            echo "<a class='card' href='profile.php?id=".$friendId."'>
                            <img src='res/profile.png' alt='Avatar'>
                            <div class='content'>
                                <h4><b>$friendName</b></h4> 
                            </div>
                            </a>";
                        }
                } ?>
                </div>
                <?php if ($pages > 1) { ?>
                <div class="pagination">
                    <a href="friends.php?page=<?php if ($page>1){echo $page-1;}else{echo 1;} ?>">&laquo;</a>
                    <a class="active"  href="friends.php?page=<?php echo $page ?>">第<?php echo $page; ?>页</a>
                    <a href="friends.php?page=<?php echo $pages ?>">共<?php echo $pages ?>页</a>
                    <a href="friends.php?page=<?php if ($page<$pages){echo $page+1;}else{echo $pages;} ?>">&raquo;</a>
                </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>
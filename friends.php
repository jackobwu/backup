<?php

include 'classes/DB.php';
include 'classes/Login.php';


if (isset($_GET['id'])) {
    $userid = $_GET['id'];
    if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid)) || DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=1', array(':friend_id'=>$userid)) ) {
        $friendOfMine = DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid));
        $meAsFriend = DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=1', array(':friend_id'=>$userid));
        $friends = array_merge($friendOfMine, $meAsFriend);
            
    } else {
        $friends = [];
    }
} else {
    if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
        if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid)) || DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=1', array(':friend_id'=>$userid)) ) {
            $friendOfMine = DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid));
            $meAsFriend = DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=1', array(':friend_id'=>$userid));
            $friends = array_merge($friendOfMine, $meAsFriend);  
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
            $start = $offset + 1;
            $end = min(($offset + $limit), $totalNumbers);
        } else {
            $friends = [];
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
                <div class="showFriends">
                <?php foreach ($friends as $friend) { 
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
                <div class="pagination">
                    <a href="friends.php?page=<?php if ($page>1){echo $page-1;}else{echo 1;} ?>">&laquo;</a>
                    <a class="active"  href="friends.php?page=<?php echo $page ?>">第<?php echo $page; ?>页</a>
                    <a href="friends.php?page=<?php echo $pages ?>">共<?php echo $pages ?>页</a>
                    <a href="friends.php?page=<?php if ($page<$pages){echo $page+1;}else{echo $pages;} ?>">&raquo;</a>
                </div>
            </div>
        </div>
    </body>
</html>
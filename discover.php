<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();
    $recommendFriends = DB::query('SELECT y.friend_id  FROM friendship x LEFT JOIN friendship y ON y.user_id = x.friend_id AND y.friend_id <> x.user_id  LEFT JOIN friendship z ON z.friend_id = y.friend_id AND z.user_id = x.user_id WHERE x.user_id = :user_id AND z.user_id IS NULL GROUP BY y.friend_id', array(':user_id'=>$user_id));
    //print_r($recommendFriends[0]['friend_id']) ;
    //die;
    if ($recommendFriends[0]['friend_id']) {
        $totalNumbers = count($recommendFriends); 
        $limit = 20;
        $pages = ceil($totalNumbers / $limit);
        $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
            'options' => array(
                'default'   => 1,
                'min_range' => 1,
            ),
        )));
        $offset = ($page - 1)  * $limit;
        $friendsOfPage = array_slice($recommendFriends, $offset, $limit); 
    } else {
        $friendsOfPage = null;
    }
    
} else {
    header("Location: /login.php");
    exit;
}



?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/discover.css">
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
                    <li><a href="friends.php">我的朋友</a></li>
                    <li><a href="received-message.php">我的消息</a></li>
                </ul>
            </div>
            <div class="main">
                <?php if ($friendsOfPage != null) {
                    foreach ($friendsOfPage as $friend) {   
                    $discover = DB::query('SELECT * FROM users WHERE id=:id', array(':id'=>$friend[0]))[0];?>
                    <div class="card">
                        <img src="res/profile.png" alt="Avatar" style="width:100%">
                        <div class="content">
                            <h4><b><?php echo $discover['username'] ?></b></h4> 
                        </div>
                    </div>
                <?php }} ?>
                <?php if ($pages > 1) { ?>
                <div class="pagination">
                    <a href="discover.php?page=<?php if ($page>1){echo $page-1;}else{echo 1;} ?>">&laquo;</a>
                    <a class="active"  href="discover.php?page=<?php echo $page ?>">第<?php echo $page; ?>页</a>
                    <a href="discover.php?page=<?php echo $pages ?>">共<?php echo $pages ?>页</a>
                    <a href="discover.php?page=<?php if ($page<$pages){echo $page+1;}else{echo $pages;} ?>">&raquo;</a>
                </div>
                <?php } ?>
                
            </div>
            <div class="clearfix"></div>
        </div>
    </body>
</html>
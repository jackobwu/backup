<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
} else {
    header("Location: /login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/upload.css">
    </head>
    <body>
        <header>
            <nav>
                <div class="container">
                    <div class="dropdown">
                        <button class="dropbtn">设置</button>
                        <div class="dropdown-content">
                            <a href="index.php">修改密码</a>
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
                    <li><a href="edit.php">编辑资料</a></li>
                    <li><a active href="upload.php">上传头像</a></li>
                    <li><a href="friends.php">我的朋友</a></li>
                    <li><a href="received-message.php">我的私信</a></li>
                </ul>
            </div>
            <div class="main-content">
            <h2>上传头像</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="profileimg">
                    <br>
                    <input type="submit" name="uploadprofileimg" value="上传">
            </form>
            </div>
        </div>
    </body>
</html>
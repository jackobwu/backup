<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $logged_id = Login::isLoggedIn();
    if (isset($_GET['search']) && ($_GET['keyword'] !== "")) {
        $keyword = $_GET['keyword'];
        $results = DB::query('SELECT username FROM users WHERE username LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
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
        <link rel="stylesheet" href="res/css/search.css">
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
                    <a href="discover.php">推荐</a>
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
                    <li><a href="friends.php">我的朋友</a></li>
                    <li><a href="received-message.php">我的消息</a></li>
                </ul>
            </div>
            <div class="main">
            <?php if ($keyword) { 
                foreach ($results as $result) {
                $username = $result['username'];
                $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id']; 
                if ($user_id != $logged_id ) {?>
                <a class="card" href="profile.php?id=<?php echo $user_id ?>">
                    <img src="res/profile.png" alt="Avatar" style="width:100%">
                    <div class="content">
                        <h4><b><?php echo $username ?></b></h4> 
                    </div>
                </a>
            <?php }} }; ?>
            </div>
        </div>
    </body>
</html>
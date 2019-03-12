<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $logged_id = Login::isLoggedIn();
    if (isset($_GET['search'])) {
        if (isset($_GET['username'])) {
            $keyword = $_GET['username'];
            $results = DB::query('SELECT username FROM users WHERE username LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['university'])) {
            $keyword = $_GET['university'];
            $results = DB::query('SELECT username FROM users WHERE university LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['livein'])) {
            $keyword = $_GET['livein'];
            $results = DB::query('SELECT username FROM users WHERE livein LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['relationship'])) {
            $keyword = $_GET['relationship'];
            $results = DB::query('SELECT username FROM users WHERE relationship LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['company'])) {
            $keyword = $_GET['company'];
            $results = DB::query('SELECT username FROM users WHERE company LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['profession'])) {
            $keyword = $_GET['profession'];
            $results = DB::query('SELECT username FROM users WHERE profession LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['hometown'])) {
            $keyword = $_GET['hometown'];
            $results = DB::query('SELECT username FROM users WHERE hometown LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['elementary_school'])) {
            $keyword = $_GET['elementary_school'];
            $results = DB::query('SELECT username FROM users WHERE elementary_school LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['junior_school'])) {
            $keyword = $_GET['junior_school'];
            $results = DB::query('SELECT username FROM users WHERE junior_school LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        } else if (isset($_GET['senior_school'])) {
            $keyword = $_GET['senior_school'];
            $results = DB::query('SELECT username FROM users WHERE senior_school LIKE :keyword',  array(':keyword'=>"%{$keyword}%"));
        }
    } else {
        header("Location: discover.php");
        exit;
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
                    <a href="discover.php">发现</a>
                    <a href="index.php">首页</a>
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
                $user_avatar = DB::query('SELECT avatar FROM users WHERE username=:username', array(':username'=>$username))[0]['avatar'];
                if ($user_id != $logged_id ) {?>
                    <?php if ( $user_avatar ) { ?>
                        <a class="card" href="profile.php?id=<?php echo $user_id ?>">
                            <img src="res/uploads/<?php echo $user_avatar ?>" alt="Avatar" >
                            <div class="content">
                                <h4><b><?php echo $username ?></b></h4> 
                            </div>
                        </a>
                    <?php } else { ?>
                        <a class="card" href="profile.php?id=<?php echo $user_id ?>">
                            <img src="res/profile.png" alt="Avatar" >
                            <div class="content">
                                <h4><b><?php echo $username ?></b></h4> 
                            </div>
                        </a>          
            <?php }}} }; ?>
            </div>
        </div>
    </body>
</html>
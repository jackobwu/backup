<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
} else {
    header("Location: /login.php");
    exit;
}
$username = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];
$created_at = DB::query('SELECT created_at FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['created_at'];
$updated_at = DB::query('SELECT updated_at FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['updated_at'];
$email = DB::query('SELECT email FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['email'];
$gender = DB::query('SELECT gender FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['gender'];
$birthday = DB::query('SELECT birthday FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['birthday'];
$hometown = DB::query('SELECT hometown FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['hometown'];
$livein = DB::query('SELECT livein FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['livein'];
$elementary_school = DB::query('SELECT elementary_school FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['elementary_school'];;
$junior_school = DB::query('SELECT junior_school FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['junior_school'];
$senior_school = DB::query('SELECT senior_school FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['senior_school'];
$university = DB::query('SELECT university FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['university'];
$profession = DB::query('SELECT profession FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['profession'];
$company = DB::query('SELECT company FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['company'];
$relationship = DB::query('SELECT relationship FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['relationship'];
$lookfor = DB::query('SELECT lookfor FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['lookfor'];
$mobile = DB::query('SELECT mobile FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['mobile'];
$wechat = DB::query('SELECT wechat FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['wechat'];
$qq = DB::query('SELECT qq FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['qq'];

if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid)) || DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=1', array(':friend_id'=>$userid)) ) {
    $friendOfMine = DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid));
    $meAsFriend = DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=1', array(':friend_id'=>$userid));
    $friends = array_merge($friendOfMine, $meAsFriend);
    $friendName1 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[0][0]))[0]['username'];
    $friendName2 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[1][0]))[0]['username'];
    $friendName3 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[2][0]))[0]['username'];
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/index.css">
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
                <li><a href="edit.php">编辑资料</a></li>
                <li><a href="friends.php">我的朋友</a></li>
                <li><a href="received-message.php">我的私信</a></li>
            </ul>
        </div>
        <div class="main">
            <div class="left">
                <div class="avatar">
                    <div class="title">头像</div>
                    <img src="res/profile.png" alt="profileimg">
                </div>
                <div class="friends">
                    <div class="title">好友</div>
                    <?php if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid)) || DB::query('SELECT user_id FROM friendship WHERE friend_id=:friend_id AND accept=1', array(':friend_id'=>$userid)) ) {?>
                        <a href='profile.php?id=<?php echo $friends[0][0] ?>'><?php echo $friendName1 ?></a><br>
                        <a href='profile.php?id=<?php echo $friends[1][0] ?>'><?php echo $friendName2 ?></a><br>
                        <a href='profile.php?id=<?php echo $friends[2][0] ?>'><?php echo $friendName3 ?></a><br>
                    <?php } else { ?>
                        <p> 你暂时还未添加任何朋友</p>
                    <?php } ?>
                    <a href="friends.php">查看更多<a>
                </div>
            </div>
            <div class="right">
                <div class="information">
                    <p>账号信息</p>
                    <p>姓名: <?php echo $username ?></p>
                    <p>加入时间: <?php echo $created_at ?></p>
                    <p>上次更新: <?php echo $updated_at ?></p>
                    <p>基础信息</p>
                    <p>小学: <?php echo $elementary_school ?></p>
                    <p>初中: <?php echo $junior_school ?></p>
                    <p>高中: <?php echo $senior_school ?></p>
                    <p>大学: <?php echo $university ?></p>
                    <p>家乡: <?php echo $hometown ?></p>
                    <p>性别: <?php echo $gender ?></p>
                    <p>生日: <?php echo $birthday ?></p>
                    <p>联系方式</p>
                    <p>手机: <?php echo $mobile ?></p>
                    <p>微信: <?php echo $wechat ?></p>
                    <p>qq: <?php echo $qq ?></p>
                    <p>电子邮件: <?php echo $email ?></p>
                    <p>博客:</p>
                    <p>个人信息</p>
                    <p>寻找: <?php echo $lookfor ?></p>
                    <p>个人兴趣:</p>
                    <p>感情状况: <?php echo $relationship ?></p>
                    <p>希望另一半是:</p>
                    <p>喜欢的东东:</p>
                    <p>关于我:</p>
                </div>
            </div>
            
            <div class="clearfix"></div>
        </div>
        </div>
    </body>
</html>
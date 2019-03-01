<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (isset($_GET['id'])) {
    $userid = $_GET['id'];
    $logged_userid = Login::isLoggedIn();
    if ($userid != $logged_userid ) {

        $username = DB::query('SELECT username FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['username'];
        $created_at = DB::query('SELECT created_at FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['created_at'];
        $updated_at = DB::query('SELECT updated_at FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['updated_at'];
        $email = DB::query('SELECT email FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['email'];
        $avatar = DB::query('SELECT avatar FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['avatar'];
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

        if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid))) {
            $friends = DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid));
        }

        if (Login::isLoggedIn()) {
            $hasRequest = DB::query('SELECT id FROM friendship WHERE (friend_id=:friend_id AND user_id=:user_id) OR (user_id=:friend_id AND friend_id=:user_id)', array(':friend_id'=>$userid, ':user_id'=>$logged_userid))[0]['id'];
            $isMyFriend = DB::query('SELECT accept FROM friendship WHERE friend_id=:friend_id AND user_id=:user_id', array(':friend_id'=>$userid, ':user_id'=>$logged_userid))[0]['accept'];
            $isHisFriend = DB::query('SELECT accept FROM friendship WHERE friend_id=:friend_id AND user_id=:user_id', array(':friend_id'=>$logged_userid, ':user_id'=>$userid))[0]['accept'];
            if ( $hasRequest ) {
                if ($isMyFriend ==1 || $isHisFriend == 1) {
                    $isFriend = true;
                } else {
                    $isFriend = false;
                }
            } else {
                if (isset($_POST['request'])) {
                    DB::query('INSERT INTO friendship VALUES (NULL, :user_id, :friend_id, :accept, DEFAULT, DEFAULT)', array(':user_id'=>$logged_userid, ':friend_id'=>$userid, ':accept'=>0));
                    header('Location: friends-request.php');
                    exit;
                    }
            }    
        } else {
            header("Location: index.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/profile.css">
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
                <li><a href="received-message.php">我的私信</a></li>
            </ul>
        </div>
        <div class="main">
            <div class="left">
                <div class="avatar">
                    <div class="title">头像</div>
                    <?php if ($avatar == NULL) { ?>
                        <img src="res/profile.png" alt="profileimg">
                    <?php } else { ?>
                        <img src="res/uploads/<?php echo $avatar ?>" alt="profileimg">
                    <?php } ?>  
                </div>
                <div class="connection">
                    <?php if ($isFriend) { ?>
                        <form action="#" method="post">
                            <input type=submit name="request" value="已经是好友" disabled>
                        </form>
                    <?php } elseif ($hasRequest) {?>
                        <form action="#" method="post">
                            <input type=submit name="request" value="已经发送好友申请" disabled>
                        </form>
                    <?php } else { ?>
                        <form action="profile.php?id=<?php echo $userid ?>" method="post">
                            <input type=submit name="request" value="加为好友">
                        </form>
                    <?php } ?>
                    <form action="message.php?receiver=<?php echo $userid ?>" method="post">
                        <input type=submit name="message" value="发送私信">
                    </form>
                    
                </div>
                <div class="mutual-friends">
                    <div class="title">共同好友</div>
                    <p>你们有0个共同好友</p>
                </div>
                <div class="friends">
                    <div class="title">好友</div>
                    <?php 
                        $friendName1 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[0][0]))[0]['username'];
                        $friendName2 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[1][0]))[0]['username'];
                        $friendName3 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[2][0]))[0]['username'];
                        ?>
                        <a href='profile.php?id=<?php echo $friends[0][0] ?>'><?php echo $friendName1 ?></a><br>
                        <a href='profile.php?id=<?php echo $friends[1][0] ?>'><?php echo $friendName2 ?></a><br>
                        <a href='profile.php?id=<?php echo $friends[2][0] ?>'><?php echo $friendName3 ?></a><br>

                    <a href="friends.php?id=<?php echo $userid; ?>">查看更多<a>

                </div>
            </div>
            <div class="right">
                <div class="information">
                    <div class="title">资料</div>
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
                    <p>个人信息</p>
                    <p>寻找: <?php echo $lookfor ?></p>
                    <p>感情状况: <?php echo $relationship ?></p>
                    <p>关于我:</p>
                </div>
            </div>
            
            <div class="clearfix"></div>
        </div>
        </div>
    </body>
</html>
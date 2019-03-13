<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $logged_userid = Login::isLoggedIn();
    $user_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$logged_userid))[0]['username'];

    if (isset($_GET['id'])) {
        $userid = $_GET['id'];

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
            $friends = DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid));
            $about_me = DB::query('SELECT about_me FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['about_me'];
            $mutual_friends = DB::query('SELECT x.friend_id FROM friendship x INNER JOIN friendship y ON x.friend_id = y.friend_id WHERE x.user_id = :x_user_id AND y.user_id = :y_user_id', array(':x_user_id'=>$logged_userid, ':y_user_id'=>$userid));

            if ( DB::query('SELECT id FROM friendship WHERE (friend_id=:friend_id AND user_id=:user_id) OR (user_id=:friend_id AND friend_id=:user_id)', array(':friend_id'=>$userid, ':user_id'=>$logged_userid)) ) {
                $isFriend = DB::query('SELECT accept FROM friendship WHERE (friend_id=:friend_id AND user_id=:user_id) OR (user_id=:friend_id AND friend_id=:user_id)', array(':friend_id'=>$userid, ':user_id'=>$logged_userid))[0]['accept'];
                if ($isFriend == 0) {
                    $hasRequest = 1;
                } else {
                    $hasRequest = 0;
                }
            } else {
                $isFriend = 0;
                if (isset($_POST['request'])) {
                    DB::query('INSERT INTO friendship VALUES (NULL, :user_id, :friend_id, :accept, DEFAULT, DEFAULT)', array(':user_id'=>$logged_userid, ':friend_id'=>$userid, ':accept'=>0));
                    header('Location: friends-request.php');
                    exit;
                    }
            }    

            
        }  else {
            header("Location: index.php");
            exit;
        }
    } else {
        header("Location: index.php");
        exit;
    }
}  else {
    header("Location: login.php");
    exit;
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
                    <a href="discover.php">发现</a>
                    <a href="index.php">首页</a>
                    <a href="index.php"><?php echo $user_name ?></a>
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
                <li><a href="edit.php"><img src="res/edit.svg"/>编辑资料</a></li>
                <li><a href="friends.php"><img src="res/contacts.svg"/>我的朋友</a></li>
                <li><a href="received-message.php"><img src="res/mailbox.svg"/>我的私信</a></li>
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
                    <?php if ($isFriend == 1) { ?>
                        <form action="#" method="post">
                            <input type=submit name="request" value="已经是好友" disabled>
                        </form>
                    <?php } elseif ($hasRequest == 1) {?>
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
                <div class="aboutme">
                    <div class="title">关于我</div>
                    <?php if ($about_me == NULL) { 
                        echo "<p>他还未添加个人说明</p>";
                    } else {
                        echo "<p>".$about_me."</p>";
                    } ?>

                </div>
                <div class="mutual-friends">
                    <div class="title">共同好友</div>
                    <p>你们有<?php echo count($mutual_friends); ?>个共同好友</p>
                </div>
                <div class="friends">
                    <div class="title">好友(<?php echo count($friends); ?>)</div>
                    <?php if (DB::query('SELECT friend_id FROM friendship WHERE user_id=:user_id AND accept=1', array(':user_id'=>$userid))){
                        $friendName1 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[0][0]))[0]['username'];
                        $friendName2 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[1][0]))[0]['username'];
                        $friendName3 = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$friends[2][0]))[0]['username'];
                    } else { $friendName1 = ""; $friendName2 = ""; $friendName3 = ""; }?>
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
                    <p>小学: <a href="search.php?search=1&elementary_school=<?php echo $elementary_school ?>"><?php echo $elementary_school ?></a></p>
                    <p>初中: <a href="search.php?search=1&junior_school=<?php echo $junior_school ?>"><?php echo $junior_school ?></a></p>
                    <p>高中: <a href="search.php?search=1&senior_school=<?php echo $senior_school ?>"><?php echo $senior_school ?></a></p>
                    <p>大学: <a href="search.php?search=1&university=<?php echo $university ?>"><?php echo $university ?></a></p>
                    <p>家乡: <a href="search.php?search=1&hometown=<?php echo $hometown ?>"><?php echo $hometown ?></a></p>
                    <p>居住地: <a href="search.php?search=1&livein=<?php echo $livein ?>"><?php echo $livein ?></a></p>
                    <p>性别: <?php echo $gender ?></p>
                    <p>生日: <?php echo $birthday ?></p>
                    <p>工作信息</p>
                    <p>职业: <a href="search.php?search=1&profession=<?php echo $profession ?>"><?php echo $profession ?></a></p>
                    <p>公司: <a href="search.php?search=1&company=<?php echo $company ?>"><?php echo $company ?></a></p>
                    <p>个人信息</p>
                    <p>寻找: <?php echo $lookfor ?></p>
                    <p>感情状况: <a href="search.php?search=1&relationship=<?php echo $relationship ?>"><?php echo $relationship ?></a></p>
                    <p>联系方式</p>
                    <p>手机: <?php echo $mobile ?></p>
                    <p>微信: <?php echo $wechat ?></p>
                    <p>qq: <?php echo $qq ?></p>
                    <p>电子邮件: <?php echo $email ?></p>
                </div>
            </div>
            
            <div class="clearfix"></div>
        </div>
        </div>
    </body>
</html>

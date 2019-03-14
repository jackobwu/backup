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
$about_me = DB::query('SELECT about_me FROM users WHERE id=:userid', array(':userid'=>$userid))[0]['about_me'];


if (isset($_POST['foundation'])) {
    $newusername = $_POST['username'];
    $newgender = $_POST['gender'];
    $newbirthday = $_POST['birthday'];
    $newhometown = $_POST['hometown'];
    $newlivein = $_POST['livein'];
    if (strlen($newusername) >= 3 && strlen($newusername) <= 255) {
        DB::query('UPDATE users SET username=:username, gender=:gender, birthday=:birthday, hometown=:hometown, livein=:livein WHERE id=:userid', array(':username'=>$newusername, 'gender'=>$newgender, ':userid'=>$userid, ':birthday'=>$newbirthday, ':hometown'=>$newhometown, ':livein'=>$newlivein));
        DB::query('INSERT INTO activity_log VALUES ( NULL, :user_id, 0, :event, DEFAULT)', array(':user_id'=>$userid, ':event'=>"更新了基础信息"));
        header("Refresh:0");
        } else {
            echo 'Invalid username';
        }
}

if (isset($_POST['school'])) {
    $new_elementary_school = $_POST['elementary_school'];
    $new_junior_school = $_POST['junior_school'];
    $new_senior_school = $_POST['senior_school'];
    $new_university = $_POST['university'];

    DB::query('UPDATE users SET elementary_school=:elementary_school, junior_school=:junior_school, senior_school=:senior_school, university=:university WHERE id=:userid', array(':elementary_school'=>$new_elementary_school, ':junior_school'=>$new_junior_school, ':userid'=>$userid, ':senior_school'=>$new_senior_school, ':university'=>$new_university));
    DB::query('INSERT INTO activity_log VALUES ( NULL, :user_id, 0, :event, DEFAULT)', array(':user_id'=>$userid, ':event'=>"更新了学校信息"));
    header("Refresh:0");
}

if (isset($_POST['work'])) {
    $new_profession = $_POST['profession'];
    $new_company = $_POST['company'];

    DB::query('UPDATE users SET profession=:profession, company=:company WHERE id=:userid', array(':profession'=>$new_profession, ':company'=>$new_company, ':userid'=>$userid));
    DB::query('INSERT INTO activity_log VALUES ( NULL, :user_id, 0, :event, DEFAULT)', array(':user_id'=>$userid, ':event'=>"更新了工作信息"));
    header("Refresh:0");
}

if (isset($_POST['relation'])) {
    $new_relationship = $_POST['relationship'];
    $new_lookfor = $_POST['lookfor'];

    DB::query('UPDATE users SET relationship=:relationship, lookfor=:lookfor WHERE id=:userid', array(':relationship'=>$new_relationship, ':lookfor'=>$new_lookfor, ':userid'=>$userid));
    DB::query('INSERT INTO activity_log VALUES ( NULL, :user_id, 0, :event, DEFAULT)', array(':user_id'=>$userid, ':event'=>"更新了感情状况"));
    header("Refresh:0");
}

if (isset($_POST['contact'])) {
    $new_mobile = $_POST['mobile'];
    $new_wechat = $_POST['wechat'];
    $new_qq = $_POST['qq'];

    DB::query('UPDATE users SET mobile=:mobile, wechat=:wechat, qq=:qq WHERE id=:userid', array(':mobile'=>$new_mobile, ':wechat'=>$new_wechat, ':qq'=>$new_qq, ':userid'=>$userid));
    DB::query('INSERT INTO activity_log VALUES ( NULL, :user_id, 0, :event, DEFAULT)', array(':user_id'=>$userid, ':event'=>"更新了联系方式"));
    header("Refresh:0");
}

if (isset($_POST['about_me'])) {
    $new_about = $_POST['about_body'];

    DB::query('UPDATE users SET about_me=:about_me WHERE id=:userid', array(':about_me'=>$new_about, ':userid'=>$userid));
    DB::query('INSERT INTO activity_log VALUES ( NULL, :user_id, 0, :event, DEFAULT)', array(':user_id'=>$userid, ':event'=>"更新了个人简介"));

    header("Refresh:0");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>有朋</title>
        <link rel="stylesheet" href="res/css/edit.css">
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
                    <a href="activity.php">活动日志</a>
                    <a href="discover.php">发现</a>
                    <a href="index.php">首页</a>
                    <a href="index.php"><?php echo $username ?></a>
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
                    <li><a href="edit.php" style="color:#1c8adb"><img src="res/edit.svg" />编辑资料</a></li>
                    <li><a href="upload.php"><img src="res/upload.svg" />上传头像</a></li>
                    <li><a href="friends.php"><img src="res/contacts.svg" />我的朋友</a></li>
                    <li><a href="received-message.php"><img src="res/mailbox.svg" />我的私信</a></li>
                </ul>
            </div>
            <div class="main-content">
                <div class="left">
                    <div class="information">
                        <div class="title">基础信息</div>
                            <form action="edit.php" method="post">
                            <label>姓名:</label>
                            <input type="text" name="username" value="<?php echo $username ?>">
                            <br>
                            <label>性别:</label>
                            <input id="gender" type="radio" name="gender" value="男" <?php if($gender == "男"){ echo "checked"; } ?>><small>男</small>
                            <input type="radio" name="gender" value="女" <?php if($gender == "女"){ echo "checked"; } ?> ><small>女</small>
                            <br>
                            <label>家乡:</label>
                            <input type="text" name="hometown" value="<?php echo $hometown ?>" >
                            <br>
                            <label>现所在地:</label>
                            <input type="text" name="livein" value="<?php echo $livein ?>">
                            <br>
                            <label>生日:</label>
                            <input type="date" name="birthday" value=<?php if ($birthday == NULL ) { echo "0000-00-00"; } else { echo $birthday; } ?> required> 
                            <br>
                            <input type="submit" name="foundation" value="修改">
                        </form>
                    </div> 
                    <div class="information">
                        <div class="title">学校信息</div>
                            <form action="edit.php" method="post">
                                <label>小学:</label>
                                <input type="text" name="elementary_school" value="<?php echo $elementary_school ?>">
                                <br>
                                <label>初中:</label>
                                <input type="text" name="junior_school" value="<?php echo $junior_school ?>">
                                <br>
                                <label>高中:</label>
                                <input type="text" name="senior_school" value="<?php echo $senior_school ?>">
                                <br>
                                <label>大学:</label>
                                <input type="text" name="university" value="<?php echo $university ?>">
                                <br><br>
                                <input type="submit" name="school" value="修改">
                            </form>
                    </div>
                    <div class="information">
                        <div class="title">工作信息</div>
                            <form action="edit.php" method="post">
                                <label>职业:</label>
                                <input type="text" name="profession" value="<?php echo $profession ?>">
                                <br>
                                <label>公司:</label>
                                <input type="text" name="company" value="<?php echo $company ?>">
                                <br><br>
                                <input type="submit" name="work" value="修改">
                            </form>
                    </div>
                </div>
                <div class="right">
                    <div class="information">
                        <div class="title">感情状况</div>
                            <form action="edit.php" method="post">
                                <label>想寻找:</label>
                                    <input type="text" name="lookfor" value="<?php echo $lookfor ?>">
                                <br>
                                <label>感情状态:</label>
                                <!--<input type="text" name="relationship" value="">-->
                                <select name="relationship">
                                    <option value="单身" <?php if($relationship == "单身"){ echo "selected"; }else{ echo ""; }?> >单身</option>
                                    <option value="暧昧期" <?php if($relationship == "暧昧期"){ echo "selected"; }else{ echo ""; }?> >暧昧期</option>
                                    <option value="恋爱中" <?php if($relationship == "恋爱中"){ echo "selected"; }else{ echo ""; }?> >恋爱中</option>
                                    <option value="已婚" <?php if($relationship == "已婚"){ echo "selected"; }else{ echo ""; }?> >已婚</option>
                                    <option value="已婚" <?php if($relationship == "有同性伴侣"){ echo "selected"; }else{ echo ""; }?> >有同性伴侣</option>
                                    <option value="分手" <?php if($relationship == "分手"){ echo "selected"; }else{ echo ""; }?> >分手</option>
                                    <option value="离异" <?php if($relationship == "离异"){ echo "selected"; }else{ echo ""; }?> >离异</option>
                                    <option value="比较复杂" <?php if($relationship == "比较复杂"){ echo "selected"; }else{ echo ""; }?> >比较复杂</option>
                                    <option value="丧偶" <?php if($relationship == "丧偶"){ echo "selected"; }else{ echo ""; }?> >丧偶</option>
                                    <option value="交往中，但保留交友空间" <?php if($relationship == "交往中，但保留交友空间"){ echo "selected"; }else{ echo ""; }?> >交往中，但保留交友空间</option>
                                    <option value="---" <?php if($relationship == "---"){ echo "selected"; }else{ echo ""; }?> >---</option>
                                </select>
                                <br>
                                <input type="submit" name="relation" value="修改">
                            </form>
                    </div>     
                    <div class="information">
                        <div class="title">联系方式</div>
                            <form action="edit.php" method="post">
                                <label>手机:</label>
                                <input type="text" name="mobile" value="<?php echo $mobile ?>">
                                <br>
                                <label>微信:</label>
                                <input type="text" name="wechat" value="<?php echo $wechat ?>">
                                <br>
                                <label>QQ:</label>
                                <input type="text" name="qq" value="<?php echo $qq ?>">
                                <br><br>
                                <input type="submit" name="contact" value="修改">
                            </form>
                    </div>
                    <div class="aboutme">
                        <div class="title">关于我</div>
                            <form action="edit.php" method="post">
                                <textarea name="about_body" rows="6" cols="60"><?php echo $about_me ?></textarea><br>
                                <input type="submit" name="about_me" value="修改">
                            </form>
                    </div>
                </div>
        </div>
    </body>
</html>
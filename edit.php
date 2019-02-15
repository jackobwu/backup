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
    $newemail = $_POST['email'];
    $newgender = $_POST['gender'];
    $newbirthday = $_POST['birthday'];
    $newhometown = $_POST['hometown'];
    $newlivein = $_POST['livein'];
    if (strlen($newusername) >= 3 && strlen($newusername) <= 32) {
        if (preg_match('/[a-zA-Z0-9_]+/', $newusername)) {
            if (filter_var($newemail, FILTER_VALIDATE_EMAIL)) {
                if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$newemail))) {
                    DB::query('UPDATE users SET username=:username, email=:email, gender=:gender, birthday=:birthday, hometown=:hometown, livein=:livein WHERE id=:userid', array(':username'=>$newusername, ':email'=>$newemail, 'gender'=>$newgender, ':userid'=>$userid, ':birthday'=>$newbirthday, ':hometown'=>$newhometown, ':livein'=>$newlivein));
                    header("Refresh:0");
                } else {
                    echo 'Email was in used';
                }
            } else {
                echo 'Invalid Email';
            }
        } else {
            echo 'Invalid username';
        }
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
    header("Refresh:0");
}

if (isset($_POST['work'])) {
    $new_profession = $_POST['profession'];
    $new_company = $_POST['company'];

    DB::query('UPDATE users SET profession=:profession, company=:company WHERE id=:userid', array(':profession'=>$new_profession, ':company'=>$new_company, ':userid'=>$userid));
    header("Refresh:0");
}

if (isset($_POST['relation'])) {
    $new_relationship = $_POST['relationship'];
    $new_lookfor = $_POST['lookfor'];

    DB::query('UPDATE users SET relationship=:relationship, lookfor=:lookfor WHERE id=:userid', array(':relationship'=>$new_relationship, ':lookfor'=>$new_lookfor, ':userid'=>$userid));
    header("Refresh:0");
}

if (isset($_POST['contact'])) {
    $new_mobile = $_POST['mobile'];
    $new_wechat = $_POST['wechat'];
    $new_qq = $_POST['qq'];

    DB::query('UPDATE users SET mobile=:mobile, wechat=:wechat, qq=:qq WHERE id=:userid', array(':mobile'=>$new_mobile, ':wechat'=>$new_wechat, ':qq'=>$new_qq, ':userid'=>$userid));
    header("Refresh:0");
}

if (isset($_POST['about_me'])) {
    $new_about = $_POST['about_body'];

    DB::query('UPDATE users SET about_me=:about_me WHERE id=:userid', array(':about_me'=>$new_about, ':userid'=>$userid));
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
                    <li><a href="edit.php">编辑资料</a></li>
                    <li><a href="upload.php">上传头像</a></li>
                    <li><a href="friends.php">我的朋友</a></li>
                    <li><a href="received-message.php">我的私信</a></li>
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
                            <label>电子邮箱:</label>
                            <input type="text" name="email" value="<?php echo $email ?>">
                            <br>
                            <label>性别:</label>
                            <input type="text" name="gender" value="<?php echo $gender ?>">
                            <br>
                            <label>生日:</label>
                            <input type="text" name="birthday" value="<?php echo $birthday ?>">
                            <br>
                            <label>家乡:</label>
                            <input type="text" name="hometown" value="<?php echo $hometown ?>">
                            <br>
                            <label>所在地:</label>
                            <input type="text" name="livein" value="<?php echo $livein ?>">
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
                                <label>感情状态:</label>
                                <input type="text" name="relationship" value="<?php echo $relationship ?>">
                                <br>
                                <label>想寻找:</label>
                                <input type="text" name="lookfor" value="<?php echo $lookfor ?>">
                                <br><br>
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
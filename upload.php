<?php

include 'classes/DB.php';
include 'classes/Login.php';

if (Login::isLoggedIn()) {
    $user_id = Login::isLoggedIn();
    $user_name = DB::query('SELECT username FROM users WHERE id=:id', array(':id'=>$user_id))[0]['username'];
} else {
    header("Location: /login.php");
    exit;
}

$target_dir = "res/uploads/";
$upload = "";
$old_exist = DB::query('SELECT avatar FROM users WHERE id=:id', array(':id'=>$user_id))[0]['avatar'];
if ($old_exist != NULL) {
    $old_avatar = $target_dir . DB::query('SELECT avatar FROM users WHERE id=:id', array(':id'=>$user_id))[0]['avatar'];
} else {
    $old_avatar = NULL;
}

if (isset($_POST['upload'])) {
    if (file_exists($_FILES["profileimg"]["tmp_name"])) {
        $check = getimagesize($_FILES["profileimg"]["tmp_name"]);
        $end = explode(".", $_FILES["profileimg"]["name"]);
        $target_file = $target_dir . uniqid(rand()) . '.' . $end[1];
        if($check !== false) {
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" ) {
                if ($_FILES["profileimg"]["size"] < 2000000) {
                    move_uploaded_file($_FILES["profileimg"]["tmp_name"], $target_file);
                    $temp = explode("/", $target_file);
                    $filename = $temp[2];
                    if ($old_avatar != NULL) {
                        unlink($old_avatar);
                        DB::query('UPDATE users SET avatar = :avatar where id=:id', array(':id'=>$user_id, ':avatar'=>$filename));
                        $upload = "上传成功";
                    } else {
                        DB::query('UPDATE users SET avatar = :avatar where id=:id', array(':id'=>$user_id, ':avatar'=>$filename));
                        $upload = "上传成功";
                    }
                }
            }

        } else {
            echo "仅支持图片类型文件.";
        }
    }
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
                    <li><a active href="upload.php" style="color:#1c8adb"><img src="res/upload.svg" />上传头像</a></li>
                    <li><a href="friends.php"><img src="res/contacts.svg" />我的朋友</a></li>
                    <li><a href="received-message.php"><img src="res/mailbox.svg" />我的私信</a></li>
                </ul>
            </div>
            <div class="main-content">
            <h2>上传头像</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="profileimg">
                <br>
                <input type="submit" name="upload" value="上传">
            </form>
            <p><?php if ($upload) { echo $upload; } ?></p>
            </div>
        </div>
    </body>
</html>
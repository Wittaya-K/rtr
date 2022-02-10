<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
include("./database/conn.php");

if (isset($_COOKIE['rtr_user_id']) != null) {
    header('location:./index.php');
    exit;
}

// SQL GET RTR Name
$sqlGETRTRName = $conn->prepare("SELECT * FROM `rtr_name`");
$sqlGETRTRName->execute();
$resultGETRTRName = $sqlGETRTRName->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/assets/css/bootstrap.css">

    <link rel="stylesheet" href="./assets/assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="./assets/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="./assets/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="./assets/assets/css/app.css">
    <link rel="shortcut icon" href="./assets/assets/images/favicon.svg" type="image/x-icon">

    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</head>
<style>
    body {
        background-image: url('./assets/assets/images/system/BG.png');
        background-size: cover;
    }

    .card-main {
        -webkit-box-shadow: 5px 5px 15px 5px #FFCF5C;
        box-shadow: 5px 5px 15px 5px #FFCF5C;
    }
</style>

<body>
    <!-- Contant -->
    <center>
        <div class="card col-sm-4 mt-5 border-0 card-main">
            <div class="card-header">
                <?php
                if (isset($_GET['LoginFalse'])) {
                ?>
                    <div class="alert alert-light-danger color-danger"><i class="bi bi-exclamation-circle"></i>
                        หมายเลขไม่ได้ลงทะเบียน
                    </div>
                <?php } ?>
                <figure>
                    <blockquote class="blockquote">
                        <p>ระบบร้านอาหาร <span class="iconify" data-icon="dashicons:food" data-width="30" data-height="30"></span></p>
                    </blockquote>
                    <figcaption class="blockquote-footer">
                        <?php echo $resultGETRTRName['name'] ?>
                    </figcaption>
                </figure>
            </div>
            <div class="card-body">
                <form action="./action.php" method="post">
                    <div class="form-floating mb-3">
                        <input type="tel" maxlength="10" class="form-control" id="phonenumber" name="phonenumber" placeholder="เบอร์โทรศัพท์" required>
                        <label for="phonenumber">เบอร์โทรศัพท์</label>
                    </div>
                    <div class="mt-4">
                        <button type="submit" name="btn_login" class="btn btn-block btn-primary">เข้าสู่ระบบ</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="float-start">
                    <img style="width: 100px;" src="../assets/assets/images/system/myhost.png" alt="">
                    <small class="font_copy">Copyright 2021 Myhost Co.,Ltd.</small>
                </div>
                <div class="float-end">
                    <a style="text-decoration: none;" target="_blank" href="https://page.line.me/lyu6240i?openQrModal=true">
                        <span class="iconify" data-icon="cib:line" data-inline="false" data-width="24" data-height="24"></span>
                    </a>
                </div>
            </div>
        </div>
    </center>
    <!-- /Contant -->

    <script src="./assets/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="./assets/assets/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="./assets/assets/vendors/apexcharts/apexcharts.js"></script> -->
    <!-- <script src="./assets/assets/js/pages/dashboard.js"></script> -->

    <script src="./assets/assets/js/pages/horizontal-layout.js"></script>
</body>

</html>
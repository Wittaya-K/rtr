<?php
ob_start();
date_default_timezone_set("Asia/Bangkok");
// session_start();
if (!session_id()) session_start();
include("./database/conn.php");
include("model/datetime.php");

if ($_COOKIE['rtr_user_id'] == null) {
    header('location:./loginPage.php');
    exit;
}

if ($_SESSION['pinset'] == null) {
    header("location:./pinset/index.php");
    exit;
}

$user_id = $_COOKIE['rtr_user_id'];

// SQL GET Data User
$sqlGETDataUser = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_id`=$user_id");
$sqlGETDataUser->execute();
$resultGETDataUser = $sqlGETDataUser->fetch();

$_SESSION["UserID"] = $resultGETDataUser["user_id"];
$_SESSION["Username"] = $resultGETDataUser["user_name"];
$_SESSION["User"] =  $resultGETDataUser["user_title"]."".$resultGETDataUser["user_name"]."".$resultGETDataUser["user_surname"];
// SQL GET Img Logo
$sqlGETImgLogo = $conn->prepare("SELECT * FROM `rtr_img_logo` WHERE `logo_status`='true'");
$sqlGETImgLogo->execute();
$resultGETImgLogo = $sqlGETImgLogo->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant System</title>
    <link rel="shortcut icon" href="assets/assets/images/logo/catering_food_dinner.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="./assets/assets/css/bootstrap.css">

    <link rel="stylesheet" href="./assets/assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="./assets/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="./assets/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="./assets/assets/css/app.css">
    <link href="assets/css/cssmantraplan.css?v=<?php echo filemtime('assets/css/cssmantraplan.css'); ?>" rel="stylesheet" />
    <link href="assets/css/cssTaraPlan.css?v=<?php echo filemtime('assets/css/cssTaraPlan.css'); ?>" rel="stylesheet" />
    <link href="assets/css/cssLoftPlan.css?v=<?php echo filemtime('assets/css/cssLoftPlan.css'); ?>" rel="stylesheet" />
    <!-- <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script> -->
    <script src="assets/assets/vendors/jquery/jquery.min.js"></script>
    <!-- include alertify.css -->
    <link rel="stylesheet" href="alertifyjs/css/alertify.css">
    <script src="alertifyjs/alertify.js"></script>
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

</head>
<style>
    /* @import url('https://fonts.googleapis.com/css2?family=Itim&display=swap'); */
    @import url('https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@200;300;400&display=swap');

    body {
        /* font-family: 'Itim', cursive; */
        font-family: 'Bai Jamjuree', sans-serif;
        background-color: #FFEDE9;
        /* background-image: url('./assets/assets/images/system/BG3.jpg'); */
        /* background-size: cover; */
    }
</style>

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5 hid">
                <div class="header-top">
                    <div class="container">
                        <div class="logo">
                            <a href="index.php"><img src="./assets/assets/images/logo/<?php echo $resultGETImgLogo['logo_name'] ?>" alt="Logo" srcset=""></a>
                        </div>
                        <div class="header-top-right">
                            <a href="#" class="burger-btn d-block d-xl-none">
                                <span class="iconify" data-icon="bx:bxs-user-circle" data-width="30" data-height="30"></span>
                            </a>
                        </div>
                    </div>
                </div>
                <nav class="main-navbar">
                    <div class="container">
                        <ul>
                            <!-- Menu user -->
                            <li class="menu-item  has-sub">
                                <a href="#" class='menu-link'>
                                    <span class="iconify" data-icon="bx:bxs-user" data-width="20" data-height="20"></span>
                                    <span>ผู้ใช้งาน</span>
                                </a>
                                <div class="submenu ">
                                    <div class="submenu-group-wrapper">
                                        <ul class="submenu-group">
                                            <div class="card-body py-4 px-5">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xl">
                                                        <img src="./assets/assets/user/<?php echo $resultGETDataUser['user_pic'] ?>" alt="Face 1">
                                                    </div>
                                                    <div class="ms-3 name">
                                                        <h5 class="font-bold"><?php echo $resultGETDataUser['user_title'] . $resultGETDataUser['user_name'] . ' ' . $resultGETDataUser['user_surname'] ?></h5>
                                                        <h6 class="text-muted mb-0">@<?php echo $resultGETDataUser['user_position'] ?></h6>
                                                    </div>
                                                </div>
                                                <br>
                                                <center>
                                                    <div class="row">
                                                        <div class="col">
                                                            <a href="" class="btn" style="color: green;">
                                                                <span class="iconify" data-icon="bx:bxs-user-pin" data-width="30" data-height="30"></span>
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            <a href="./action.php?lockScreen" class="btn" style="color: #6b61e9;">
                                                                <span class="iconify" data-icon="ant-design:lock-filled" data-width="30" data-height="30"></span>
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            <a href="./action.php?logout" class="btn" style="color: red;">
                                                                <span class="iconify" data-icon="heroicons-outline:logout" data-width="30" data-height="30"></span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </center>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <!-- Menu home -->
                            <li class="menu-item  ">
                                <a href="index.php" class='menu-link'>
                                    <span class="iconify" data-icon="bx:bxs-home" data-width="20" data-height="20"></span>
                                    <span>หน้าหลัก</span>
                                </a>
                            </li>

                            <!-- Menu Shift -->
                            <li class="menu-item  ">
                                <a href="./ManageShift.php" class='menu-link'>
                                    <span class="iconify" data-icon="fluent:shifts-team-24-filled" data-width="20" data-height="20"></span>
                                    <span>การทำงาน</span>
                                </a>
                            </li>

                            <!-- Menu Payment -->
                            <li class="menu-item  ">
                                <a href="./PaymentPage.php" class='menu-link'>
                                    <span class="iconify" data-icon="ic:twotone-payments" data-width="20" data-height="20"></span>
                                    <span>เช็คบิล</span>
                                </a>
                            </li>

                            <?php if ($resultGETDataUser['user_position'] == 'admin') { ?>
                                <!-- Menu Setting -->
                                <li class="menu-item  ">
                                    <a type="button" class='menu-link' data-bs-toggle="modal" data-bs-target="#ModalSetting">
                                        <span class="iconify" data-icon="ant-design:setting-filled" data-width="20" data-height="20"></span>
                                        <span>ตั้งค่า</span>
                                    </a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="ModalSetting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">

                                                    <div class="row">

                                                        <!-- Manege Menu -->
                                                        <div class="col-sm-3">
                                                            <a href="./ManageMenu.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #FF410D;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="bx:bxs-food-menu" data-width="30" data-height="30"></span>
                                                                        <br>
                                                                        <b>เมนู</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <!-- Manege Group foods -->
                                                        <div class="col-sm-3">
                                                            <a href="./ManageGroupMenu.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #FF520D;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="whh:foodtray" data-width="30" data-height="30"></span>
                                                                        <br>
                                                                        <b>หมวดอาหาร</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <!-- Manege Admin -->
                                                        <div class="col-sm-3">
                                                            <a href="./ManageEmplayee.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #FF820D;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="clarity:employee-group-solid" data-width="30" data-height="30"></span>
                                                                        <br>
                                                                        <b>พนักงาน</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <!-- Manege Seat -->
                                                        <div class="col-sm-3">
                                                            <a href="./ManageSeat.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #FFB20D;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="vs:table" data-width="30" data-height="30"></span>
                                                                        <br>
                                                                        <b>ที่นั่ง</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>


                                                        <!-- Manege Pay Type -->
                                                        <div class="col-sm-3">
                                                            <a href="./ManageSeatZone.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #ffc107;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="ic:baseline-chair" data-width="30" data-height="30"></span>
                                                                        <br>
                                                                        <b>โซนที่นั่ง</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <!-- Manege Pay Type -->
                                                        <div class="col-sm-3">
                                                            <a href="./ManagePayType.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #68BD18;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="fluent:payment-16-filled" data-width="30" data-height="30" data-rotate="270deg"></span>
                                                                        <br>
                                                                        <b>การชำระเงิน</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <!-- Manege Pay Type -->
                                                        <div class="col-sm-3">
                                                            <a href="./ManageShiftOpen.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #27AE60;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="mdi:archive-arrow-up-outline" data-width="30" data-height="30"></span>
                                                                        <br>
                                                                        <b>นำเงินเข้าลิ้นชัก</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <div class="col-sm-3">
                                                            <a href="./Management.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #27AE60;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="ion:restaurant-sharp" style="color: #27AE60;" data-width="30" data-height="30"></span>
                                                                        <br>
                                                                        <b>จัดการร้านอาหาร</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                        <!-- Manege Seat -->
                                                        <div class="col-sm-3">
                                                            <a href="./SettingSystem.php">
                                                                <div class="card" style="text-align: center;border: 1px solid #EBEBEB;color: #555555;">
                                                                    <div class="card-body">
                                                                        <span class="iconify" data-icon="uiw:setting" data-width="30" data-height="30"></span>
                                                                        <br>
                                                                        <b>ตั้งค่า</b>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Modal -->
                                </li>
                            <?php } ?>

                        </ul>
                    </div>
                </nav>

            </header>

            <div class="content-wrapper container">

                <!-- <div class="page-heading">
                    <h3>Horizontal Layout</h3>
                </div> -->
                <div class="page-content">
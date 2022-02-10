<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
include("./database/conn.php");

function DateThai($strDate)
{
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    $strHour = date("H", strtotime($strDate));
    $strMinute = date("i", strtotime($strDate));
    $strSeconds = date("s", strtotime($strDate));
    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $strMonthThai = $strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear, $strHour:$strMinute น.";
}

// btn_login
if (isset($_POST['btn_login'])) {
    $phoneNumber = $_POST['phonenumber'];
    $md5 = md5($_POST['phonenumber']);

    // SQL Check Login
    $sqlCheckLogin = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_phone`='$phoneNumber' AND `user_password`='$md5'");
    $sqlCheckLogin->execute();
    $resultCheckLogin = $sqlCheckLogin->fetch();

    if ($resultCheckLogin != null) {
        $user_id = $resultCheckLogin['user_id'];

        $cookie_name = "rtr_user_id";
        $cookie_value = $user_id;
        setcookie($cookie_name, $cookie_value, time() + (86400 * 365), "/");

        header("location:./index.php");
        exit;
    } else {
        header("location:./loginPage.php?LoginFalse");
        exit;
    }
}

// logout
if (isset($_GET['logout'])) {
    $cookie_name = "rtr_user_id";
    $cookie_value = null;
    setcookie($cookie_name, $cookie_value, time() + (86400 * 365), "/");
    $_SESSION['pinset'] = null;

    header("location:./index.php");
}

// addUser
if (isset($_POST['addUser'])) {

    $title = $_POST['title'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $phone = $_POST['phone'];
    $md5 = md5($_POST['phone']);
    $pin = $_POST['pin'];
    $admin = $_COOKIE['rtr_user_id'];
    $date = date('Y-m-d H:i');
    $position = $_POST['position'];

    // SQL Check Phone
    $sqlCheckPhone = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_phone`='$phone'");
    $sqlCheckPhone->execute();
    $resultCheckPhone = $sqlCheckPhone->fetch();

    if ($resultCheckPhone == null) {
        function UploadImg($name, $tmp)
        {
            $images = $name;
            $tmp_dir = $tmp;
            $upload_dir = './assets/assets/user/';
            $imgExt = strtolower(pathinfo($images, PATHINFO_EXTENSION));
            $picRoomdata = "Candy_" . rand(1000, 1000000) . "." . $imgExt;
            move_uploaded_file($tmp_dir, $upload_dir . $picRoomdata);
            return $picRoomdata;
        }

        $img = UploadImg($_FILES['img']['name'], $_FILES['img']['tmp_name']);

        // SQL Insert User
        $sqlInsertUser = $conn->prepare("INSERT INTO `rtr_user`(`user_title`, `user_name`, `user_surname`, `user_phone`, 
        `user_password`, `user_pin`, `user_position`, `user_by_add`, `user_date_add`, `user_pic`) 
        VALUES (:user_title,:user_name,:user_surname,:user_phone,:user_password,:user_pin,:user_position,:user_by_add,:user_date_add,:user_pic)");
        $sqlInsertUser->bindParam('user_title', $title);
        $sqlInsertUser->bindParam('user_name', $name);
        $sqlInsertUser->bindParam('user_surname', $surname);
        $sqlInsertUser->bindParam('user_phone', $phone);
        $sqlInsertUser->bindParam('user_password', $md5);
        $sqlInsertUser->bindParam('user_pin', $pin);
        $sqlInsertUser->bindParam('user_position', $position);
        $sqlInsertUser->bindParam('user_by_add', $admin);
        $sqlInsertUser->bindParam('user_date_add', $date);
        $sqlInsertUser->bindParam('user_pic', $img);
        $sqlInsertUser->execute();

        header("location:./ManageEmplayee.php?regisSuccess");
        exit;
    } else {
        header("location:./ManageEmplayee.php?regisError");
        exit;
    }
}

// editPicUser
if (isset($_POST['editPicUser'])) {
    function UploadImg($name, $tmp)
    {
        $images = $name;
        $tmp_dir = $tmp;
        $upload_dir = './assets/assets/user/';
        $imgExt = strtolower(pathinfo($images, PATHINFO_EXTENSION));
        $picRoomdata = "Candy_" . rand(1000, 1000000) . "." . $imgExt;
        move_uploaded_file($tmp_dir, $upload_dir . $picRoomdata);
        return $picRoomdata;
    }

    $img = UploadImg($_FILES['img']['name'], $_FILES['img']['tmp_name']);
    $id = $_GET['id'];

    // SQL Update Pic User
    $sqlUpdatePicUser = $conn->prepare("UPDATE `rtr_user` SET `user_pic`=:user_pic WHERE `user_id`=:user_id");
    $sqlUpdatePicUser->bindParam("user_pic", $img);
    $sqlUpdatePicUser->bindParam("user_id", $id);
    $sqlUpdatePicUser->execute();

    header("location:./ManageUserOne.php?id=" . $id);
    exit;
}

// editFullNameUser
if (isset($_POST['editFullNameUser'])) {
    $title = $_POST['title'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $id = $_GET['id'];

    // SQL Update Full Name
    $sqlUpdateFullName = $conn->prepare("UPDATE `rtr_user` 
    SET `user_title`= :user_title,`user_name`= :user_name,`user_surname`= :user_surname WHERE `user_id`= :user_id");
    $sqlUpdateFullName->bindParam("user_title", $title);
    $sqlUpdateFullName->bindParam("user_name", $name);
    $sqlUpdateFullName->bindParam("user_surname", $surname);
    $sqlUpdateFullName->bindParam("user_id", $id);
    $sqlUpdateFullName->execute();

    header("location:./ManageUserOne.php?id=" . $id);
    exit;
}

// editPhoneNumberUser
if (isset($_POST['editPhoneNumberUser'])) {
    $phone = $_POST['phone'];
    $id = $_GET['id'];

    // SQL Update phone
    $sqlUpdatePhone = $conn->prepare("UPDATE `rtr_user` SET `user_phone`=:user_phone WHERE `user_id`=:user_id");
    $sqlUpdatePhone->bindParam('user_phone', $phone);
    $sqlUpdatePhone->bindParam('user_id', $id);
    $sqlUpdatePhone->execute();

    header("location:./ManageUserOne.php?id=" . $id);
    exit;
}

// editPositionUser
if (isset($_POST['editPositionUser'])) {
    $position = $_POST['position'];
    $id = $_GET['id'];

    // SQL Update position
    $sqlUpdateposition = $conn->prepare("UPDATE `rtr_user` SET `user_position`=:user_position WHERE `user_id`=:user_id");
    $sqlUpdateposition->bindParam('user_position', $position);
    $sqlUpdateposition->bindParam('user_id', $id);
    $sqlUpdateposition->execute();

    header("location:./ManageUserOne.php?id=" . $id);
    exit;
}

// SetPin
if (isset($_GET['SetPin'])) {
    $pin = $_GET['SetPin'];
    $userID = $_COOKIE['rtr_user_id'];

    // SQL Check PIN
    $sqlCheckPIN = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_id`=$userID AND `user_pin`='$pin'");
    $sqlCheckPIN->execute();
    $resultCheckPIN = $sqlCheckPIN->fetch();

    if ($resultCheckPIN != null) {
        $_SESSION['pinset'] = $pin;
        header("location:./index.php");
        exit;
    } else {
        header("location:./pinset/index.php?PINERROR");
        exit;
    }
}

// addGroupMenu
if (isset($_POST['addGroupMenu'])) {
    $groupmenu = $_POST['groupmenu'];
    $admin = $_COOKIE['rtr_user_id'];
    $datenow = date('Y-m-d H:i');

    // SQL Check Group Menu
    $sqlCheckGroupMenu = $conn->prepare("SELECT * FROM `rtr_group_menu` WHERE `group_name`='$groupmenu'");
    $sqlCheckGroupMenu->execute();
    $resultCheckGroupMenu = $sqlCheckGroupMenu->fetch();

    if ($resultCheckGroupMenu == null) {
        // SQL Insert Group Menu
        $sqlInsertGroupMenu = $conn->prepare("INSERT INTO `rtr_group_menu`(`group_name`, `group_by_add`, `group_date_add`) 
        VALUES (:group_name,:group_by_add,:group_date_add)");
        $sqlInsertGroupMenu->bindParam("group_name", $groupmenu);
        $sqlInsertGroupMenu->bindParam("group_by_add", $admin);
        $sqlInsertGroupMenu->bindParam("group_date_add", $datenow);
        $sqlInsertGroupMenu->execute();

        header("location:./ManageGroupMenu.php");
        exit;
    } else {
        header("location:./ManageGroupMenu.php");
        exit;
    }
}

// addSeatZone
if (isset($_POST['addSeatZone'])) {
    $seat_zone = $_POST['seat_zone'];
    $admin = $_COOKIE['rtr_user_id'];
    $datenow = date('Y-m-d H:i');

    // SQL Check Group Menu
    $sqlCheckGroupMenu = $conn->prepare("SELECT `zone_id` FROM `rtr_zone` WHERE `zone_name`='$seat_zone'");
    $sqlCheckGroupMenu->execute();
    $resultCheckGroupMenu = $sqlCheckGroupMenu->fetch();

    if ($resultCheckGroupMenu == null) {
        // SQL Insert Group Menu
        $sqlInsertGroupMenu = $conn->prepare("INSERT INTO `rtr_zone`(`zone_name`, `zone_by_add`, `zone_date_add`) 
        VALUES (:zone_name,:zone_by_add,:zone_date_add)");
        $sqlInsertGroupMenu->bindParam("zone_name", $seat_zone);
        $sqlInsertGroupMenu->bindParam("zone_by_add", $admin);
        $sqlInsertGroupMenu->bindParam("zone_date_add", $datenow);
        $sqlInsertGroupMenu->execute();

        header("location:./ManageSeatZone.php");
        exit;
    } else {
        header("location:./ManageSeatZone.php");
        exit;
    }
}

//ใส่เงินเข้าลิ้นชัก
if (isset($_POST['addrChange'])) {
    $sh_id = $_POST['sh_id'];
    $change_in = $_POST['change_in'];
    $othermoney = $_POST['othermoney'];
    // $demoC = $_POST['demoC'];

    // SQL Check rtr_shift
    $sqlCheckrtr_shift = $conn->prepare("SELECT `sh_id` FROM `rtr_shift` WHERE `sh_id`='$sh_id'");
    $sqlCheckrtr_shift->execute();
    $resultCheckrtr_shift = $sqlCheckrtr_shift->fetch();

    if ($resultCheckGroupMenu == null) {
        // SQL Insert rtr_shift
        $sqlInsertshift = $conn->prepare("UPDATE `rtr_shift` SET `rtr_change_in`=:rtr_change_in,`rtr_othermoney`=:rtr_othermoney WHERE `sh_id`=:sh_id");
        $sqlInsertshift->bindParam("rtr_change_in", $change_in);
        $sqlInsertshift->bindParam("rtr_othermoney", $othermoney);
        $sqlInsertshift->bindParam('sh_id', $sh_id);
        $sqlInsertshift->execute();

        header("location:./ManageShiftOpen.php");
        exit;
    } else {
        header("location:./ManageShiftOpen.php");
        exit;
    }
}

// EditGroupMenu
if (isset($_POST['EditGroupMenu'])) {
    $id = $_GET['id'];
    $groupmenu = $_POST['groupmenu'];

    // SQL Check Group Menu
    $sqlCheckGroupMenu = $conn->prepare("SELECT * FROM `rtr_group_menu` WHERE `group_name`='$groupmenu'");
    $sqlCheckGroupMenu->execute();
    $resultCheckGroupMenu = $sqlCheckGroupMenu->fetch();

    if ($resultCheckGroupMenu == null) {
        // SQL Update Group Menu
        $sqlUpdateGroupMenu = $conn->prepare("UPDATE `rtr_group_menu` SET `group_name`=:group_name WHERE `group_id`=:group_id");
        $sqlUpdateGroupMenu->bindParam("group_name", $groupmenu);
        $sqlUpdateGroupMenu->bindParam("group_id", $id);
        $sqlUpdateGroupMenu->execute();

        header("location:./ManageGroupMenu.php");
        exit;
    } else {
        header("location:./ManageGroupMenu.php");
        exit;
    }
}

// EditSeatZone
if (isset($_POST['editSeatZone'])) {
    $id = $_GET['id'];
    $seat_zone = $_POST['seat_zone'];

    // SQL Check Group Menu
    $sqlCheckGroupMenu = $conn->prepare("SELECT * FROM `rtr_zone` WHERE `zone_name`='$seat_zone'");
    $sqlCheckGroupMenu->execute();
    $resultCheckGroupMenu = $sqlCheckGroupMenu->fetch();

    if ($resultCheckGroupMenu == null) {
        // SQL Update Group Menu
        $sqlUpdateGroupMenu = $conn->prepare("UPDATE `rtr_zone` SET `zone_name`=:zone_name WHERE `zone_id`=:zone_id");
        $sqlUpdateGroupMenu->bindParam("zone_name", $seat_zone);
        $sqlUpdateGroupMenu->bindParam("zone_id", $id);
        $sqlUpdateGroupMenu->execute();

        header("location:./ManageSeatZone.php");
        exit;
    } else {
        header("location:./ManageSeatZone.php");
        exit;
    }
}

// lockScreen
if (isset($_GET['lockScreen'])) {
    $_SESSION['pinset'] = null;
    header("location:./index.php");
    exit;
}

// addMeNu
if (isset($_POST['addMeNu'])) {
    function UploadImg($name, $tmp)
    {
        $images = $name;
        $tmp_dir = $tmp;
        $upload_dir = './assets/assets/images/food/';
        $imgExt = strtolower(pathinfo($images, PATHINFO_EXTENSION));
        $picRoomdata = "food_" . rand(1000, 1000000) . "." . $imgExt;
        move_uploaded_file($tmp_dir, $upload_dir . $picRoomdata);
        return $picRoomdata;
    }
    if ($_FILES['menu_img']['name']) {
        $menu_img = UploadImg($_FILES['menu_img']['name'], $_FILES['menu_img']['tmp_name']);
    }
    $menu_name = $_POST['menu_name'];
    $menu_price = $_POST['menu_price'];
    $menu_group = $_POST['menu_group'];
    $menu_by_add = $_COOKIE['rtr_user_id'];
    $menu_date_add = date('Y-m-d H:i');

    // SQL Insert Menu
    $sqlInsertMenu = $conn->prepare("INSERT INTO `rtr_menu`(`menu_name`, `menu_price`, `menu_img`, `menu_group`, 
    `menu_by_add`, `menu_date_add`) 
    VALUES (:menu_name,:menu_price,:menu_img,:menu_group,:menu_by_add,:menu_date_add)");
    $sqlInsertMenu->bindParam('menu_name', $menu_name);
    $sqlInsertMenu->bindParam('menu_price', $menu_price);
    $sqlInsertMenu->bindParam('menu_img', $menu_img);
    $sqlInsertMenu->bindParam('menu_group', $menu_group);
    $sqlInsertMenu->bindParam('menu_by_add', $menu_by_add);
    $sqlInsertMenu->bindParam('menu_date_add', $menu_date_add);
    $sqlInsertMenu->execute();

    // SQL GET ID
    $sqlGETID = $conn->prepare("SELECT `menu_id` FROM `rtr_menu` WHERE `menu_name`=:menu_name AND `menu_date_add`=:menu_date_add");
    $sqlGETID->bindParam('menu_name', $menu_name);
    $sqlGETID->bindParam('menu_date_add', $menu_date_add);
    $sqlGETID->execute();
    $resultGETID = $sqlGETID->fetch();

    if (isset($_POST['checkboxgoDetail']) == 'true') {
        header('location:./MenuDetail.php?idcode=' . $resultGETID['menu_id']);
    } else {
        header('location:./ManageMenu.php');
        exit;
    }
}

// addSubMenu
if (isset($_POST['addSubMenu'])) {
    $sm_name = $_POST['subMenu'];
    $sm_price = $_POST['priceSubMenu'];
    $menu_id = $_GET['id'];
    $sm_by_add = $_COOKIE['rtr_user_id'];
    $sm_date_add = date('Y-m-d H:i');

    // SQL Insert SubMenu
    $sqlInsertSubMenu = $conn->prepare("INSERT INTO `rtr_sub_menu`(`menu_id`, `sm_name`, `sm_price`, `sm_by_add`, `sm_date_add`) 
    VALUES (:menu_id,:sm_name,:sm_price,:sm_by_add,:sm_date_add)");
    $sqlInsertSubMenu->bindParam('menu_id', $menu_id);
    $sqlInsertSubMenu->bindParam('sm_name', $sm_name);
    $sqlInsertSubMenu->bindParam('sm_price', $sm_price);
    $sqlInsertSubMenu->bindParam('sm_by_add', $sm_by_add);
    $sqlInsertSubMenu->bindParam('sm_date_add', $sm_date_add);
    $sqlInsertSubMenu->execute();

    header('location:./MenuDetail.php?idcode=' . $menu_id);
    exit;
}

// RemoveSubMenu
if (isset($_GET['RemoveSubMenu'])) {
    $RemoveSubMenu = $_GET['RemoveSubMenu'];
    $id = $_GET['id'];

    // SQL Delete SubMenu
    $sqlDeleteSubMenu = $conn->prepare("DELETE FROM `rtr_sub_menu` WHERE `sm_id`=$RemoveSubMenu AND `menu_id`=$id");
    $sqlDeleteSubMenu->execute();

    header('location:./MenuDetail.php?idcode=' . $id);
    exit;
}

// ModalEditMenuPic
if (isset($_POST['ModalEditMenuPic'])) {
    function UploadImg($name, $tmp)
    {
        $images = $name;
        $tmp_dir = $tmp;
        $upload_dir = './assets/assets/images/food/';
        $imgExt = strtolower(pathinfo($images, PATHINFO_EXTENSION));
        $picRoomdata = "food_" . rand(1000, 1000000) . "." . $imgExt;
        move_uploaded_file($tmp_dir, $upload_dir . $picRoomdata);
        return $picRoomdata;
    }
    if ($_FILES['menu_img']['name']) {
        $menu_img = UploadImg($_FILES['menu_img']['name'], $_FILES['menu_img']['tmp_name']);
    }
    $menu_id = $_GET['id'];

    // SQL Update Menu menu_img
    $sqlUpdateMenu_menu_img = $conn->prepare("UPDATE `rtr_menu` SET `menu_img`=:menu_img WHERE `menu_id`=:menu_id");
    $sqlUpdateMenu_menu_img->bindParam('menu_img', $menu_img);
    $sqlUpdateMenu_menu_img->bindParam('menu_id', $menu_id);
    $sqlUpdateMenu_menu_img->execute();

    header('location:./MenuDetail.php?idcode=' . $menu_id);
    exit;
}

// ModalEditMenuName
if (isset($_POST['ModalEditMenuName'])) {
    $menu_name = $_POST['menu_name'];
    $menu_id = $_GET['id'];
    // SQL Update Menu menu_name
    $sqlUpdateMenu_menu_name = $conn->prepare("UPDATE `rtr_menu` SET `menu_name`=:menu_name WHERE `menu_id`=:menu_id");
    $sqlUpdateMenu_menu_name->bindParam('menu_name', $menu_name);
    $sqlUpdateMenu_menu_name->bindParam('menu_id', $menu_id);
    $sqlUpdateMenu_menu_name->execute();

    header('location:./MenuDetail.php?idcode=' . $menu_id);
    exit;
}

// ModalEditMenuPrice
if (isset($_POST['ModalEditMenuPrice'])) {
    $menu_price = $_POST['menu_price'];
    $menu_id = $_GET['id'];
    // SQL Update Menu menu_price
    $sqlUpdateMenu_menu_price = $conn->prepare("UPDATE `rtr_menu` SET `menu_price`=:menu_price WHERE `menu_id`=:menu_id");
    $sqlUpdateMenu_menu_price->bindParam('menu_price', $menu_price);
    $sqlUpdateMenu_menu_price->bindParam('menu_id', $menu_id);
    $sqlUpdateMenu_menu_price->execute();

    header('location:./MenuDetail.php?idcode=' . $menu_id);
    exit;
}

// ModalEditMenuGroup
if (isset($_POST['ModalEditMenuGroup'])) {
    $menu_group = $_POST['menu_group'];
    $menu_id = $_GET['id'];
    // SQL Update Menu menu_group
    $sqlUpdateMenu_menu_group = $conn->prepare("UPDATE `rtr_menu` SET `menu_group`=:menu_group WHERE `menu_id`=:menu_id");
    $sqlUpdateMenu_menu_group->bindParam('menu_group', $menu_group);
    $sqlUpdateMenu_menu_group->bindParam('menu_id', $menu_id);
    $sqlUpdateMenu_menu_group->execute();

    header('location:./MenuDetail.php?idcode=' . $menu_id);
    exit;
}

// ModalEditMenuStatus
if (isset($_POST['ModalEditMenuStatus'])) {
    $menu_status = $_POST['menu_status'];
    $menu_id = $_GET['id'];
    // SQL Update Menu menu_status
    $sqlUpdateMenu_menu_status = $conn->prepare("UPDATE `rtr_menu` SET `menu_status`=:menu_status WHERE `menu_id`=:menu_id");
    $sqlUpdateMenu_menu_status->bindParam('menu_status', $menu_status);
    $sqlUpdateMenu_menu_status->bindParam('menu_id', $menu_id);
    $sqlUpdateMenu_menu_status->execute();

    header('location:./MenuDetail.php?idcode=' . $menu_id);
    exit;
}

// addSeat
if (isset($_POST['addSeat'])) {
    $seat_number = $_POST['seat'];
    $seat_zone = $_POST['zone'];
    $seat_by_add = $_COOKIE['rtr_user_id'];
    $seat_date_add = date('Y-m-d H:i');

    // SQL Check Number Seat
    $sqlCheckNumber = $conn->prepare("SELECT `seat_number` FROM `rtr_seat` WHERE `seat_number`='$seat_number'");
    $sqlCheckNumber->execute();
    $resultCheckNumber = $sqlCheckNumber->fetch();

    if ($resultCheckNumber == null) {
        // SQL Insert Seat
        $sqlInsertSeat = $conn->prepare("INSERT INTO `rtr_seat`(`seat_number`,`seat_zone`, `seat_by_add`, `seat_date_add`) 
        VALUES (:seat_number,:seat_zone,:seat_by_add,:seat_date_add)");
        $sqlInsertSeat->bindParam('seat_number', $seat_number);
        $sqlInsertSeat->bindParam('seat_zone', $seat_zone);
        $sqlInsertSeat->bindParam('seat_by_add', $seat_by_add);
        $sqlInsertSeat->bindParam('seat_date_add', $seat_date_add);
        $sqlInsertSeat->execute();

        header("location:./ManageSeat.php");
        exit;
    } else {
        header("location:./ManageSeat.php?addseatError");
        exit;
    }
}

// EditStatusSeat
if (isset($_POST['EditStatusSeat'])) {
    $seat_status = $_POST['seat_status'];
    $seat_zone = $_POST['seat_zone'];
    $seat_number = $_POST['seat_number'];
    $seat_id = $_GET['id'];
    // SQL GET Zone All
    $sqlGETZoneAll = $conn->prepare("SELECT `zone_id`,`zone_name` FROM `rtr_zone` WHERE `zone_id`=$seat_zone");
    $sqlGETZoneAll->execute();
    $resultGETZoneAll = $sqlGETZoneAll->fetchAll();
    foreach ($resultGETZoneAll as $keyresultGETZoneAll => $valueresultGETZoneAll) { 
        $fecthseat_zoneid = $valueresultGETZoneAll['zone_id'];
        $fecthseat_zone = $valueresultGETZoneAll['zone_name'];
    }
    // SQL Update Seat Status
    $sqlUpdateSeatStatus = $conn->prepare("UPDATE `rtr_seat` SET `seat_number`=:seat_number,`seat_status`=:seat_status,`zone_id`=:zone_id,`seat_zone`=:seat_zone WHERE `seat_id`=:seat_id");
    $sqlUpdateSeatStatus->bindParam('seat_number', $seat_number);
    $sqlUpdateSeatStatus->bindParam('seat_status', $seat_status);
    $sqlUpdateSeatStatus->bindParam('zone_id', $fecthseat_zoneid);
    $sqlUpdateSeatStatus->bindParam('seat_zone', $fecthseat_zone);
    $sqlUpdateSeatStatus->bindParam('seat_id', $seat_id);
    $sqlUpdateSeatStatus->execute();

    header("location:./ManageSeat.php");
    exit;
}

// AddMenuToCart
if (isset($_POST['AddMenuToCart'])) {
    $id = $_GET['id'];

    // SQL GET Sub Menu
    $sqlGETSubMenu = $conn->prepare("SELECT * FROM `rtr_sub_menu` WHERE `menu_id`=$id");
    $sqlGETSubMenu->execute();
    $resultGETSubMenu = $sqlGETSubMenu->fetchAll();

    // SQL GET Price Menu
    $sqlGETPriceMenu = $conn->prepare("SELECT `menu_price` FROM `rtr_menu` WHERE `menu_id`=$id");
    $sqlGETPriceMenu->execute();
    $resultGETPriceMenu = $sqlGETPriceMenu->fetch();

    $SumSubOrder = null;
    $sumPriceSubOrder = 0;

    foreach ($resultGETSubMenu as $keyresultGETSubMenu => $valueresultGETSubMenu) {
        if ($valueresultGETSubMenu['sm_id'] == $_POST['sm_' . $valueresultGETSubMenu['sm_id']]) {
            if ($_POST['sm_' . $valueresultGETSubMenu['sm_id']] != '') {
                if ($SumSubOrder == null) {
                    $SumSubOrder = $valueresultGETSubMenu['sm_name'];
                } else {
                    $SumSubOrder = $SumSubOrder . ' , ' . $valueresultGETSubMenu['sm_name'];
                }
                $sumPriceSubOrder = $sumPriceSubOrder + (float)$valueresultGETSubMenu['sm_price'];
            }
        }
    }

    $detail_ot = $_POST['detail_ot'];
    $price_ot = $_POST['price_ot'];

    if ($detail_ot != null) {
        $SumSubOrder = $SumSubOrder . ' , ' . $detail_ot;
    }

    if ($SumSubOrder == null) {
        $SumSubOrder = '';
    }

    $sumPriceSubOrder = $sumPriceSubOrder + (float)$price_ot + (float)$resultGETPriceMenu['menu_price'];

    $user_id = $_COOKIE['rtr_user_id'];
    $number = $_POST['number'];
    $finalprice = $number * $sumPriceSubOrder;

    // SQL Check Cart menu
    $sqlCheckCartMenu = $conn->prepare("SELECT * FROM `rtr_cart` WHERE `menu_id`=$id AND `user_id`=$user_id
    AND `menu_detail`='$SumSubOrder' AND `cart_status`='false'");
    $sqlCheckCartMenu->execute();
    $resultCheckCartMenu = $sqlCheckCartMenu->fetch();

    if ($resultCheckCartMenu == null) {
        // SQL Insert Cart
        $sqlInsertCart = $conn->prepare("INSERT INTO `rtr_cart`(`menu_id`, `user_id`, `menu_detail`, `price`, `sum`) 
        VALUES (:menu_id,:user_id,:menu_detail,:price,:sum)");
        $sqlInsertCart->bindParam('menu_id', $id);
        $sqlInsertCart->bindParam('user_id', $user_id);
        $sqlInsertCart->bindParam('menu_detail', $SumSubOrder);
        $sqlInsertCart->bindParam('price', $finalprice);
        $sqlInsertCart->bindParam('sum', $number);
        $sqlInsertCart->execute();
    } else {
        $sumelse = (int)$resultCheckCartMenu['sum'] + $number;
        $priceelse = (float)$resultCheckCartMenu['price'] + $finalprice;
        $idcartelse = $resultCheckCartMenu['cart_id'];

        // SQL Date Cart
        $sqlUpdateCart = $conn->prepare("UPDATE `rtr_cart` SET `price`=:price,`sum`=:sum WHERE `cart_id`=:cart_id");
        $sqlUpdateCart->bindParam('price', $priceelse);
        $sqlUpdateCart->bindParam('sum', $sumelse);
        $sqlUpdateCart->bindParam('cart_id', $idcartelse);
        $sqlUpdateCart->execute();
    }

    if (isset($_GET['idgroupindex'])) {
        // header('location:./index.php?idgroup=' . $_GET['idgroupindex']);
        header('location:./LoftWaterFront.php?idgroup=' . $_GET['idgroupindex']);
        exit;
    } else {
        header('location:./LoftWaterFront.php');
        // header('location:./index.php');
        exit;
    }
}

// saveDiscountB
if (isset($_POST['saveDiscountB'])) {
    $codeGET = str_replace("#", "%23", $_GET['code']);
    $_SESSION['discount'] = $_POST['discountB'];
    $_SESSION['discountType'] = 'B';
    header('location:./PaymentPage.php?code_or=' . $codeGET);
    exit;
}

// saveDiscountP
if (isset($_POST['saveDiscountP'])) {
    $codeGET = str_replace("#", "%23", $_GET['code']);
    $_SESSION['discount'] = $_POST['discountP'];
    $_SESSION['discountType'] = 'P';
    header('location:./PaymentPage.php?code_or=' . $codeGET);
    exit;
}

// cancelDiscount
if (isset($_GET['cancelDiscount'])) {
    $codeGET = str_replace("#", "%23", $_GET['code']);
    $_SESSION['discount'] = null;
    $_SESSION['discountType'] = null;
    header('location:./PaymentPage.php?code_or=' . $codeGET);
    exit;
}

// CancelAllListCart
if (isset($_GET['CancelAllListCart'])) {
    $user_id = $_COOKIE['rtr_user_id'];
    // SQL Cancel All Cart
    $sqlCancelAllCart = $conn->prepare("DELETE FROM `rtr_cart` WHERE `user_id`=$user_id AND `cart_status`='false'");
    $sqlCancelAllCart->execute();
    // header('location:./index.php');
    header('location:./LoftWaterFront.php');
    exit;
}

// EditSumCart
if (isset($_POST['EditSumCart'])) {
    $cartID = $_GET['id'];
    $number = $_POST['number'];

    // SQL GET Cart
    $sqlGETCart = $conn->prepare("SELECT * FROM `rtr_cart` WHERE `cart_id`=$cartID");
    $sqlGETCart->execute();
    $resultGETCart = $sqlGETCart->fetch();

    $numbercart = (int)$resultGETCart['sum'];
    $priceCart = (float)$resultGETCart['price'];
    $x = (float)$resultGETCart['price'] / (int)$resultGETCart['sum'];
    $priceCal = $number * $x;

    // SQL Update Cart
    $sqlUpdateCart = $conn->prepare("UPDATE `rtr_cart` SET `price`=:price,`sum`=:sum WHERE `cart_id`=:cart_id");
    $sqlUpdateCart->bindParam('price', $priceCal);
    $sqlUpdateCart->bindParam('sum', $number);
    $sqlUpdateCart->bindParam('cart_id', $cartID);
    $sqlUpdateCart->execute();
    // header('location:./index.php');
    header('location:./LoftWaterFront.php');
    exit;
}

// CancelListCart
if (isset($_GET['CancelListCart'])) {
    $id = $_GET['CancelListCart'];
    $user_id = $_COOKIE['rtr_user_id'];
    $sqlCancelAllCart = $conn->prepare("DELETE FROM `rtr_cart` WHERE `user_id`=$user_id AND `cart_status`='false' AND `cart_id`=$id");
    $sqlCancelAllCart->execute();
    // header('location:./index.php');
    header('location:./LoftWaterFront.php');
    exit;
}

// ShiftTimeIn
if (isset($_GET['ShiftTimeIn'])) {
    $user_id = $_COOKIE['rtr_user_id'];
    $code = date('Y') . date('m') . date('d') . date('H') . date('i');
    $datenow = date('Y-m-d H:i');
    $datework = date('Y-m-d');

    // $sqlGETdatework = $conn->prepare("SELECT `datework` FROM `rtr_shift` WHERE `datework`=$datework AND `user_id`=$user_id");
    // $sqlGETdatework->execute();
    // $resultGETdatework = $sqlGETdatework->fetch();
    // if($resultGETdatework == null)
    // {
        
    // }

    // SQL Insert Shift
    $sqlInsertShift = $conn->prepare("INSERT INTO `rtr_shift`(`sh_number`, `user_id`, `time_in`, `datework`) 
    VALUES (:sh_number,:user_id,:time_in,:datework)");
    $sqlInsertShift->bindParam('sh_number', $code);
    $sqlInsertShift->bindParam('user_id', $user_id);
    $sqlInsertShift->bindParam('time_in', $datenow);
    $sqlInsertShift->bindParam('datework', $datework);
    $sqlInsertShift->execute();

    // SQL GET User Cookie
    $sqlGETUserCookie = $conn->prepare("SELECT 
                                        `rtr_user`.`user_title`,
                                        `rtr_user`.`user_name`,
                                        `rtr_user`.`user_surname`
                                     FROM `rtr_user` WHERE `user_id`=$user_id");
    $sqlGETUserCookie->execute();
    $resultGETUserCookie = $sqlGETUserCookie->fetch();
    $nameUser = $resultGETUserCookie['user_title'] . $resultGETUserCookie['user_name'] . ' ' . $resultGETUserCookie['user_surname'];

    // SQL GET Notify
    $sqlGETNotify = $conn->prepare("SELECT * FROM `rtr_token_notify`");
    $sqlGETNotify->execute();
    $resultGETNotify = $sqlGETNotify->fetch();

    // if ($resultGETNotify['status'] == 'true') {
    //     // Line Notify
    //     ini_set('display_errors', 1);
    //     ini_set('display_startup_errors', 1);
    //     error_reporting(E_ALL);
    //     $sToken = $resultGETNotify['token'];
    //     $sMessage = "เริ่มการทำงาน" . "\n"
    //         . $resultGETUserCookie['user_title'] . $resultGETUserCookie['user_name'] . ' ' . $resultGETUserCookie['user_surname'] . "\n"
    //         . "เมื่อ " . DateThai($datenow);
    //     $chOne = curl_init();
    //     curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    //     curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
    //     curl_setopt($chOne, CURLOPT_POST, 1);
    //     curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
    //     $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken . '',);
    //     curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
    //     $result = curl_exec($chOne);
    //     if (curl_error($chOne)) {
    //         echo 'error:' . curl_error($chOne);
    //     } else {
    //         $result_ = json_decode($result, true);
    //         echo "status : " . $result_['status'];
    //         echo "message : " . $result_['message'];
    //     }
    //     curl_close($chOne);
    //     // Line Notify
    // }
    header('location:./index.php');
    exit;
}

// closeShift
if (isset($_GET['closeShift'])) {
    $pin = $_GET['pin'];
    $user_id = $_COOKIE['rtr_user_id'];
    $time_out = date('Y-m-d H:i');
    // SQL Check pin
    $sqlCheckPIN = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_pin`='$pin' AND `user_id`=$user_id");
    $sqlCheckPIN->execute();
    $resultCheckPIN = $sqlCheckPIN->fetch();
    if ($resultCheckPIN != null) {
        // SQL Update Shift
        $sqlUpdateShift = $conn->prepare("UPDATE `rtr_shift` SET `time_out`=:time_out,`sh_status`='true' 
        WHERE `user_id`=:user_id AND `sh_status`='false'");
        $sqlUpdateShift->bindParam('time_out', $time_out);
        $sqlUpdateShift->bindParam('user_id', $user_id);
        $sqlUpdateShift->execute();

        // SQL GET User Cookie
        $sqlGETUserCookie = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_id`=$user_id");
        $sqlGETUserCookie->execute();
        $resultGETUserCookie = $sqlGETUserCookie->fetch();
        $nameUser = $resultGETUserCookie['user_title'] . $resultGETUserCookie['user_name'] . ' ' . $resultGETUserCookie['user_surname'];

        // SQL GET Notify
        $sqlGETNotify = $conn->prepare("SELECT * FROM `rtr_token_notify`");
        $sqlGETNotify->execute();
        $resultGETNotify = $sqlGETNotify->fetch();

        if ($resultGETNotify['status'] == 'true') {
            // Line Notify
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            $sToken = $resultGETNotify['token'];
            $sMessage = "สิ้นสุดการทำงาน" . "\n"
                . $resultGETUserCookie['user_title'] . $resultGETUserCookie['user_name'] . ' ' . $resultGETUserCookie['user_surname'] . "\n"
                . "เมื่อ " . DateThai($time_out);
            $chOne = curl_init();
            curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
            curl_setopt($chOne, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($chOne, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($chOne, CURLOPT_POST, 1);
            curl_setopt($chOne, CURLOPT_POSTFIELDS, "message=" . $sMessage);
            $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $sToken . '',);
            curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($chOne, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($chOne);
            if (curl_error($chOne)) {
                echo 'error:' . curl_error($chOne);
            } else {
                $result_ = json_decode($result, true);
                echo "status : " . $result_['status'];
                echo "message : " . $result_['message'];
            }
            curl_close($chOne);
            // Line Notify
        }

        header('location:./ManageShift.php');
        exit;
    } else {
        header('location:./ManageShift.php?PINERROR');
        exit;
    }
}

// EditRTRname
if (isset($_POST['EditRTRname'])) {
    $name = $_POST['name'];
    // SQL Update RTR Name
    $sqlUpdateRTRName = $conn->prepare("UPDATE `rtr_name` SET `name`=:name");
    $sqlUpdateRTRName->bindParam('name', $name);
    $sqlUpdateRTRName->execute();
    header('location:./SettingSystem.php');
    exit;
}

// EditRTRStatusNotify
if (isset($_POST['EditRTRStatusNotify'])) {
    $rtrstatusnotify = $_POST['rtrstatusnotify'];
    // SQL Update RTR Status Notify
    $sqlUpdateRTRStatusNotify = $conn->prepare("UPDATE `rtr_token_notify` SET `status`=:status");
    $sqlUpdateRTRStatusNotify->bindParam('status', $rtrstatusnotify);
    $sqlUpdateRTRStatusNotify->execute();
    header('location:./SettingSystem.php');
    exit;
}

// EditRTRToken
if (isset($_POST['EditRTRToken'])) {
    $token = $_POST['token'];
    // SQL Update RTR Token
    $sqlUpdateRTRToken = $conn->prepare("UPDATE `rtr_token_notify` SET `token`=:token");
    $sqlUpdateRTRToken->bindParam('token', $token);
    $sqlUpdateRTRToken->execute();
    header('location:./SettingSystem.php');
    exit;
}

// Shiftclose
if (isset($_POST['Shiftclose'])) {
    $getmoneyout = $_POST['getmoneyout'];
    // echo $getmoneyout;
    $sqlGetmoneyout = $conn->prepare("SELECT 
                                    `rtr_shift`.`rtr_change_in` +
                                    `rtr_shift`.`rtr_total_amount` +
                                    `rtr_shift`.`rtr_sales` +
                                    `rtr_shift`.`rtr_cash` +
                                    `rtr_shift`.`rtr_credit_card` +
                                    `rtr_shift`.`rtr_transferpayment` +
                                    `rtr_shift`.`rtr_othermoney` +
                                    `rtr_shift`.`rtr_money_out` +
                                    `rtr_shift`.`rtr_othermoney_in` +
                                    `rtr_shift`.`rtr_expenses` +
                                    `rtr_shift`.`rtr_othermoney_out` - '$getmoneyout' AS Cal
                                FROM `db_rtr`.`rtr_shift`
                                WHERE `rtr_shift`.`datework`='".date('Y-m-d')."'");
    $sqlGetmoneyout->execute();
    $resultGetmoneyout = $sqlGetmoneyout->fetch();
    // echo $resultGetmoneyout['Cal'];
    // SQL Update RTR Token
    $sqlUpdateGetmoneyout= $conn->prepare("UPDATE `db_rtr`.`rtr_shift`
                                            SET
                                                `rtr_change_in` = '0.00',
                                                `rtr_total_amount` = '0.00',
                                                `rtr_sales` = '0.00',
                                                `rtr_cash` = '0.00',
                                                `rtr_credit_card` = '0.00',
                                                `rtr_transferpayment` = '0.00',
                                                `rtr_othermoney` = '0.00',
                                                `rtr_money_out` = '0.00',
                                                `rtr_othermoney_in` = '0.00',
                                                `rtr_expenses` = '0.00',
                                                `rtr_othermoney_out` = '0.00',
                                                `rtr_withdraw` = '$getmoneyout'
                                            WHERE `rtr_shift`.`datework`='".date('Y-m-d')."'");
    $sqlUpdateGetmoneyout->execute();
    header('location:./ManageShift.php');
    exit;
}

// GETOrderTable
if (isset($_POST['GETOrderTable'])) {
    // SQL GET Shift
    $sqlGETShift = $conn->prepare("SELECT * FROM `rtr_shift` WHERE `sh_status`='false'");
    $sqlGETShift->execute();
    $resultGETShift = $sqlGETShift->fetch();
    // Gen Code
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $od_code = '#' . generateRandomString();
    $sh_number = $resultGETShift['sh_id'];
    $od_q = $_POST['q'];
    $seat_id = $_POST['numberSeat'];
    if (isset($_SESSION['discountType'])) {
        $type_discount = $_SESSION['discountType'];
        $order_discount = $_SESSION['discount'];
    } else {
        $type_discount = null;
        $order_discount = null;
    }
    $od_by_add = $_COOKIE['rtr_user_id'];
    $od_date_add = date('Y-m-d H:i');
    $date = date('Y-m-d');
    $home = 'false';
    $sum_price = $_POST['sumprice'];

    // SQL Insert Order
    $sqlInsertOrder = $conn->prepare("INSERT INTO `rtr_order`(`sh_number`, `od_code`, `od_q`, `seat_id`, `home`, `order_discount`, 
    `type_discount`, `sum_price`, `od_by_add`, `od_date_add`, `date`) 
    VALUES (:sh_number,:od_code,:od_q,:seat_id,:home,:order_discount,:type_discount,:sum_price,:od_by_add,:od_date_add,:date)");
    $sqlInsertOrder->bindParam('sh_number', $sh_number);
    $sqlInsertOrder->bindParam('od_code', $od_code);
    $sqlInsertOrder->bindParam('od_q', $od_q);
    $sqlInsertOrder->bindParam('seat_id', $seat_id);
    $sqlInsertOrder->bindParam('home', $home);
    $sqlInsertOrder->bindParam('order_discount', $order_discount);
    $sqlInsertOrder->bindParam('type_discount', $type_discount);
    $sqlInsertOrder->bindParam('sum_price', $sum_price);
    $sqlInsertOrder->bindParam('od_by_add', $od_by_add);
    $sqlInsertOrder->bindParam('od_date_add', $od_date_add);
    $sqlInsertOrder->bindParam('date', $date);
    $sqlInsertOrder->execute();

    // SQL GET Cart
    $sqlGETCart = $conn->prepare("SELECT * FROM `rtr_cart` WHERE `cart_status`='false' AND `user_id`=$od_by_add");
    $sqlGETCart->execute();
    $resultGETCart = $sqlGETCart->fetchAll();

    foreach ($resultGETCart as $keyresultGETCart => $valueresultGETCart) {
        // SQL Insert Sub Order
        $sqlInsertSubOrder = $conn->prepare("INSERT INTO `rtr_sub_order`(`od_code`, `menu_id`, `user_id`, `menu_detail`, `price`, `sum`) 
        VALUES (:od_code,:menu_id,:user_id,:menu_detail,:price,:sum)");
        $sqlInsertSubOrder->bindParam('od_code', $od_code);
        $sqlInsertSubOrder->bindParam('menu_id', $valueresultGETCart['menu_id']);
        $sqlInsertSubOrder->bindParam('user_id', $valueresultGETCart['user_id']);
        $sqlInsertSubOrder->bindParam('menu_detail', $valueresultGETCart['menu_detail']);
        $sqlInsertSubOrder->bindParam('price', $valueresultGETCart['price']);
        $sqlInsertSubOrder->bindParam('sum', $valueresultGETCart['sum']);
        $sqlInsertSubOrder->execute();
    }

    // SQL Update Cart True
    $sqlUpdateCartTrue = $conn->prepare("UPDATE `rtr_cart` SET `cart_status`='true' WHERE `cart_status`='false' AND `user_id`=$od_by_add");
    $sqlUpdateCartTrue->execute();

    $_SESSION['discountType'] = null;
    $_SESSION['discount'] = null;
    $_SESSION['od_code'] = $od_code;
    header('location:./index.php?GETOrderSuccess');
    exit;
}

// GETOrderBackHome
if (isset($_POST['GETOrderBackHome'])) {
    // SQL GET Shift
    $sqlGETShift = $conn->prepare("SELECT * FROM `rtr_shift` WHERE `sh_status`='false'");
    $sqlGETShift->execute();
    $resultGETShift = $sqlGETShift->fetch();
    // Gen Code
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $od_code = '#' . generateRandomString();
    $sh_number = $resultGETShift['sh_id'];
    $od_q = $_POST['q'];
    if (isset($_SESSION['discountType'])) {
        $type_discount = $_SESSION['discountType'];
        $order_discount = $_SESSION['discount'];
    } else {
        $type_discount = null;
        $order_discount = null;
    }
    $od_by_add = $_COOKIE['rtr_user_id'];
    $od_date_add = date('Y-m-d H:i');
    $date = date('Y-m-d');
    $home = 'true';
    $sum_price = $_POST['sumprice'];
    $name_payer = $_POST['name_payer'];
    $phone_payer = $_POST['phone_payer'];

    // SQL Insert Order
    $sqlInsertOrder = $conn->prepare("INSERT INTO `rtr_order`(`sh_number`, `od_code`, `od_q`, `home`, `order_discount`, 
    `type_discount`, `sum_price`, `od_by_add`, `od_date_add`, `date`, `name_payer`, `phone_payer`) 
    VALUES (:sh_number,:od_code,:od_q,:home,:order_discount,:type_discount,:sum_price,:od_by_add,:od_date_add,:date,:name_payer,:phone_payer)");
    $sqlInsertOrder->bindParam('sh_number', $sh_number);
    $sqlInsertOrder->bindParam('od_code', $od_code);
    $sqlInsertOrder->bindParam('od_q', $od_q);
    $sqlInsertOrder->bindParam('home', $home);
    $sqlInsertOrder->bindParam('order_discount', $order_discount);
    $sqlInsertOrder->bindParam('type_discount', $type_discount);
    $sqlInsertOrder->bindParam('sum_price', $sum_price);
    $sqlInsertOrder->bindParam('od_by_add', $od_by_add);
    $sqlInsertOrder->bindParam('od_date_add', $od_date_add);
    $sqlInsertOrder->bindParam('date', $date);
    $sqlInsertOrder->bindParam('name_payer', $name_payer);
    $sqlInsertOrder->bindParam('phone_payer', $phone_payer);
    $sqlInsertOrder->execute();

    // SQL GET Cart
    $sqlGETCart = $conn->prepare("SELECT * FROM `rtr_cart` WHERE `cart_status`='false' AND `user_id`=$od_by_add");
    $sqlGETCart->execute();
    $resultGETCart = $sqlGETCart->fetchAll();

    foreach ($resultGETCart as $keyresultGETCart => $valueresultGETCart) {
        // SQL Insert Sub Order
        $sqlInsertSubOrder = $conn->prepare("INSERT INTO `rtr_sub_order`(`od_code`, `menu_id`, `user_id`, `menu_detail`, `price`, `sum`) 
        VALUES (:od_code,:menu_id,:user_id,:menu_detail,:price,:sum)");
        $sqlInsertSubOrder->bindParam('od_code', $od_code);
        $sqlInsertSubOrder->bindParam('menu_id', $valueresultGETCart['menu_id']);
        $sqlInsertSubOrder->bindParam('user_id', $valueresultGETCart['user_id']);
        $sqlInsertSubOrder->bindParam('menu_detail', $valueresultGETCart['menu_detail']);
        $sqlInsertSubOrder->bindParam('price', $valueresultGETCart['price']);
        $sqlInsertSubOrder->bindParam('sum', $valueresultGETCart['sum']);
        $sqlInsertSubOrder->execute();
    }

    // SQL Update Cart True
    $sqlUpdateCartTrue = $conn->prepare("UPDATE `rtr_cart` SET `cart_status`='true' WHERE `cart_status`='false' AND `user_id`=$od_by_add");
    $sqlUpdateCartTrue->execute();

    $_SESSION['discountType'] = null;
    $_SESSION['discount'] = null;
    $_SESSION['od_code'] = $od_code;
    $code_Order = str_replace("#", "%23", $od_code);
    header('location:./PaymentPage.php?code_or=' . $code_Order);
    exit;
}

// GETOrderMoreTable
if (isset($_POST['GETOrderMoreTable'])) {
    $od_by_add = $_COOKIE['rtr_user_id'];
    $seat_id = $_POST['numberSeat'];
    $od_q = $_POST['q'];
    $bell = 'false';
    $stauts_serve = 'false';
    $cook_stauts = 'false';
    if (isset($_SESSION['discountType'])) {
        $type_discount = $_SESSION['discountType'];
        $order_discount = $_SESSION['discount'];
    } else {
        $type_discount = null;
        $order_discount = null;
    }
    $sum_price = $_POST['sumprice'];

    // SQL GET Order
    $sqlGETOrder = $conn->prepare("SELECT * FROM `rtr_order` WHERE `seat_id`=$seat_id AND `od_status`='false'");
    $sqlGETOrder->execute();
    $resultGETOrder = $sqlGETOrder->fetch();
    $od_code = $resultGETOrder['od_code'];
    $dbprice = (float)$resultGETOrder['sum_price'] + (float)$sum_price;

    // SQL Update Order
    $sqlUpdateOrder = $conn->prepare("UPDATE `rtr_order` SET `od_q`=:od_q,`sum_price`=:sum_price,
    `cook_stauts`=:cook_stauts,`stauts_serve`=:stauts_serve,`bell`=:bell WHERE `od_code`=:od_code");
    $sqlUpdateOrder->bindParam('od_q', $od_q);
    $sqlUpdateOrder->bindParam('sum_price', $dbprice);
    $sqlUpdateOrder->bindParam('cook_stauts', $cook_stauts);
    $sqlUpdateOrder->bindParam('stauts_serve', $stauts_serve);
    $sqlUpdateOrder->bindParam('bell', $bell);
    $sqlUpdateOrder->bindParam('od_code', $od_code);
    $sqlUpdateOrder->execute();

    // SQL GET Cart
    $sqlGETCart = $conn->prepare("SELECT * FROM `rtr_cart` WHERE `cart_status`='false' AND `user_id`=$od_by_add");
    $sqlGETCart->execute();
    $resultGETCart = $sqlGETCart->fetchAll();

    foreach ($resultGETCart as $keyresultGETCart => $valueresultGETCart) {
        // SQL Insert Sub Order
        $sqlInsertSubOrder = $conn->prepare("INSERT INTO `rtr_sub_order`(`od_code`, `menu_id`, `user_id`, `menu_detail`, `price`, `sum`) 
    VALUES (:od_code,:menu_id,:user_id,:menu_detail,:price,:sum)");
        $sqlInsertSubOrder->bindParam('od_code', $od_code);
        $sqlInsertSubOrder->bindParam('menu_id', $valueresultGETCart['menu_id']);
        $sqlInsertSubOrder->bindParam('user_id', $valueresultGETCart['user_id']);
        $sqlInsertSubOrder->bindParam('menu_detail', $valueresultGETCart['menu_detail']);
        $sqlInsertSubOrder->bindParam('price', $valueresultGETCart['price']);
        $sqlInsertSubOrder->bindParam('sum', $valueresultGETCart['sum']);
        $sqlInsertSubOrder->execute();
    }

    // SQL Update Cart True
    $sqlUpdateCartTrue = $conn->prepare("UPDATE `rtr_cart` SET `cart_status`='true' WHERE `cart_status`='false' AND `user_id`=$od_by_add");
    $sqlUpdateCartTrue->execute();

    $_SESSION['discountType'] = null;
    $_SESSION['discount'] = null;
    $_SESSION['od_code'] = $od_code;
    header('location:./index.php?GETOrderSuccess');
    exit;
}

// ClearAllTreeTable
if (isset($_GET['ClearAllTreeTable'])) {
    // SQL Delete Cart
    $sql = $conn->prepare("DELETE FROM `rtr_cart`");
    $sql->execute();
    // SQL Delete Order
    $sql = $conn->prepare("DELETE FROM `rtr_order`");
    $sql->execute();
    // SQL Delete Sub Order
    $sql = $conn->prepare("DELETE FROM `rtr_sub_order`");
    $sql->execute();

    header('location:./index.php?ClearAllTreeTable');
    exit;
}

// GetOrderGoCookking
if (isset($_GET['GetOrderGoCookking'])) {
    $id = $_GET['GetOrderGoCookking'];
    // SQL Update Order
    $sqlUpdateOrder = $conn->prepare("UPDATE `rtr_order` SET `cook_stauts`='true' WHERE `od_id`=$id");
    $sqlUpdateOrder->execute();
    // SQL GET Code
    $sqlGETCode = $conn->prepare("SELECT `od_code` FROM `rtr_order` WHERE `od_id`=$id");
    $sqlGETCode->execute();
    $resultGETCode = $sqlGETCode->fetch();
    $codeOrder = $resultGETCode['od_code'];
    // SQL Update Sub Order
    $sqlUpdateSubOrder = $conn->prepare("UPDATE `rtr_sub_order` SET `cook_stauts`='true' WHERE `od_code`='$codeOrder' AND `cook_stauts`='false'");
    $sqlUpdateSubOrder->execute();
    header('location:./kitchenPage.php');
    exit;
}

// ToServeFood
if (isset($_GET['ToServeFood'])) {
    $id = $_GET['ToServeFood'];
    // SQL Update Order
    $sqlUpdateOrder = $conn->prepare("UPDATE `rtr_order` SET `stauts_serve`='true' WHERE `od_id`=$id");
    $sqlUpdateOrder->execute();
    // SQL GET Code
    $sqlGETCode = $conn->prepare("SELECT `od_code` FROM `rtr_order` WHERE `od_id`=$id");
    $sqlGETCode->execute();
    $resultGETCode = $sqlGETCode->fetch();
    $codeOrder = $resultGETCode['od_code'];
    // SQL Update Sub Order
    $sqlUpdateSubOrder = $conn->prepare("UPDATE `rtr_sub_order` SET `serve`='true' WHERE `od_code`='$codeOrder' AND `serve`='false'");
    $sqlUpdateSubOrder->execute();
    header('location:./kitchenPage.php');
    exit;
}

// UpdateBell
if (isset($_GET['UpdateBell'])) {
    // SQL Update Bell
    $sqlUpdateBell = $conn->prepare("UPDATE `rtr_order` SET `bell`='true'");
    $sqlUpdateBell->execute();
    header('location:./kitchenPage.php');
    exit;
}

// tableToPayMent
if (isset($_GET['tableToPayMent'])) {
    $idSeat = $_GET['tableToPayMent'];
    // SQL GET Order
    $sqlGETOrder = $conn->prepare("SELECT * FROM `rtr_order` WHERE `seat_id`=$idSeat AND `od_status`='false';");
    $sqlGETOrder->execute();
    $resultGETOrder = $sqlGETOrder->fetch();
    $code_Order = $resultGETOrder['od_code'];
    $code_Order = str_replace("#", "%23", $code_Order);
    header("location:./PaymentPage.php?code_or=" . $code_Order);
    exit;
}

// EditServicecharge
if (isset($_POST['EditServicecharge'])) {
    $servicech = $_POST['servicech'];
    $servicech_status = $_POST['servicech_status'];

    // SQL Update Service Charge
    $sqlUpdateServiceCharge = $conn->prepare("UPDATE `rtr_servicech` SET `servicech`=:servicech,`servicech_status`=:servicech_status");
    $sqlUpdateServiceCharge->bindParam('servicech', $servicech);
    $sqlUpdateServiceCharge->bindParam('servicech_status', $servicech_status);
    $sqlUpdateServiceCharge->execute();

    header('location:./SettingSystem.php');
    exit;
}

// addserviceCharge
if (isset($_GET['addserviceCharge'])) {
    $code_Order = str_replace("#", "%23", $_GET['code']);
    // SQL GET servicech
    $sqlGETServicech = $conn->prepare("SELECT * FROM `rtr_servicech`");
    $sqlGETServicech->execute();
    $resultGETServicech = $sqlGETServicech->fetch();
    $serch = $_SESSION['serviceCharge'];
    if ($serch == null) {
        $_SESSION['serviceCharge'] = $resultGETServicech['servicech'];
    } else {
        $_SESSION['serviceCharge'] = null;
    }
    header("location:./PaymentPage.php?code_or=" . $code_Order);
    exit;
}

// vatout
if (isset($_GET['vatout'])) {
    $code_Order = str_replace("#", "%23", $_GET['code']);
    $_SESSION['vat'] = 'vatout';
    header("location:./PaymentPage.php?code_or=" . $code_Order);
    exit;
}

// vatin
if (isset($_GET['vatin'])) {
    $code_Order = str_replace("#", "%23", $_GET['code']);
    $_SESSION['vat'] = 'vatin';
    header("location:./PaymentPage.php?code_or=" . $code_Order);
    exit;
}

// cancelvat
if (isset($_GET['cancelvat'])) {
    $code_Order = str_replace("#", "%23", $_GET['code']);
    $_SESSION['vat'] = null;
    header("location:./PaymentPage.php?code_or=" . $code_Order);
    exit;
}

// paymentOrder
if (isset($_GET['paymentOrder'])) {
    $monmeyget = $_GET['monmeyget'];
    $sumprice = $_GET['sumprice'];
    $moneyRecov = $_GET['moneyRecov'];
    $codeOrder = $_GET['codeOrder'];
    $payType = $_GET['payType'];
    $payment_status = 'true';

    if (isset($_SESSION['discountType']) != null) {
        $discount = $_SESSION['discount'];
        $discountType = $_SESSION['discountType'];
    } else {
        $discount = null;
        $discountType = null;
    }
    if (isset($_SESSION['serviceCharge']) != null) {
        $serviceCharge = $_SESSION['serviceCharge'];
    } else {
        $serviceCharge = null;
    }
    if (isset($_SESSION['vat']) != null) {
        $vat = $_SESSION['vat'];
    } else {
        $vat = null;
    }

    // SQL Update Order
    $sqlUpdateOrder = $conn->prepare("UPDATE `rtr_order` SET `order_discount`=:order_discount,`type_discount`=:type_discount,
    `real_price`=:real_price,`get_price`=:get_price,`chang_price`=:chang_price,`payment_status`=:payment_status,
    `payment_type`=:payment_type,`vat`=:vat,`service_charge`=:service_charge WHERE `od_code`=:od_code");
    $sqlUpdateOrder->bindParam('order_discount', $discount);
    $sqlUpdateOrder->bindParam('type_discount', $discountType);
    $sqlUpdateOrder->bindParam('real_price', $sumprice);
    $sqlUpdateOrder->bindParam('get_price', $monmeyget);
    $sqlUpdateOrder->bindParam('chang_price', $moneyRecov);
    $sqlUpdateOrder->bindParam('payment_status', $payment_status);
    $sqlUpdateOrder->bindParam('payment_type', $payType);
    $sqlUpdateOrder->bindParam('vat', $vat);
    $sqlUpdateOrder->bindParam('service_charge', $serviceCharge);
    $sqlUpdateOrder->bindParam('od_code', $codeOrder);
    $sqlUpdateOrder->execute();

    $code_Order = str_replace("#", "%23", $codeOrder);
    header("location:./PaymentPage.php?code_or=" . $code_Order);
    exit;
}

// confirmGETProduct
if (isset($_GET['confirmGETProduct'])) {
    $code = $_GET['code'];
    $code_Order = str_replace("#", "%23", $code);

    // SQL Update order Success
    $sqlUpdateOrderSuccess = $conn->prepare("UPDATE `rtr_order` SET `od_status`='true' WHERE `od_code`='$code'");
    $sqlUpdateOrderSuccess->execute();

    header("location:./PaymentPage.php?code_or=" . $code_Order);
    exit;
}

// addlogo
if (isset($_POST['addlogo'])) {
    function UploadImg($name, $tmp)
    {
        $images = $name;
        $tmp_dir = $tmp;
        $upload_dir = './assets/assets/images/logo/';
        $imgExt = strtolower(pathinfo($images, PATHINFO_EXTENSION));
        $picRoomdata = "Candy_" . rand(1000, 1000000) . "." . $imgExt;
        move_uploaded_file($tmp_dir, $upload_dir . $picRoomdata);
        return $picRoomdata;
    }

    $logo_name = UploadImg($_FILES['file']['name'], $_FILES['file']['tmp_name']);
    $logo_status = 'true';
    $logo_by_add = $_COOKIE['rtr_user_id'];
    $logo_date_add = date('Y-m-d H:i');

    // SQL Update All False
    $sqlUpdateAllFalse = $conn->prepare("UPDATE `rtr_img_logo` SET `logo_status`='false'");
    $sqlUpdateAllFalse->execute();

    // SQL Insert Img Logo
    $sqlInsertImgLogo = $conn->prepare("INSERT INTO `rtr_img_logo`(`logo_name`, `logo_status`, `logo_by_add`, `logo_date_add`) 
    VALUES (:logo_name,:logo_status,:logo_by_add,:logo_date_add)");
    $sqlInsertImgLogo->bindParam( 'logo_name' , $logo_name );
    $sqlInsertImgLogo->bindParam( 'logo_status' , $logo_status );
    $sqlInsertImgLogo->bindParam( 'logo_by_add' , $logo_by_add );
    $sqlInsertImgLogo->bindParam( 'logo_date_add' , $logo_date_add );
    $sqlInsertImgLogo->execute();

    header('location:./SettingSystem.php');
    exit;
}

// editlogo
if (isset($_GET['editlogo'])) {
    $id = $_GET['id'];

    // SQL Update All False
    $sqlUpdateAllFalse = $conn->prepare("UPDATE `rtr_img_logo` SET `logo_status`='false'");
    $sqlUpdateAllFalse->execute();

    // SQL Update All False
    $sqlUpdateFalse = $conn->prepare("UPDATE `rtr_img_logo` SET `logo_status`='true' WHERE `logo_id`=$id");
    $sqlUpdateFalse->execute();

    header('location:./SettingSystem.php');
    exit;
}

// addPayType
if (isset($_POST['addPayType'])) {
    $pay_type_name = $_POST['paytype'];
    $pay_type_get_money = $_POST['statusgetmoney'];

    // SQL Insert Pay Type
    $sqlInsertPayType = $conn->prepare("INSERT INTO `rtr_pay_type`(`pay_type_name`, `pay_type_get_money`) 
    VALUES (:pay_type_name,:pay_type_get_money)");
    $sqlInsertPayType->bindParam( 'pay_type_name' , $pay_type_name );
    $sqlInsertPayType->bindParam( 'pay_type_get_money' , $pay_type_get_money );
    $sqlInsertPayType->execute();

    header('location:./ManagePayType.php');
    exit;
}

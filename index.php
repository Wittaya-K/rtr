<?php
ob_start();
include("./FLO.php");

$_SESSION['discount'] = null;
$_SESSION['discountType'] = null;
$_SESSION['serviceCharge'] = null;
$_SESSION['vat'] = null;

// SQL GET Shift
$sqlGETShift = $conn->prepare("SELECT `sh_id`, `user_id` FROM `rtr_shift` WHERE `sh_status`='false'");
$sqlGETShift->execute();
$resultGETShift = $sqlGETShift->fetch();

$idshift = $resultGETShift['sh_id'];


if ($resultGETShift == null) {
    header('location:./ManageShift.php');
    exit;
} else {
    if ($user_id != $resultGETShift['user_id']) {
        header('location:./ManageShift.php');
        exit;
    }
}

if (isset($_GET['idgroup'])) {
    $idgroup = $_GET['idgroup'];
    // SQL GET Name Group
    $sqlGETNameGroup = $conn->prepare("SELECT `group_name` FROM `rtr_group_menu` WHERE `group_id`=$idgroup");
    $sqlGETNameGroup->execute();
    $resultGETNameGroup = $sqlGETNameGroup->fetch();
    $namegroup = $resultGETNameGroup['group_name'];

    // SQL GET Menu
    $sqlGETMenu = $conn->prepare("SELECT `menu_id`,`menu_name`,`menu_price`,`menu_img`,`menu_group` FROM `rtr_menu` WHERE `menu_status`='true' AND `menu_group`=$idgroup");
    $sqlGETMenu->execute();
    $resultGETMenu = $sqlGETMenu->fetchAll();
} else {
    $idgroup = null;
    $namegroup = 'ทั้งหมด';

    // SQL GET Menu
    $sqlGETMenu = $conn->prepare("SELECT `menu_id`,`menu_name`,`menu_price`,`menu_img`,`menu_group` FROM `rtr_menu` WHERE `menu_status`='true'");
    $sqlGETMenu->execute();
    $resultGETMenu = $sqlGETMenu->fetchAll();
}

// SQL GET Group Menu
$sqlGETGroupMenu = $conn->prepare("SELECT `group_id`, `group_name`, `group_by_add`, `group_date_add` FROM `rtr_group_menu`");
$sqlGETGroupMenu->execute();
$resultGETGroupMenu = $sqlGETGroupMenu->fetchAll();

// SQL Check Count
$sqlCheckCount = $conn->prepare("SELECT COUNT(`menu_id`) FROM `rtr_menu`");
$sqlCheckCount->execute();
$resultCheckCountAll = $sqlCheckCount->fetch();

$sum_price = 0;

?>

<style>
    .card-main {
        height: 800px;
    }

    .txt-search {
        background-color: #F4F6FC;
    }

    .card-show-product {
        background-color: #F4F6FC;
        overflow: auto;
    }

    /* width */
    ::-webkit-scrollbar {
        width: 0px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
        width: 10px;
    }

    .limit-txt {
        white-space: nowrap;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        -ms-text-overflow: ellipsis;
        overflow: hidden;
        width: 190px;
    }

    .card-goods {
        background-color: #F4F6FC;
        margin-left: -24px;
        margin-right: -24px;
        height: 580px;
        overflow: auto;
    }

    .card-seat-free {
        border: 1px solid #5b5b5b;
        background-color: #FAFAFA;
    }

    .card-seat-use {
        border: 1px solid green;
        background-color: #C8FDC1;
    }

    @media print {
        .hid {
            display: none;
            /* ซ่อน  */
        }
    }
</style>

<script>
    function getDataFromDb() {
        $.ajax({
            url: "./ajax/ajax.php?ModalStatusSeat",
            type: "POST",
            data: '',
            success: function(result) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("ModalStatusSeat").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "./ajax/ajax.php?ModalStatusSeat", true);
                xmlhttp.send();
            }
        });
    }
    setInterval(getDataFromDb, 5000); // 1000 = 1 second
</script>

<!-- Modal -->
<?php
// SQL GET All Seat
$sqlGETAllSeat = $conn->prepare("SELECT `seat_id`,`seat_number` FROM `rtr_seat` WHERE `seat_status`='true' ORDER BY `seat_number` ASC");
$sqlGETAllSeat->execute();
$resultGETAllSeat = $sqlGETAllSeat->fetchAll();
?>
<div class="modal fade" id="ModalAllTable" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">รายการโต๊ะ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ModalStatusSeat">
                <div class="row">
                    <?php foreach ($resultGETAllSeat as $keyresultGETAllSeat => $valueresultGETAllSeat) { ?>
                        <?php
                        $SeatIdModalvar = $valueresultGETAllSeat['seat_id'];
                        // SQL GET Order
                        $sqlGETOrderSeatModal = $conn->prepare("SELECT `cook_stauts`,`stauts_serve` FROM `rtr_order` WHERE `od_status`='false' AND `seat_id`=$SeatIdModalvar");
                        $sqlGETOrderSeatModal->execute();
                        $resultGETOrderSeatModal = $sqlGETOrderSeatModal->fetch();
                        ?>
                        <?php if ($resultGETOrderSeatModal == null) { ?>
                            <div class="col-6">
                                <div class="card card-seat-free">
                                    <div class="card-body">
                                        <center>
                                            <span class="iconify" data-icon="vs:table-alt" data-width="30" data-height="30"></span>
                                            <br>
                                            <b>โต๊ะที่ <?php echo $valueresultGETAllSeat['seat_number'] ?></b>
                                            <br>
                                            <span class="badge bg-secondary">ว่าง</span>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-6">
                                <a href="./action.php?tableToPayMent=<?php echo $SeatIdModalvar ?>" style="width: 100%;">
                                    <div class="card card-seat-use">
                                        <div class="card-body">
                                            <center>
                                                <span style="color: #198754;" class="iconify" data-icon="vs:table-alt" data-width="30" data-height="30"></span>
                                                <br>
                                                <b style="color: #198754;">โต๊ะที่ <?php echo $valueresultGETAllSeat['seat_number'] ?></b>
                                                <br>
                                                <?php if ($resultGETOrderSeatModal['cook_stauts'] == 'false') { ?>
                                                    <span class="badge bg-success">กำลังใช้งาน</span>
                                                <?php } elseif ($resultGETOrderSeatModal['cook_stauts'] == 'true' && $resultGETOrderSeatModal['stauts_serve'] == 'false') { ?>
                                                    <span class="badge bg-warning">กำลังทำอาหาร</span>
                                                <?php } elseif ($resultGETOrderSeatModal['stauts_serve'] == 'true') { ?>
                                                    <span class="badge bg-success">เสิร์ฟอาหารแล้ว</span>
                                                <?php } ?>
                                            </center>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
<!-- /Modal -->
<!-- ModalGettoHome -->
<div class="modal fade" id="ModalGettoHome" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ออร์เดอร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                // SQL GET Order
                $sqlGETOrderBackHome = $conn->prepare("SELECT `od_code`,`od_q`,`cook_stauts`,`stauts_serve`,`sum_price`,`name_payer`,`phone_payer` FROM `rtr_order` WHERE `od_status`='false' AND `home`='true' ");
                $sqlGETOrderBackHome->execute();
                $resultGETOrderBackHome = $sqlGETOrderBackHome->fetchAll();
                ?>
                <div class="list-group">
                    <?php foreach ($resultGETOrderBackHome as $keyresultGETOrderBackHome => $valueresultGETOrderBackHome) { ?>
                        <?php
                        $code_Order = $valueresultGETOrderBackHome['od_code'];
                        $code_Order = str_replace("#", "%23", $code_Order);
                        ?>
                        <a href="./PaymentPage.php?code_or=<?php echo $code_Order ?>" class="list-group-item list-group-item-action">
                            <span class="badge bg-secondary"><?php echo 'คิวที่ ' . $valueresultGETOrderBackHome['od_q'] ?></span>
                            <span class="badge bg-danger"><?php echo $valueresultGETOrderBackHome['od_code'] ?></span>
                            <?php if ($valueresultGETOrderBackHome['cook_stauts'] == 'false') { ?>
                                <span class="badge bg-success">กำลังใช้งาน</span>
                            <?php } elseif ($valueresultGETOrderBackHome['cook_stauts'] == 'true' && $valueresultGETOrderBackHome['stauts_serve'] == 'false') { ?>
                                <span class="badge bg-warning">กำลังทำอาหาร</span>
                            <?php } elseif ($valueresultGETOrderBackHome['stauts_serve'] == 'true') { ?>
                                <span class="badge bg-success">เสิร์ฟอาหารแล้ว</span>
                            <?php } ?>
                            <span class="badge bg-primary"><?php echo '฿' . number_format($valueresultGETOrderBackHome['sum_price'], 2) . '.-' ?></span>
                            <?php if ($valueresultGETOrderBackHome['name_payer'] != null) { ?>
                                <span class="badge bg-info text-dark"><?php echo $valueresultGETOrderBackHome['name_payer'] . '/' . $valueresultGETOrderBackHome['phone_payer'] ?></span>
                            <?php } ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ModalGettoHome -->
<!-- ModalGETOrderSuccess -->
<?php if (isset($_GET['GETOrderSuccess']) && isset($_SESSION['od_code'])) { ?>
    <!-- Modal -->
    <!-- <div class="modal fade" id="Modalod_code" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> -->
    <div class="modal fade" id="Modalod_code" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header hid">
                    <a type="button" class="btn hid" onclick="window.print()">
                        <span class="iconify" data-icon="fluent:print-48-filled" data-width="30" data-height="30"></span>
                    </a>
                    <!-- <button type="button" class="btn-close hid" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <?php
                    $od_code = $_SESSION['od_code'];
                    // SQL GET Order
                    $sqlGETOrderModal = $conn->prepare("SELECT `seat_id`,`od_date_add`,`od_q`,`seat_number`,`sum_price` FROM `rtr_order` WHERE `od_code`='$od_code'");
                    $sqlGETOrderModal->execute();
                    $resultGETOrderModal = $sqlGETOrderModal->fetch();
                    // SQL GET Sub Order
                    $sqlGETSubOrderModal = $conn->prepare("SELECT `menu_id`,`menu_detail`,`price`,`sum` FROM `rtr_sub_order` WHERE `od_code`='$od_code'");
                    $sqlGETSubOrderModal->execute();
                    $resultGETSubOrderModal = $sqlGETSubOrderModal->fetchAll();

                    $codeOrder = '%23' . substr($od_code, 1);

                    if ($resultGETOrderModal['seat_id'] != null) {
                        $SeatIDBill = $resultGETOrderModal['seat_id'];
                        // SQL GET Seat
                        $sqlGETSeatBill = $conn->prepare("SELECT `seat_number` FROM `rtr_seat` WHERE `seat_id`=$SeatIDBill");
                        $sqlGETSeatBill->execute();
                        $resultGETSeatBill = $sqlGETSeatBill->fetch();
                    }
                    ?>
                    <center>
                        <b>สลิปรับออร์เดอร์</b>
                        <p>วันที่ <?php echo DateThai($resultGETOrderModal['od_date_add']) ?></p>
                        <p>คิวที่ <?php echo $resultGETOrderModal['od_q'] ?>
                            <?php if ($resultGETOrderModal['seat_id'] != null) { ?>
                                โต๊ะที่ <?php echo $resultGETSeatBill['seat_number'] ?>
                            <?php } ?>
                        </p>
                        <img style="width: 200px;" alt='Barcode Generator TEC-IT' src='https://barcode.tec-it.com/barcode.ashx?data=<?php echo $codeOrder ?>&code=Code128' />
                        <hr>
                        <ol class="list-group list-group-numbered">
                            <?php foreach ($resultGETSubOrderModal as $keyresultGETSubOrderModal => $valueresultGETSubOrderModal) { ?>
                                <?php
                                $menuID = $valueresultGETSubOrderModal['menu_id'];
                                // SQL GET Name Menu
                                $sqlGETNameMenu = $conn->prepare("SELECT `menu_name` FROM `rtr_menu` WHERE `menu_id`=$menuID");
                                $sqlGETNameMenu->execute();
                                $resultGETNameMenu = $sqlGETNameMenu->fetch();
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-start border-0">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><?php echo $resultGETNameMenu['menu_name'] ?></div>
                                        <?php echo $valueresultGETSubOrderModal['menu_detail'] ?>
                                    </div>
                                    <span class="badge" style="color: #61686F;"><?php echo '฿' . number_format($valueresultGETSubOrderModal['price'], 2) . '/' . $valueresultGETSubOrderModal['sum'] ?></span>
                                </li>
                            <?php } ?>
                        </ol>
                        <h5 style="color: #61686F;">ยอดรวม <?php echo number_format($resultGETOrderModal['sum_price'], 2) ?> บาท</h5>
                    </center>
                </div>
                <div class="modal-footer hid">
                    <!-- <a href="./PaymentPage.php?code_or=<?php echo $codeOrder ?>" class="btn btn-success btn-block">ชำระเงิน</a> -->
                    <button type="button" class="btn btn-secondary btn-block" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <!-- modal js -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#Modalod_code").modal("show");
        });
    </script>
    <!-- /modal js -->
    <?php
    $_SESSION['od_code'] = null;
    ?>
<?php } ?>
<!-- /ModalGETOrderSuccess -->

<div class="row hid" style="margin-top: -45px;">
    <div class="col-sm-8">
        <div class="card card-main card-main-show-pro" style="margin-left: -16px;margin-right: -16px;">
            <div class="card-header">
                <!-- search -->
                <div class="form-group position-relative has-icon-left">
                    <input id="searchingbar" onkeyup="searchProduct(this.value)" type="search" class="form-control txt-search" placeholder="ค้นหาสินค้า">
                    <div class="form-control-icon">
                        <span class="iconify" data-icon="bx:bx-search-alt" data-inline="false"></span>
                    </div>
                </div>
                <!-- js search -->
                <script>
                    function searchProduct(value) {
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                document.getElementById("listProduct").innerHTML = this.responseText;
                            }
                        };
                        xmlhttp.open("GET", "./ajax/ajax.php?searchProduct=" + value, true);
                        xmlhttp.send();
                    }
                </script>
                <!-- js search -->
                <!-- /search -->
                <div class="dropdown">
                    <a class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="badge bg-secondary">หมวดหมู่</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <div class="list-group">
                            <a href="./index.php" class="list-group-item list-group-item-action <?php if ($idgroup == null) { ?>active<?php } ?>">ทั้งหมด(<?php echo $resultCheckCountAll[0] ?>)</a>
                            <?php 
                            $WOverFlow = 3;
                            foreach ($resultGETGroupMenu as $keyresultGETGroupMenu => $valueresultGETGroupMenu) { ?>
                                <?php
                                $groupid = $valueresultGETGroupMenu['group_id'];
                                // SQL Check Count
                                $sqlCheckCount = $conn->prepare("SELECT COUNT(*) FROM `rtr_menu` WHERE menu_group=$groupid");
                                $sqlCheckCount->execute();
                                $resultCheckCount = $sqlCheckCount->fetch();
                                ?>
                                <a href="./index.php?idgroup=<?php echo $valueresultGETGroupMenu['group_id'] ?>" class="list-group-item list-group-item-action <?php if ($idgroup == $valueresultGETGroupMenu['group_id']) { ?>active<?php } ?>"><?php echo $valueresultGETGroupMenu['group_name'] ?>(<?php echo $resultCheckCount[0] ?>)</a>
                            <?php $WOverFlow++;
                            } ?>
                        </div>
                    </ul>
                    <?php echo $namegroup ?>
                </div>
                <style>
                    ::-webkit-scrollbar:horizontal {
                        height: 0px;
                        background-color: red;
                        overflow: visible;
                    }

                    div#groupcontainer:hover::-webkit-scrollbar {
                        height: 10px;
                        background-color: red;
                        overflow: visible;
                    }

                    ::-webkit-scrollbar-thumb:horizontal {
                        background: #808080;
                        border-radius: 10px;
                        height: 4px;
                    }
                </style>
                <div class="mt-3 groupMEnu" id="groupcontainer" style="width: 100%;overflow: auto;padding-bottom: 3px;">
                    <div style="width: <?php echo $WOverFlow ?>00px;">
                        <?php
                        if ($idgroup == null) {
                            $txtgroupAll = 'dark';
                            $bgGroupAll = 'light';
                        } else {
                            $txtgroupAll = 'light';
                            $bgGroupAll = 'dark';
                        }
                        ?>
                        <a href="./index.php">
                            <span class="badge bg-<?php echo $txtgroupAll ?> text-<?php echo $bgGroupAll ?>">ทั้งหมด(<?php echo $resultCheckCountAll[0] ?>)</span>
                        </a>
                        <?php foreach ($resultGETGroupMenu as $keyresultGETGroupMenu => $valueresultGETGroupMenu) { ?>
                            <?php
                            $groupid = $valueresultGETGroupMenu['group_id'];
                            // SQL Check Count
                            $sqlCheckCount = $conn->prepare("SELECT COUNT(*) FROM `rtr_menu` WHERE menu_group=$groupid");
                            $sqlCheckCount->execute();
                            $resultCheckCount = $sqlCheckCount->fetch();

                            if ($idgroup == $valueresultGETGroupMenu['group_id']) {
                                $txtgroup = 'dark';
                                $bgGroup = 'light';
                            } else {
                                $txtgroup = 'light';
                                $bgGroup = 'dark';
                            }
                            ?>
                            <a href="./index.php?idgroup=<?php echo $valueresultGETGroupMenu['group_id'] ?>">
                                <span style="font-size: 16px;" class="badge bg-<?php echo $txtgroup ?> text-<?php echo $bgGroup ?>"><?php echo $valueresultGETGroupMenu['group_name'] ?>(<?php echo $resultCheckCount[0] ?>)</span>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <style>
                .border-card-product {
                    -webkit-border-radius: 0px;
                    border-radius: 0px;
                    -webkit-backdrop-filter: blur(10px);
                    backdrop-filter: blur(10px);
                }

                .blur {
                    background: rgba(255, 255, 255, 0.2);
                    backdrop-filter: blur(8px);
                }
            </style>
            <div class="card-body card-show-product" style="padding: 0px;">
                <div class="row mt-3" id="listProduct">
                    <?php foreach ($resultGETMenu as $keyresultGETMenu => $valueresultGETMenu) { ?>
                        <?php
                        $groupIDloop = $valueresultGETMenu['menu_group'];
                        // SQL GET Name Gruop
                        $sqlGETNameGroup = $conn->prepare("SELECT `group_name` FROM `rtr_group_menu` WHERE `group_id`=$groupIDloop");
                        $sqlGETNameGroup->execute();
                        $resultGETNameGRoup = $sqlGETNameGroup->fetch();
                        ?>
                        <div class="col-6" style="padding: 1px;margin-bottom: -35px;">
                            <a style="width: 100%;" type="button" data-bs-toggle="modal" data-bs-target="#ModaladdCart<?php echo $valueresultGETMenu['menu_id'] ?>">
                                <div class="card card-menu-main border-card-product" style="background-image: url('./assets/assets/images/food/<?php echo $valueresultGETMenu['menu_img'] ?>');background-size: cover;">
                                    <div class="blur">
                                        <?php if ($valueresultGETMenu['menu_img'] != null) { ?>
                                            <img class="card-img-top img-fluid" style="height: 200px;object-fit: contain;" src="./assets/assets/images/food/<?php echo $valueresultGETMenu['menu_img'] ?>" alt="">
                                        <?php } else { ?>
                                            <img class="card-img-top img-fluid" style="height: 200px;object-fit: contain;" src="./assets/assets/images/food/nopic.png" alt="">
                                        <?php } ?>
                                        <!-- 0000003F -->
                                        <!-- 00000057 -->
                                        <!-- 0000006E -->
                                        <!-- 00000013 -->
                                        <div class="card-body limit-txt" style="position: absolute;background-color: #0000002C;bottom: 2px;width: 100%;padding-top: 2px;padding-bottom: 2px;">
                                            <div class="float-end">
                                                <b style="color: #FFFFFF;font-size: 18px;"><?php echo $valueresultGETMenu['menu_name'] ?></b>
                                                <br>
                                                <h4 style="color: #FFFFFF;"><?php echo number_format($valueresultGETMenu['menu_price'], 2).'.-' ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <!-- Modal -->
                            <div class="modal fade" id="ModaladdCart<?php echo $valueresultGETMenu['menu_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <?php
                                        $menu_id = $valueresultGETMenu['menu_id'];
                                        $codeJSMD5 = md5($menu_id);
                                        // SQL GET Sub menu
                                        $sqlGETSubMenu = $conn->prepare("SELECT * FROM `rtr_sub_menu` WHERE `menu_id`=$menu_id");
                                        $sqlGETSubMenu->execute();
                                        $resultGETSubMenu = $sqlGETSubMenu->fetchAll();
                                        ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel"><?php echo $valueresultGETMenu['menu_name'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <?php
                                        if (isset($_GET['idgroup'])) {
                                            $idgrouplink = '&idgroupindex=' . $idgroup;
                                        } else {
                                            $idgrouplink = '';
                                        }
                                        ?>
                                        <form action="./action.php?id=<?php echo $menu_id ?><?php echo $idgrouplink ?>" method="post">
                                            <?php if ($resultGETSubMenu != null) { ?>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <?php foreach ($resultGETSubMenu as $keyresultGETSubMenu => $valueresultGETSubMenu) { ?>
                                                            <div class="col-12">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="sm_<?php echo $valueresultGETSubMenu['sm_id'] ?>" name="sm_<?php echo $valueresultGETSubMenu['sm_id'] ?>" value="<?php echo $valueresultGETSubMenu['sm_id'] ?>" id="flexCheckDefault">
                                                                    <label class="form-check-label" for="sm_<?php echo $valueresultGETSubMenu['sm_id'] ?>">
                                                                        <?php echo $valueresultGETSubMenu['sm_name'] . '(+' . $valueresultGETSubMenu['sm_price'] . ' บาท)' ?>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <br>
                                                    <a type="button" style="color: #EC5F47;" onclick="showOt<?php echo $valueresultGETSubMenu['sm_id'] ?>()">เพิ่มเติม</a>
                                                    <!-- orther -->
                                                    <div id="orther_detail<?php echo $valueresultGETSubMenu['sm_id'] ?>" hidden>
                                                        <div class="form-floating mb-3">
                                                            <input onkeyup="lockprice<?php echo $valueresultGETSubMenu['sm_id'] ?>(this.value)" type="text" class="form-control" id="floatingDetail" name="detail_ot" placeholder="รายละเอียด">
                                                            <label for="floatingDetail">รายละเอียด</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="number" class="form-control" id="floatingPrice<?php echo $valueresultGETSubMenu['sm_id'] ?>" name="price_ot" placeholder="ราคา(บาท)">
                                                            <label for="floatingPrice<?php echo $valueresultGETSubMenu['sm_id'] ?>">ราคา(บาท)</label>
                                                        </div>
                                                    </div>
                                                    <!-- /orther -->
                                                </div>
                                            <?php } ?>
                                            <div class="modal-body">
                                                <center>
                                                    <div class="input-group" style="width: 50%;">
                                                        <button class="btn btn-outline-danger" type="button" onclick="renumbersum<?php echo $codeJSMD5 ?>()"><span class="iconify" data-icon="ic:baseline-remove"></span></button>
                                                        <input type="number" class="form-control" name="number" id="numbersum<?php echo $codeJSMD5 ?>" value="1" readonly>
                                                        <button class="btn btn-outline-success" type="button" onclick="addnumbersum<?php echo $codeJSMD5 ?>()"><span class="iconify" data-icon="fluent:add-12-filled"></button>
                                                    </div>
                                                </center>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" sty>ปิด</button>
                                                <button type="submit" class="btn btn-primary" name="AddMenuToCart">เพิ่มลงรายการ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- JS -->
                                <script>
                                    function addnumbersum<?php echo $codeJSMD5 ?>() {
                                        var num = document.getElementById('numbersum<?php echo $codeJSMD5 ?>').value;
                                        num++;
                                        document.getElementById('numbersum<?php echo $codeJSMD5 ?>').value = num;
                                    }

                                    function renumbersum<?php echo $codeJSMD5 ?>() {
                                        var num = document.getElementById('numbersum<?php echo $codeJSMD5 ?>').value;
                                        if (num >= 2) {
                                            num--;
                                            document.getElementById('numbersum<?php echo $codeJSMD5 ?>').value = num;
                                        }
                                    }

                                    function showOt<?php echo $valueresultGETSubMenu['sm_id'] ?>() {
                                        var x = document.getElementById("orther_detail<?php echo $valueresultGETSubMenu['sm_id'] ?>").hidden;
                                        if (x == true) {
                                            document.getElementById("orther_detail<?php echo $valueresultGETSubMenu['sm_id'] ?>").hidden = false;
                                        } else {
                                            document.getElementById("orther_detail<?php echo $valueresultGETSubMenu['sm_id'] ?>").hidden = true;
                                        }
                                    }

                                    function lockprice<?php echo $valueresultGETSubMenu['sm_id'] ?>(value) {
                                        if (value != '') {
                                            document.getElementById("floatingPrice<?php echo $valueresultGETSubMenu['sm_id'] ?>").required = true;
                                        } else {
                                            document.getElementById("floatingPrice<?php echo $valueresultGETSubMenu['sm_id'] ?>").required = false;
                                        }
                                    }
                                </script>
                                <!-- JS -->
                            </div>
                            <!-- Modal -->
                        </div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card card-main card-payment" id="card-payment">
            <div class="card-body">
                <div class="float-end">
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#ListModalTop"><span class="iconify" data-icon="gg:menu-boxed"></span></button>
                    <!-- Modal -->
                    <div class="modal fade" id="ListModalTop" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">ตัวเลือก</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <a href="./action.php?CancelAllListCart" onclick="return confirm('ยืนยันการลบ')" class="btn btn-block btn-danger">ยกเลิกสินค้าทั้งหมด</a>
                                    <a href="./action.php?ClearAllTreeTable">เคลียร์</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Modal -->
                </div>
                <h4 style="text-align: center;">รายการสินค้า</h4>
                <table style="width: 100%;" class="mt-4 border-bottom">
                    <tr>
                        <th style="text-align: left;">รายการ</th>
                        <th style="text-align: center;">จำนวน</th>
                        <th style="text-align: right;">ราคา</th>
                    </tr>
                </table>
                <?php
                // SQL GET List Cart
                $sqlGETListCart = $conn->prepare("SELECT * FROM `rtr_cart` WHERE `user_id`=$user_id AND `cart_status`='false'");
                $sqlGETListCart->execute();
                $resultGETListCart = $sqlGETListCart->fetchAll();
                ?>
                <div class="card-body card-goods" style="padding: 0px 0px 0px 0px;">
                    <?php if ($resultGETListCart == null) { ?>
                        <div style="text-align: center;" class="mt-5">
                            <span class="iconify" data-icon="entypo:box" data-inline="false" data-width="90" data-height="90"></span>
                            <br>
                            <b>ยังไม่มีการเลือกสินค้า</b>
                        </div>
                    <?php } else { ?>
                        <div class="list-group">
                            <?php foreach ($resultGETListCart as $keyresultGETListCart => $valueresultGETListCart) { ?>
                                <?php
                                $menuID = $valueresultGETListCart['menu_id'];
                                // SQL GET Name Menu
                                $sqlGETNameMenu = $conn->prepare("SELECT `menu_name` FROM `rtr_menu` WHERE `menu_id`=$menuID");
                                $sqlGETNameMenu->execute();
                                $resultGETNameMenu = $sqlGETNameMenu->fetch();
                                ?>
                                <a type="button" data-bs-toggle="modal" data-bs-target="#ModalListCart<?php echo $valueresultGETListCart['cart_id'] ?>" class="list-group-item list-group-item-action border-0" aria-current="true">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="text-align: left; width: 43%;">
                                                <div class="list-text-limit">
                                                    <b><?php echo $resultGETNameMenu['menu_name'] ?></b>
                                                    <br>
                                                    <small style="font-size: 12px;"><?php echo $valueresultGETListCart['menu_detail'] ?></small>
                                                </div>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge bg-primary"><?php echo $valueresultGETListCart['sum'] ?></span>
                                            </td>
                                            <td style="text-align: right;width: 30%;">
                                                <price style="color: #3950A2;"><?php echo '฿' . $valueresultGETListCart['price'] ?></price>
                                                <?php
                                                $sum_price = $sum_price + $valueresultGETListCart['price'];
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </a>
                                <!-- Modal -->
                                <div class="modal fade" id="ModalListCart<?php echo $valueresultGETListCart['cart_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"><?php echo $resultGETNameMenu['menu_name'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="./action.php?id=<?php echo $valueresultGETListCart['cart_id'] ?>" method="post">
                                                <div class="modal-body">
                                                    <small><?php echo $valueresultGETListCart['menu_detail'] ?></small>
                                                    <br>
                                                    <center>
                                                        <div class="input-group mt-3" style="width: 50%;">
                                                            <button class="btn btn-outline-danger" type="button" onclick="renumbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>()"><span class="iconify" data-icon="ic:baseline-remove"></span></button>
                                                            <input type="number" class="form-control" name="number" id="numbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>" value="<?php echo $valueresultGETListCart['sum'] ?>" readonly>
                                                            <button class="btn btn-outline-success" type="button" onclick="addnumbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>()"><span class="iconify" data-icon="fluent:add-12-filled"></button>
                                                        </div>
                                                    </center>
                                                    <script>
                                                        function addnumbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>() {
                                                            var num = document.getElementById('numbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>').value;
                                                            num++;
                                                            document.getElementById('numbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>').value = num;
                                                        }

                                                        function renumbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>() {
                                                            var num = document.getElementById('numbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>').value;
                                                            if (num >= 2) {
                                                                num--;
                                                                document.getElementById('numbersumcart<?php echo $valueresultGETListCart['cart_id'] ?>').value = num;
                                                            }
                                                        }
                                                    </script>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="./action.php?CancelListCart=<?php echo $valueresultGETListCart['cart_id'] ?>" onclick="return confirm('ยืนยันการลบรายการ')" class="btn btn-danger">ลบรายการ</a>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                    <button type="submit" name="EditSumCart" class="btn btn-primary">บันทึก</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Modal -->
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <ul class="list-group border-1" style="margin-left: -24px;margin-right: -24px;">
                    <!-- <li class="border-0 list-group-item d-flex justify-content-between align-items-center">
                        ราคาเฉพาะสินค้า
                        <span class="badge rounded-pill" style="color: #3950A2;">฿<?php echo number_format($sum_price, 2) ?></span>
                    </li> -->
                    <li class="border-0 list-group-item d-flex justify-content-between align-items-center">
                        <b>ยอดรวมสุทธิ</b>
                        <span class="badge rounded-pill" style="color: #3950A2;"><b>฿<?php echo number_format($sum_price, 2) ?></b></span>
                    </li>
                </ul>

                <div class="fix-btn-final mt-3">
                    <?php if ($resultGETListCart == null) {
                        $btnGETOrder = 'disabled';
                    } else {
                        $btnGETOrder = '';
                    } ?>
                    <button <?php echo $btnGETOrder ?> type="button" data-bs-toggle="modal" data-bs-target="#ModalPayment" class="btn btn-primary btn-payment" style="width: 100%;">รับออเดอร์</button>
                    <!-- Modal -->
                    <div class="modal fade" id="ModalPayment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">รับออเดอร์</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">ทานที่ร้าน</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">สั่งกลับบ้าน</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">สั่งเพิ่ม</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <!-- ร้าน -->
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <form action="./action.php" method="post">
                                                <div class="form-group">
                                                    <?php
                                                    $datenow = date('Y-m-d');
                                                    // SQL GET Order
                                                    $sqlGETOrder = $conn->prepare("SELECT * FROM `rtr_order` WHERE `date`='$datenow'");
                                                    $sqlGETOrder->execute();
                                                    $resultGETOrder = $sqlGETOrder->fetchAll();
                                                    if ($resultGETOrder != null) {
                                                        $q = 0;
                                                        foreach ($resultGETOrder as $keyresultGETOrder => $valueresultGETOrder) {
                                                            $q++;
                                                        }
                                                        $q++;
                                                    } else {
                                                        $q = 1;
                                                    }
                                                    ?>
                                                    <label for="q" class="form-label">คิวที่</label>
                                                    <input type="number" name="q" id="q" class="form-control" value="<?php echo $q ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <?php
                                                    // SQL GET Seat
                                                    $sqlGETSeat = $conn->prepare("SELECT * FROM `rtr_seat` WHERE `seat_status`='true'");
                                                    $sqlGETSeat->execute();
                                                    $resultGETSeat = $sqlGETSeat->fetchAll();

                                                    ?>
                                                    <label for="numberSeat" class="form-label">หมายเลขโต๊ะ</label>
                                                    <select name="numberSeat" id="numberSeat" class="form-control" required>
                                                        <option value="">-- เลือกโต๊ะ --</option>
                                                        <?php foreach ($resultGETSeat as $keyresultGETSeat => $valueresultGETSeat) { ?>
                                                            <?php
                                                            $seatID = $valueresultGETSeat['seat_id'];
                                                            // SQL GET Order
                                                            $sqlGETOrderSeat = $conn->prepare("SELECT * FROM `rtr_order` 
                                                            WHERE `od_status`='false' AND `seat_id`=$seatID");
                                                            $sqlGETOrderSeat->execute();
                                                            $resultGETOrderSeat = $sqlGETOrderSeat->fetch();
                                                            ?>
                                                            <?php if ($resultGETOrderSeat == null) { ?>
                                                                <option value="<?php echo $seatID ?>" style="color: green;"><?php echo $valueresultGETSeat['seat_number'] . ' - ว่าง' ?></option>
                                                            <?php } else { ?>
                                                                <option style="color: red;" disabled><?php echo $valueresultGETSeat['seat_number'] . ' - ไม่ว่าง' ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <?php if (isset($_SESSION['discountType']) != null) { ?>
                                                    <?php
                                                    if ($_SESSION['discountType'] == 'B') {
                                                        $type = ' บาท';
                                                    } else {
                                                        $type = '%';
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="" class="form-label">ส่วนลด</label>
                                                        <input type="text" value="<?php echo $_SESSION['discount'] . $type ?>" class="form-control" readonly>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group">
                                                    <label for="" class="form-label">ราคาออร์เดอร์(บาท)</label>
                                                    <input type="number" name="sumprice" id="sumprice" value="<?php echo $sum_price ?>" class="form-control" readonly required>
                                                </div>
                                                <div class="form-group mt-4">
                                                    <button class="btn btn-block btn-primary" type="submit" name="GETOrderTable">รับออร์เดอร์</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /ร้าน -->

                                        <!-- บ้าน -->
                                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <form action="./action.php" method="post">
                                                <div class="form-group">
                                                    <?php
                                                    $datenow = date('Y-m-d');
                                                    // SQL GET Order
                                                    $sqlGETOrder = $conn->prepare("SELECT * FROM `rtr_order` WHERE `date`='$datenow'");
                                                    $sqlGETOrder->execute();
                                                    $resultGETOrder = $sqlGETOrder->fetchAll();
                                                    if ($resultGETOrder != null) {
                                                        $q = 0;
                                                        foreach ($resultGETOrder as $keyresultGETOrder => $valueresultGETOrder) {
                                                            $q++;
                                                        }
                                                        $q++;
                                                    } else {
                                                        $q = 1;
                                                    }
                                                    ?>
                                                    <label for="q" class="form-label">คิวที่</label>
                                                    <input type="number" name="q" id="q" class="form-control" value="<?php echo $q ?>" readonly>
                                                </div>
                                                <?php if (isset($_SESSION['discountType']) != null) { ?>
                                                    <?php
                                                    if ($_SESSION['discountType'] == 'B') {
                                                        $type = ' บาท';
                                                    } else {
                                                        $type = '%';
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="" class="form-label">ส่วนลด</label>
                                                        <input type="text" value="<?php echo $_SESSION['discount'] . $type ?>" class="form-control" readonly>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group">
                                                    <label for="name_payer" class="form-label">ชื่อลูกค้า</label>
                                                    <input type="text" name="name_payer" id="name_payer" class="form-control" placeholder="กรอกชื่อลูกค้า" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone_payer" class="form-label">เบอร์โทรศัพท์ลูกค้า</label>
                                                    <input type="tel" maxlength="10" pattern="[0-9]{10}" name="phone_payer" id="phone_payer" class="form-control" placeholder="กรอกเบอร์โทรศัพท์" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="form-label">ราคาออร์เดอร์(บาท)</label>
                                                    <input type="number" name="sumprice" id="sumprice" value="<?php echo $sum_price ?>" class="form-control" readonly required>
                                                </div>
                                                <div class="form-group mt-4">
                                                    <button class="btn btn-block btn-primary" type="submit" name="GETOrderBackHome">รับออร์เดอร์</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /บ้าน -->

                                        <!-- สั่งเพิ่ม -->
                                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                            <form action="./action.php" method="post">
                                                <div class="form-group">
                                                    <?php
                                                    $datenow = date('Y-m-d');
                                                    // SQL GET Order
                                                    $sqlGETOrder = $conn->prepare("SELECT * FROM `rtr_order` WHERE `date`='$datenow'");
                                                    $sqlGETOrder->execute();
                                                    $resultGETOrder = $sqlGETOrder->fetchAll();
                                                    if ($resultGETOrder != null) {
                                                        $q = 0;
                                                        foreach ($resultGETOrder as $keyresultGETOrder => $valueresultGETOrder) {
                                                            $q++;
                                                        }
                                                        $q++;
                                                    } else {
                                                        $q = 1;
                                                    }
                                                    ?>
                                                    <label for="q" class="form-label">คิวที่</label>
                                                    <input type="number" name="q" id="q" class="form-control" value="<?php echo $q ?>" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <?php
                                                    // SQL GET Seat
                                                    $sqlGETSeat2 = $conn->prepare("SELECT * FROM `rtr_seat` WHERE `seat_status`='true'");
                                                    $sqlGETSeat2->execute();
                                                    $resultGETSeat2 = $sqlGETSeat2->fetchAll();

                                                    ?>
                                                    <label for="numberSeat" class="form-label">หมายเลขโต๊ะ</label>
                                                    <select name="numberSeat" id="numberSeat" class="form-control" required>
                                                        <option value="">-- เลือกโต๊ะ --</option>
                                                        <?php foreach ($resultGETSeat2 as $keyresultGETSeat2 => $valueresultGETSeat2) { ?>
                                                            <?php
                                                            $seatID = $valueresultGETSeat2['seat_id'];
                                                            // SQL GET Order
                                                            $sqlGETOrderSeat2 = $conn->prepare("SELECT * FROM `rtr_order` 
                                                            WHERE `od_status`='false' AND `seat_id`=$seatID AND `sh_number`=$idshift");
                                                            $sqlGETOrderSeat2->execute();
                                                            $resultGETOrderSeat2 = $sqlGETOrderSeat2->fetch();
                                                            ?>
                                                            <?php if ($resultGETOrderSeat2 != null) { ?>
                                                                <option value="<?php echo $seatID ?>"><?php echo $valueresultGETSeat2['seat_number'] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <?php if (isset($_SESSION['discountType']) != null) { ?>
                                                    <?php
                                                    if ($_SESSION['discountType'] == 'B') {
                                                        $type = ' บาท';
                                                    } else {
                                                        $type = '%';
                                                    }
                                                    ?>
                                                    <div class="form-group">
                                                        <label for="" class="form-label">ส่วนลด</label>
                                                        <input type="text" value="<?php echo $_SESSION['discount'] . $type ?>" class="form-control" readonly>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group">
                                                    <label for="" class="form-label">ราคาออร์เดอร์(บาท)</label>
                                                    <input type="number" name="sumprice" id="sumprice" value="<?php echo $sum_price ?>" class="form-control" readonly required>
                                                </div>
                                                <div class="form-group mt-4">
                                                    <button class="btn btn-block btn-primary" type="submit" name="GETOrderMoreTable">รับออร์เดอร์</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /สั่งเพิ่ม -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Modal -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// SQL GET Order
$sqlGETOrderSeatModal2 = $conn->prepare("SELECT `seat_id` FROM `rtr_order` WHERE `od_status`='false' AND `seat_id` IS NOT NULL");
$sqlGETOrderSeatModal2->execute();
$resultGETOrderSeatModal2 = $sqlGETOrderSeatModal2->fetch();
?>

<!-- floating -->
<div style="position: fixed;right: 10px;bottom: 10px;" class="hid">
    <div id="menuFloatingactionBTN" hidden>
        <a onclick="tosearchcard()" type="button" style="background-color: #EC5F47;color: white;padding: 5px;border-radius: 50%;">
            <span class="iconify" data-icon="bytesize:chevron-top" data-width="25" data-height="25"></span>
        </a>
        <br>
        <a type="button" class="mt-2" style="background-color: #EC5F47;color: white;padding: 5px;border-radius: 50%;" data-bs-toggle="modal" data-bs-target="#ModalAllTable">
            <span class="iconify" data-icon="mdi:table-furniture" data-width="25" data-height="25"></span>
            <?php if ($resultGETOrderSeatModal2 != null) { ?>
                <span class="position-absolute top-3 start-98 translate-middle p-1 bg-success border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                </span>
            <?php } ?>
        </a>
        <br>
        <a type="button" class="mt-2" style="background-color: #EC5F47;color: white;padding: 5px;border-radius: 50%;" data-bs-toggle="modal" data-bs-target="#ModalGettoHome">
            <span class="iconify" data-icon="bi:bag-fill" data-width="25" data-height="25"></span>
            <?php if ($resultGETOrderBackHome != null) { ?>
                <span class="position-absolute top-3 start-98 translate-middle p-1 bg-success border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                </span>
            <?php } ?>
        </a>
        <br>
        <a onclick="topaycard()" type="button" class="mt-2" style="background-color: #EC5F47;color: white;padding: 5px;border-radius: 50%;">
            <span class="iconify" data-icon="mdi:cash-register" data-width="25" data-height="25"></span>
            <?php if ($resultGETListCart != null) { ?>
                <span class="position-absolute top-3 start-98 translate-middle p-1 bg-danger border border-light rounded-circle">
                    <span class="visually-hidden">New alerts</span>
                </span>
            <?php } ?>
        </a>
    </div>
    <a onclick="showMenu()" type="button" class="mt-2" style="background-color: #EC5F47;color: white;padding: 5px;border-radius: 50%;">
        <span class="iconify" data-icon="ci:menu-alt-02" data-width="25" data-height="25"></span>
    </a>
</div>
<!-- /floating -->

<script>
    function topaycard() {
        document.getElementById("card-payment").scrollIntoView();
    }

    function tosearchcard() {
        document.getElementById("app").scrollIntoView();
    }

    function showMenu() {
        var x = document.getElementById('menuFloatingactionBTN').hidden;
        if (x == true) {
            document.getElementById('menuFloatingactionBTN').hidden = false;
        } else {
            document.getElementById('menuFloatingactionBTN').hidden = true;
        }
    }
</script>

<?php
include("./LLO.php");
?>
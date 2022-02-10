<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
require_once("../database/conn.php");

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

// searchProduct
if (isset($_GET['searchProduct'])) {
    $nameProduct = $_GET['searchProduct'];

    if ($nameProduct != '') {
        // SQL GET Product
        $sqlGETProduct = $conn->prepare("SELECT * FROM `rtr_menu` WHERE `menu_status`='true' AND `menu_name` LIKE '%$nameProduct%'");
        $sqlGETProduct->execute();
        $resultGETProduct = $sqlGETProduct->fetchAll();

        foreach ($resultGETProduct as $keyresultGETProduct => $valueresultGETProduct) {
?>
            <?php
            $groupIDloop = $valueresultGETProduct['menu_group'];
            // SQL GET Name Gruop
            $sqlGETNameGroup = $conn->prepare("SELECT `group_name` FROM `rtr_group_menu` WHERE `group_id`=$groupIDloop");
            $sqlGETNameGroup->execute();
            $resultGETNameGRoup = $sqlGETNameGroup->fetch();
            ?>
            <div class="col-6" style="padding: 1px;margin-bottom: -35px;">
                <a style="width: 100%;" type="button" data-bs-toggle="modal" data-bs-target="#ModaladdCart<?php echo $valueresultGETProduct['menu_id'] ?>">
                    <div class="card card-menu-main border-card-product" style="background-image: url('./assets/assets/images/food/<?php echo $valueresultGETProduct['menu_img'] ?>');background-size: cover;">
                        <div class="blur">
                            <?php if ($valueresultGETProduct['menu_img'] != null) { ?>
                                <img class="card-img-top img-fluid" style="height: 200px;object-fit: contain;" src="./assets/assets/images/food/<?php echo $valueresultGETProduct['menu_img'] ?>" alt="">
                            <?php } else { ?>
                                <img class="card-img-top img-fluid" style="height: 200px;object-fit: contain;" src="./assets/assets/images/food/nopic.png" alt="">
                            <?php } ?>
                            <!-- 0000003F -->
                            <!-- 00000057 -->
                            <div class="card-body limit-txt" style="position: absolute;background-color: #0000006E;bottom: 2px;width: 100%;padding-top: 2px;padding-bottom: 2px;">
                                <div class="float-end">
                                    <b style="color: #FFFFFF;"><?php echo $valueresultGETProduct['menu_name'] ?></b>
                                    <br>
                                    <h5 style="color: #FFFFFF;">฿<?php echo number_format($valueresultGETProduct['menu_price'], 2) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- Modal -->
                <div class="modal fade" id="ModaladdCart<?php echo $valueresultGETProduct['menu_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <?php
                            $menu_id = $valueresultGETProduct['menu_id'];
                            $codeJSMD5 = md5($menu_id);
                            // SQL GET Sub menu
                            $sqlGETSubMenu = $conn->prepare("SELECT * FROM `rtr_sub_menu` WHERE `menu_id`=$menu_id");
                            $sqlGETSubMenu->execute();
                            $resultGETSubMenu = $sqlGETSubMenu->fetchAll();
                            ?>
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel"><?php echo $valueresultGETProduct['menu_name'] ?></h5>
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

        <?php
        }
    } else {
        // SQL GET Product
        $sqlGETProduct = $conn->prepare("SELECT * FROM `rtr_menu` WHERE `menu_status`='true'");
        $sqlGETProduct->execute();
        $resultGETProduct = $sqlGETProduct->fetchAll();

        foreach ($resultGETProduct as $keyresultGETProduct => $valueresultGETProduct) {
        ?>
            <?php
            $groupIDloop = $valueresultGETProduct['menu_group'];
            // SQL GET Name Gruop
            $sqlGETNameGroup = $conn->prepare("SELECT `group_name` FROM `rtr_group_menu` WHERE `group_id`=$groupIDloop");
            $sqlGETNameGroup->execute();
            $resultGETNameGRoup = $sqlGETNameGroup->fetch();
            ?>
            <div class="col-6" style="padding: 1px;margin-bottom: -35px;">
                <a style="width: 100%;" type="button" data-bs-toggle="modal" data-bs-target="#ModaladdCart<?php echo $valueresultGETProduct['menu_id'] ?>">
                    <div class="card card-menu-main border-card-product" style="background-image: url('./assets/assets/images/food/<?php echo $valueresultGETProduct['menu_img'] ?>');background-size: cover;">
                        <div class="blur">
                            <?php if ($valueresultGETProduct['menu_img'] != null) { ?>
                                <img class="card-img-top img-fluid" style="height: 200px;object-fit: contain;" src="./assets/assets/images/food/<?php echo $valueresultGETProduct['menu_img'] ?>" alt="">
                            <?php } else { ?>
                                <img class="card-img-top img-fluid" style="height: 200px;object-fit: contain;" src="./assets/assets/images/food/nopic.png" alt="">
                            <?php } ?>
                            <!-- 0000003F -->
                            <!-- 00000057 -->
                            <div class="card-body limit-txt" style="position: absolute;background-color: #0000006E;bottom: 2px;width: 100%;padding-top: 2px;padding-bottom: 2px;">
                                <div class="float-end">
                                    <b style="color: #FFFFFF;"><?php echo $valueresultGETProduct['menu_name'] ?></b>
                                    <br>
                                    <h5 style="color: #FFFFFF;">฿<?php echo number_format($valueresultGETProduct['menu_price'], 2) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- Modal -->
                <div class="modal fade" id="ModaladdCart<?php echo $valueresultGETProduct['menu_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <?php
                            $menu_id = $valueresultGETProduct['menu_id'];
                            $codeJSMD5 = md5($menu_id);
                            // SQL GET Sub menu
                            $sqlGETSubMenu = $conn->prepare("SELECT * FROM `rtr_sub_menu` WHERE `menu_id`=$menu_id");
                            $sqlGETSubMenu->execute();
                            $resultGETSubMenu = $sqlGETSubMenu->fetchAll();
                            ?>
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel"><?php echo $valueresultGETProduct['menu_name'] ?></h5>
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

        <?php
        }
    }
}

// GetNewOrder
if (isset($_GET['GetNewOrder'])) {
    // SQL GET Shift
    $sqlGETShift = $conn->prepare("SELECT * FROM `rtr_shift` WHERE `sh_status`='false'");
    $sqlGETShift->execute();
    $resultGETShift = $sqlGETShift->fetch();
    $idshift = $resultGETShift['sh_id'];
    // SQL GET Order
    $sqlGETOrder = $conn->prepare("SELECT * FROM `rtr_order` WHERE `od_status`='false' AND `sh_number`=$idshift");
    $sqlGETOrder->execute();
    $resultGETOrder = $sqlGETOrder->fetchAll();

    $x = '';
    foreach ($resultGETOrder as $keyresultGETOrder => $valueresultGETOrder) {
        if ($valueresultGETOrder['bell'] == 'false') {
            $x = 'autoplay';
        }
    }

    echo '<audio ' . $x . ' id="audiotag1" src="./assets/mp3/2.mp3" preload="auto"></audio>';
    echo '<div class="card col-sm-6">';
    echo '<div class="card-body">';
    if ($x == 'autoplay') {
        ?>
        <div class="float-end">
            <a href="./action.php?UpdateBell" class="btn btn-success">
                <span class="iconify" data-icon="emojione-monotone:speaker-high-volume"></span>
            </a>
        </div>
    <?php
    }
    echo '<center>';
    echo '<b>ออร์เดอร์ใหม่</b>';
    echo '</center>';
    echo '<div class="list-group mt-4">';
    foreach ($resultGETOrder as $keyresultGETOrder => $valueresultGETOrder) {
        if ($valueresultGETOrder['cook_stauts'] == 'false') {
            if ($valueresultGETOrder['home'] == 'true') {
                $headercard = 'สั่งกลับบ้าน';
            } else {
                $seatid = $valueresultGETOrder['seat_id'];
                // SQL GET Seat
                $sqlGETSeat = $conn->prepare("SELECT `seat_number` FROM `rtr_seat` WHERE `seat_id`=$seatid");
                $sqlGETSeat->execute();
                $resultGETSeat = $sqlGETSeat->fetch();
                $headercard = 'โต๊ะ ' . $resultGETSeat['seat_number'];
            }
            $codeOrder = $valueresultGETOrder['od_code'];
            // SQL GET Sub Order
            $sqlGETSubOrder = $conn->prepare("SELECT * FROM `rtr_sub_order` WHERE `od_code`='$codeOrder' AND `cook_stauts`='false'");
            $sqlGETSubOrder->execute();
            $resultGETSubOrder = $sqlGETSubOrder->fetchAll();
            $text = "return confirm('ยืนยันการทำอาหาร')";

            echo '<a href="./action.php?GetOrderGoCookking=' . $valueresultGETOrder['od_id'] . '" onclick="' . $text . '" class="list-group-item list-group-item-action" style="background-color: #FFFEFE;border: 1px solid indianred;">';
            echo '<div class="d-flex w-100 justify-content-between">';
            echo '<b class="mb-1" style="color: #D35F61;">' . $headercard . '</b>';
            echo '<small class="text-muted">คิวที่ ' . $valueresultGETOrder["od_q"] . '</small>';
            echo '</div>';
            echo '<p class="mb-1">';
            foreach ($resultGETSubOrder as $keyresultGETSubOrder => $valueresultGETSubOrder) {
                $menuID = $valueresultGETSubOrder['menu_id'];
                // SQL GET Name Menu
                $sqlGETNameMenu = $conn->prepare("SELECT `menu_name` FROM `rtr_menu` WHERE `menu_id`=$menuID");
                $sqlGETNameMenu->execute();
                $resultGETNameMenu = $sqlGETNameMenu->fetch();

                echo '<div class="border-bottom">';
                echo '<div class="float-end">';
                echo '฿' . number_format($valueresultGETSubOrder['price'], 2) . '/' . $valueresultGETSubOrder['sum'];
                echo '</div>';
                echo '<b>' . $resultGETNameMenu['menu_name'] . '</b>';
                echo "<br>";
                echo '<small>' . $valueresultGETSubOrder['menu_detail'] . '</small>';
                echo '</div>';
                echo "<br>";
            }
            echo '</p>';
            echo '<small class="text-muted">ออร์เดอร์ ' . $codeOrder . '</small>';
            echo '</a>';
        }
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

// ModalStatusSeat
if (isset($_GET['ModalStatusSeat'])) {
    // SQL GET All Seat
    $sqlGETAllSeat = $conn->prepare("SELECT * FROM `rtr_seat` WHERE `seat_status`='true' ORDER BY `seat_number` ASC");
    $sqlGETAllSeat->execute();
    $resultGETAllSeat = $sqlGETAllSeat->fetchAll();

    echo '<div class="row">';
    foreach ($resultGETAllSeat as $keyresultGETAllSeat => $valueresultGETAllSeat) {

        $SeatIdModalvar = $valueresultGETAllSeat['seat_id'];
        // SQL GET Order
        $sqlGETOrderSeatModal = $conn->prepare("SELECT * FROM `rtr_order` WHERE `od_status`='false' AND `seat_id`=$SeatIdModalvar");
        $sqlGETOrderSeatModal->execute();
        $resultGETOrderSeatModal = $sqlGETOrderSeatModal->fetch();

        if ($resultGETOrderSeatModal == null) {
            echo '<div class="col-6">';
            echo '<div class="card card-seat-free">';
            echo '<div class="card-body">';
            echo '<center>';
            echo '<span class="iconify" data-icon="vs:table-alt" data-width="30" data-height="30"></span>';
            echo '<br>';
            echo '<b>โต๊ะที่  ' . $valueresultGETAllSeat['seat_number'] . ' </b>';
            echo '<br>';
            echo '<span class="badge bg-secondary">ว่าง</span>';
            echo '</center>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="col-6">';
            echo '<div class="card card-seat-use">';
            echo '<div class="card-body">';
            echo '<center>';
            echo '<span class="iconify" data-icon="vs:table-alt" data-width="30" data-height="30"></span>';
            echo '<br>';
            echo '<b>โต๊ะที่ ' . $valueresultGETAllSeat['seat_number'] . '</b>';
            echo '<br>';
            if ($resultGETOrderSeatModal['cook_stauts'] == 'false') {
                echo '<span class="badge bg-success">กำลังใช้งาน</span>';
            } elseif ($resultGETOrderSeatModal['cook_stauts'] == 'true' && $resultGETOrderSeatModal['stauts_serve'] == 'false') {
                echo '<span class="badge bg-warning">กำลังทำอาหาร</span>';
            } elseif ($resultGETOrderSeatModal['stauts_serve'] == 'true') {
                echo '<span class="badge bg-success">เสิร์ฟอาหารแล้ว</span>';
            }
            echo '</center>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
    echo '</div>';
}

// searchBill
if (isset($_GET['searchBill'])) {
    $code = $_GET['searchBill'];
    // SQL GET Order
    $sqlGETOrderModal = $conn->prepare("SELECT 
                                        `seat_id`,
                                        `od_date_add`,
                                        `od_q`,`name_payer`,
                                        `phone_payer`,
                                        `order_discount`,
                                        `type_discount`,
                                        `sum_price`,
                                        `service_charge`,
                                        `vat`,`get_price`,
                                        `chang_price`
                                        FROM `rtr_order` WHERE `od_code`='$code'");
    $sqlGETOrderModal->execute();
    $resultGETOrderModal = $sqlGETOrderModal->fetch();
    // SQL GET Sub Order
    $sqlGETSubOrderModal = $conn->prepare("SELECT 
                                            `menu_id`,
                                            `menu_detail`,
                                            `price`,
                                            `sum` 
                                           FROM `rtr_sub_order` WHERE `od_code`='$code'");
    $sqlGETSubOrderModal->execute();
    $resultGETSubOrderModal = $sqlGETSubOrderModal->fetchAll();

    // SQL GET Img Logo
    $sqlGETImgLogo = $conn->prepare("SELECT * FROM `rtr_img_logo` WHERE `logo_status`='true'");
    $sqlGETImgLogo->execute();
    $resultGETImgLogo = $sqlGETImgLogo->fetch();

    $codeOrder = '%23' . substr($code, 1);
    if ($resultGETOrderModal != null) {

        if ($resultGETOrderModal['seat_id'] != null) {
            $SeatIDBill = $resultGETOrderModal['seat_id'];
            // SQL GET Seat
            $sqlGETSeatBill = $conn->prepare("SELECT `seat_number` FROM `rtr_seat` WHERE `seat_id`=$SeatIDBill");
            $sqlGETSeatBill->execute();
            $resultGETSeatBill = $sqlGETSeatBill->fetch();
        }
    ?>
        <h5 style="text-align: center;color: black;">ใบเสร็จรับเงิน</h5>
            <h5 style="text-align: center;color: black;">
                วันที่ <?php echo DateThai($resultGETOrderModal['od_date_add']) ?>
                <br>
                คิวที่ <?php echo $resultGETOrderModal['od_q'] ?>
                <?php if ($resultGETOrderModal['seat_id'] != null) { ?>
                    โต๊ะที่ <?php echo $resultGETSeatBill['seat_number'] ?>
                <?php } ?>
                <br>
                <?php
                if ($resultGETOrderModal['name_payer'] != null) {
                    echo $resultGETOrderModal['name_payer'] . '/' . $resultGETOrderModal['phone_payer'];
                }
                ?>
            </h5>
        <table class="table">
            <tbody>
                <tr>
                    <td style="text-align: left;color: black;">
                    <?php 
                    foreach ($resultGETSubOrderModal as $keyresultGETSubOrderModal => $valueresultGETSubOrderModal) {
                        $menuID = $valueresultGETSubOrderModal['menu_id'];
                        // SQL GET Name Menu
                        $sqlGETNameMenu = $conn->prepare("SELECT * FROM `rtr_menu` WHERE `menu_id`=$menuID");
                        $sqlGETNameMenu->execute();
                        $resultGETNameMenu = $sqlGETNameMenu->fetch();
                        echo "<h5>".$resultGETNameMenu['menu_name']." ". $valueresultGETSubOrderModal['menu_detail']."</h5>";
                    }
                    ?>
                    </td>
                    <td style="text-align: right;color: black;">
                    <?php 
                    foreach ($resultGETSubOrderModal as $keyresultGETSubOrderModal => $valueresultGETSubOrderModal) {
                        $menuID = $valueresultGETSubOrderModal['menu_id'];
                        // SQL GET Name Menu
                        $sqlGETNameMenu = $conn->prepare("SELECT * FROM `rtr_menu` WHERE `menu_id`=$menuID");
                        $sqlGETNameMenu->execute();
                        $resultGETNameMenu = $sqlGETNameMenu->fetch();
                    echo "<h5>".'฿' . number_format($valueresultGETSubOrderModal['price'], 2) . '/'. $valueresultGETSubOrderModal['sum']."</h5>";
                    }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;color: black;">
                    <h5>ยอดรวม</h5>
                    <h5>ราคาสุทธิ</h5>
                    <h5>รับเงิน</h5>
                    <h5>เงินทอน</h5>
                    </td>
                    <td style="text-align: right;color: black;">
                    <?php
                    if ($resultGETOrderModal['order_discount'] != null) {
                        $dis = $resultGETOrderModal['order_discount'];
                    } else {
                        $dis = null;
                    }
                    if ($resultGETOrderModal['type_discount'] != null) {
                        $disType = $resultGETOrderModal['type_discount'];
                    } else {
                        $disType = null;
                    }
                    $calDiscount = $resultGETOrderModal['sum_price'];
                    if ($disType == 'B') {
                        $DataDis = '฿' . number_format($dis, 2);
                        $calDiscount = $calDiscount - $dis;
                    } else {
                        $DataDis = $dis . '%';
                        $calDiscount = $calDiscount - ($calDiscount * $dis / 100);
                    }

                    if ($disType != null) {
                        echo "ส่วนลด".$DataDis;
                        echo "ปรับเป็น".'฿' . number_format($calDiscount, 2);
                    }
                    if ($resultGETOrderModal['service_charge'] != null) {
                    $servicech = $resultGETOrderModal['service_charge'];
                    } else {
                        $servicech = null;
                    }
                    if ($servicech != null) {
                        $calDiscount = $calDiscount + ($calDiscount * $servicech / 100);
                    }
                    if ($servicech != null) {
                        echo "Service Charge". $servicech . '%';
                        echo "ปรับเป็น".'฿' . number_format($calDiscount, 2);
                    }

                                    if ($resultGETOrderModal['vat'] != null) {
                                        $vat = $resultGETOrderModal['vat'];
                                    } else {
                                        $vat = null;
                                    }
                                    if ($vat != null) {
                                        if ($vat == 'vatout') {
                                            $calDiscount = $calDiscount + ($calDiscount * 7 / 100);
                                            $calDiscountout = ($calDiscount * 7 / 100);
                                        } else {
                                            $calDiscountx = $calDiscount * ($calDiscount / ($calDiscount + ($calDiscount * 7 / 100)));
                                            $calDiscounty = $calDiscount - ($calDiscount * ($calDiscount / ($calDiscount + ($calDiscount * 7 / 100))));
                                        }
                                    }

                                    if ($vat != null) {
                                        if ($vat == 'vatout') {
                                            echo "ภาษีมูลค่าเพิ่ม 7%".'฿' . number_format($calDiscountout, 2);
                                            echo "ปรับเป็น".'฿' . number_format($calDiscount, 2);
                                        } 
                                        else { 
                                            echo "ภาษีมูลค่าเพิ่ม 7%".'฿' . number_format($calDiscounty, 2);
                                            echo "ราคาไม่รวมภาษีมูลค่าเพิ่ม".'฿' . number_format($calDiscountx, 2);
                                        }
                                    }
                        echo '<h5>'.'฿' . number_format($resultGETOrderModal['sum_price'], 2).'</h5>';
                        echo '<h5>'.'฿' . number_format($calDiscount, 2).'</h5>';
                        echo '<h5>'.'฿' . number_format($resultGETOrderModal['get_price'], 2).'</h5>';
                        echo '<h5>'.'฿' . number_format($resultGETOrderModal['chang_price'], 2).'</h5>';
                        ?>
                </td>
                </tr>
            </tbody>
        </table>
        <center>
            <img style="width: 300px;" alt='Barcode Generator TEC-IT' src='https://barcode.tec-it.com/barcode.ashx?data=<?php echo $codeOrder ?>&code=Code128' />
        </center>
    <?php
    }
    else {
        echo "<center>".
                "ไม่มีรายการ".
            "</center>";
    }
}
?>
    <?php
// searchBillPaySuccess
if (isset($_GET['searchBillPaySuccess'])) {
    $code = $_GET['searchBillPaySuccess'];
    // SQL GET Order
    $sqlGETOrderModal = $conn->prepare("SELECT 
                                        `seat_id`,
                                        `od_date_add`,
                                        `od_q`,`name_payer`,
                                        `phone_payer`,
                                        `order_discount`,
                                        `type_discount`,
                                        `sum_price`,
                                        `service_charge`,
                                        `vat`,`get_price`,
                                        `chang_price`
                                        FROM `rtr_order` WHERE `od_code`='$code'");
    $sqlGETOrderModal->execute();
    $resultGETOrderModal = $sqlGETOrderModal->fetch();
    // SQL GET Sub Order
    $sqlGETSubOrderModal = $conn->prepare("SELECT 
                                        `menu_id`,
                                        `menu_detail`,
                                        `price`,
                                        `sum` 
                                        FROM `rtr_sub_order` WHERE `od_code`='$code'");
    $sqlGETSubOrderModal->execute();
    $resultGETSubOrderModal = $sqlGETSubOrderModal->fetchAll();

    // SQL GET Img Logo
    // $sqlGETImgLogo = $conn->prepare("SELECT * FROM `rtr_img_logo` WHERE `logo_status`='true'");
    // $sqlGETImgLogo->execute();
    // $resultGETImgLogo = $sqlGETImgLogo->fetch();

    $codeOrder = '%23' . substr($code, 1);

    if ($resultGETOrderModal != null) {

        if ($resultGETOrderModal['seat_id'] != null) {
            $SeatIDBill = $resultGETOrderModal['seat_id'];
            // SQL GET Seat
            $sqlGETSeatBill = $conn->prepare("SELECT `seat_number` FROM `rtr_seat` WHERE `seat_id`=$SeatIDBill");
            $sqlGETSeatBill->execute();
            $resultGETSeatBill = $sqlGETSeatBill->fetch();
        }
    ?>

        <h5 style="text-align: center;color: black;">ใบเสร็จรับเงิน</h5>
            <h5 style="text-align: center;color: black;">
                วันที่ <?php echo DateThai($resultGETOrderModal['od_date_add']) ?>
                <br>
                คิวที่ <?php echo $resultGETOrderModal['od_q'] ?>
                <?php if ($resultGETOrderModal['seat_id'] != null) { ?>
                    โต๊ะที่ <?php echo $resultGETSeatBill['seat_number'] ?>
                <?php } ?>
                <br>
                <?php
                if ($resultGETOrderModal['name_payer'] != null) {
                    echo $resultGETOrderModal['name_payer'] . '/' . $resultGETOrderModal['phone_payer'];
                }
                ?>
            </h5>
        <table class="table">
            <tbody>
                <tr>
                    <td style="text-align: left;color: black;">
                    <?php 
                    foreach ($resultGETSubOrderModal as $keyresultGETSubOrderModal => $valueresultGETSubOrderModal) {
                        $menuID = $valueresultGETSubOrderModal['menu_id'];
                        // SQL GET Name Menu
                        $sqlGETNameMenu = $conn->prepare("SELECT * FROM `rtr_menu` WHERE `menu_id`=$menuID");
                        $sqlGETNameMenu->execute();
                        $resultGETNameMenu = $sqlGETNameMenu->fetch();
                        echo $resultGETNameMenu['menu_name']." ". $valueresultGETSubOrderModal['menu_detail']."<br>";
                    }
                    ?>
                    </td>
                    <td style="text-align: right;color: black;">
                    <?php 
                    foreach ($resultGETSubOrderModal as $keyresultGETSubOrderModal => $valueresultGETSubOrderModal) {
                        $menuID = $valueresultGETSubOrderModal['menu_id'];
                        // SQL GET Name Menu
                        $sqlGETNameMenu = $conn->prepare("SELECT * FROM `rtr_menu` WHERE `menu_id`=$menuID");
                        $sqlGETNameMenu->execute();
                        $resultGETNameMenu = $sqlGETNameMenu->fetch();
                    echo '฿' . number_format($valueresultGETSubOrderModal['price'], 2) . '/'. $valueresultGETSubOrderModal['sum']."<br>";
                    }
                    ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;color: black;">
                        ยอดรวม <br>
                        ราคาสุทธิ <br>
                        รับเงิน <br>
                        เงินทอน
                    </td>
                    <td style="text-align: right;color: black;">
                    <?php
                    if ($resultGETOrderModal['order_discount'] != null) {
                        $dis = $resultGETOrderModal['order_discount'];
                    } else {
                        $dis = null;
                    }
                    if ($resultGETOrderModal['type_discount'] != null) {
                        $disType = $resultGETOrderModal['type_discount'];
                    } else {
                        $disType = null;
                    }
                    $calDiscount = $resultGETOrderModal['sum_price'];
                    if ($disType == 'B') {
                        $DataDis = '฿' . number_format($dis, 2);
                        $calDiscount = $calDiscount - $dis;
                    } else {
                        $DataDis = $dis . '%';
                        $calDiscount = $calDiscount - ($calDiscount * $dis / 100);
                    }

                    if ($disType != null) {
                        echo "ส่วนลด".$DataDis;
                        echo "ปรับเป็น".'฿' . number_format($calDiscount, 2);
                    }
                    if ($resultGETOrderModal['service_charge'] != null) {
                    $servicech = $resultGETOrderModal['service_charge'];
                    } else {
                        $servicech = null;
                    }
                    if ($servicech != null) {
                        $calDiscount = $calDiscount + ($calDiscount * $servicech / 100);
                    }
                    if ($servicech != null) {
                        echo "Service Charge". $servicech . '%';
                        echo "ปรับเป็น".'฿' . number_format($calDiscount, 2);
                    }

                                    if ($resultGETOrderModal['vat'] != null) {
                                        $vat = $resultGETOrderModal['vat'];
                                    } else {
                                        $vat = null;
                                    }
                                    if ($vat != null) {
                                        if ($vat == 'vatout') {
                                            $calDiscount = $calDiscount + ($calDiscount * 7 / 100);
                                            $calDiscountout = ($calDiscount * 7 / 100);
                                        } else {
                                            $calDiscountx = $calDiscount * ($calDiscount / ($calDiscount + ($calDiscount * 7 / 100)));
                                            $calDiscounty = $calDiscount - ($calDiscount * ($calDiscount / ($calDiscount + ($calDiscount * 7 / 100))));
                                        }
                                    }

                                    if ($vat != null) {
                                        if ($vat == 'vatout') {
                                            echo "ภาษีมูลค่าเพิ่ม 7%".'฿' . number_format($calDiscountout, 2);
                                            echo "ปรับเป็น".'฿' . number_format($calDiscount, 2);
                                        } 
                                        else { 
                                            echo "ภาษีมูลค่าเพิ่ม 7%".'฿' . number_format($calDiscounty, 2);
                                            echo "ราคาไม่รวมภาษีมูลค่าเพิ่ม".'฿' . number_format($calDiscountx, 2);
                                        }
                                    }
                        echo '฿' . number_format($resultGETOrderModal['sum_price'], 2)."<br>";
                        echo '฿' . number_format($calDiscount, 2)."<br>";
                        echo '฿' . number_format($resultGETOrderModal['get_price'], 2)."<br>";
                        echo '฿' . number_format($resultGETOrderModal['chang_price'], 2);
                        ?>
                </td>
                </tr>
            </tbody>
        </table>
        <center>
            <img style="width: 300px;" alt='Barcode Generator TEC-IT' src='https://barcode.tec-it.com/barcode.ashx?data=<?php echo $codeOrder ?>&code=Code128' />
        </center>
        
    <?php
    } 
    else {
        echo "<center>".
                "ไม่มีรายการ".
            "</center>";
    }
} // searchBillPaySuccess
?>

<?php
// searchBillEngin
if (isset($_GET['searchBillEngin'])) {
    $code = $_GET['searchBillEngin'];
    // SQL GET Order
    $sqlGETOrderModal = $conn->prepare("SELECT * FROM `rtr_order` WHERE `od_code`='$code'");
    $sqlGETOrderModal->execute();
    $resultGETOrderModal = $sqlGETOrderModal->fetch();

    if ($resultGETOrderModal != null) {
        echo 'true';
    } else {
        echo 'false';
    }
}

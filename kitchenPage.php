<?php
include("./FLO.php");
// SQL GET Shift
$sqlGETShift = $conn->prepare("SELECT * FROM `rtr_shift` WHERE `sh_status`='false'");
$sqlGETShift->execute();
$resultGETShift = $sqlGETShift->fetch();
$idshift = $resultGETShift['sh_id'];
// SQL GET Order
$sqlGETOrder = $conn->prepare("SELECT * FROM `rtr_order` WHERE `od_status`='false' AND `sh_number`=$idshift");
$sqlGETOrder->execute();
$resultGETOrder = $sqlGETOrder->fetchAll();
?>

<script>
    function getDataFromDb() {
        $.ajax({
            url: "./ajax/ajax.php?GetNewOrder",
            type: "POST",
            data: '',
            success: function(result) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("pills-home").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "./ajax/ajax.php?GetNewOrder", true);
                xmlhttp.send();
            }
        });
    }
    setInterval(getDataFromDb, 5000); // 1000 = 1 second
</script>

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">ออร์เดอร์ใหม่</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">ออร์เดอร์</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">เสิร์ฟแล้ว</button>
    </li>
</ul>
<div class="tab-content" id="pills-tabContent">
    <!-- NewOrder -->
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <div class="card col-sm-6">
            <div class="card-body">
                <center>
                    <b>ออร์เดอร์ใหม่</b>
                </center>
                <div class="list-group mt-4">
                    <?php foreach ($resultGETOrder as $keyresultGETOrder => $valueresultGETOrder) { ?>
                        <?php if ($valueresultGETOrder['cook_stauts'] == 'false') { ?>
                            <?php
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
                            ?>
                            <a href="./action.php?GetOrderGoCookking=<?php echo $valueresultGETOrder['od_id'] ?>" onclick="return confirm('ยืนยันการทำอาหาร')" class="list-group-item list-group-item-action" style="background-color: #FFFEFE;border: 1px solid indianred;">
                                <div class="d-flex w-100 justify-content-between">
                                    <b class="mb-1" style="color: #D35F61;"><?php echo $headercard ?></b>
                                    <small class="text-muted">คิวที่ <?php echo $valueresultGETOrder['od_q'] ?></small>
                                </div>
                                <p class="mb-1">
                                    <?php foreach ($resultGETSubOrder as $keyresultGETSubOrder => $valueresultGETSubOrder) {
                                        $menuID = $valueresultGETSubOrder['menu_id'];
                                        // SQL GET Name Menu
                                        $sqlGETNameMenu = $conn->prepare("SELECT `menu_name` FROM `rtr_menu` WHERE `menu_id`=$menuID");
                                        $sqlGETNameMenu->execute();
                                        $resultGETNameMenu = $sqlGETNameMenu->fetch();
                                    ?>
                                        <div class="border-bottom">
                                            <div class="float-end">
                                                <?php echo '฿' . number_format($valueresultGETSubOrder['price'], 2) . '/' . $valueresultGETSubOrder['sum'] ?>
                                            </div>
                                            <b><?php echo $resultGETNameMenu['menu_name']; ?></b>
                                            <?php
                                            echo "<br>";
                                            ?>
                                            <small><?php echo $valueresultGETSubOrder['menu_detail'] ?></small>
                                        </div>
                                    <?php
                                        echo "<br>";
                                    } ?>
                                </p>
                                <small class="text-muted">ออร์เดอร์ <?php echo $codeOrder ?></small>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /NewOrder -->

    <!-- Order -->
    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
        <div class="card col-sm-6">
            <div class="card-body">
                <center>
                    <b>ออร์เดอร์</b>
                </center>
                <div class="list-group mt-4">
                    <?php foreach ($resultGETOrder as $keyresultGETOrder => $valueresultGETOrder) { ?>
                        <?php if ($valueresultGETOrder['cook_stauts'] == 'true' && $valueresultGETOrder['stauts_serve'] == 'false') { ?>
                            <?php
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
                            $sqlGETSubOrder = $conn->prepare("SELECT * FROM `rtr_sub_order` WHERE `od_code`='$codeOrder'");
                            $sqlGETSubOrder->execute();
                            $resultGETSubOrder = $sqlGETSubOrder->fetchAll();
                            ?>
                            <a href="./action.php?ToServeFood=<?php echo $valueresultGETOrder['od_id'] ?>" onclick="return confirm('ยืนยันการเสิร์ฟอาหาร')" class="list-group-item list-group-item-action" style="background-color: #FEFFFE;border: 1px solid green;">
                                <div class="d-flex w-100 justify-content-between">
                                    <b class="mb-1" style="color: #308B28;"><?php echo $headercard ?></b>
                                    <small class="text-muted">คิวที่ <?php echo $valueresultGETOrder['od_q'] ?></small>
                                </div>
                                <p class="mb-1">
                                    <?php foreach ($resultGETSubOrder as $keyresultGETSubOrder => $valueresultGETSubOrder) {
                                        $menuID = $valueresultGETSubOrder['menu_id'];
                                        // SQL GET Name Menu
                                        $sqlGETNameMenu = $conn->prepare("SELECT `menu_name` FROM `rtr_menu` WHERE `menu_id`=$menuID");
                                        $sqlGETNameMenu->execute();
                                        $resultGETNameMenu = $sqlGETNameMenu->fetch();
                                    ?>
                                        <div class="border-bottom">
                                            <div class="float-end">
                                                <?php echo '฿' . number_format($valueresultGETSubOrder['price'], 2) . '/' . $valueresultGETSubOrder['sum'] ?>
                                            </div>
                                            <b><?php echo $resultGETNameMenu['menu_name']; ?></b>
                                            <?php
                                            echo "<br>";
                                            ?>
                                            <small><?php echo $valueresultGETSubOrder['menu_detail'] ?></small>
                                        </div>
                                    <?php
                                        echo "<br>";
                                    } ?>
                                </p>
                                <small class="text-muted">ออร์เดอร์ <?php echo $codeOrder ?></small>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Order -->

    <!-- serve -->
    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
        <div class="card col-sm-6">
            <div class="card-body">
                <center>
                    <b>เสิร์ฟแล้ว</b>
                </center>
                <div class="list-group mt-4">
                    <?php foreach ($resultGETOrder as $keyresultGETOrder => $valueresultGETOrder) { ?>
                        <?php if ($valueresultGETOrder['stauts_serve'] == 'true') { ?>
                            <?php
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
                            $sqlGETSubOrder = $conn->prepare("SELECT * FROM `rtr_sub_order` WHERE `od_code`='$codeOrder'");
                            $sqlGETSubOrder->execute();
                            $resultGETSubOrder = $sqlGETSubOrder->fetchAll();
                            ?>
                            <a class="list-group-item list-group-item-action" style="background-color: #FEFFFE;border: 1px solid green;">
                                <div class="d-flex w-100 justify-content-between">
                                    <b class="mb-1" style="color: #308B28;"><?php echo $headercard ?></b>
                                    <small class="text-muted">คิวที่ <?php echo $valueresultGETOrder['od_q'] ?></small>
                                </div>
                                <p class="mb-1">
                                    <?php foreach ($resultGETSubOrder as $keyresultGETSubOrder => $valueresultGETSubOrder) {
                                        $menuID = $valueresultGETSubOrder['menu_id'];
                                        // SQL GET Name Menu
                                        $sqlGETNameMenu = $conn->prepare("SELECT `menu_name` FROM `rtr_menu` WHERE `menu_id`=$menuID");
                                        $sqlGETNameMenu->execute();
                                        $resultGETNameMenu = $sqlGETNameMenu->fetch();
                                    ?>
                                        <div class="border-bottom">
                                            <div class="float-end">
                                                <?php echo '฿' . number_format($valueresultGETSubOrder['price'], 2) . '/' . $valueresultGETSubOrder['sum'] ?>
                                            </div>
                                            <b><?php echo $resultGETNameMenu['menu_name']; ?></b>
                                            <?php
                                            echo "<br>";
                                            ?>
                                            <small><?php echo $valueresultGETSubOrder['menu_detail'] ?></small>
                                        </div>
                                    <?php
                                        echo "<br>";
                                    } ?>
                                </p>
                                <small class="text-muted">ออร์เดอร์ <?php echo $codeOrder ?></small>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /serve -->

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php

include("./LLO.php");
?>
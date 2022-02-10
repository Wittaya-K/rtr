<?php
ob_start();
include('./FLO.php');
// SQL GET Shift
$sqlGETShift = $conn->prepare("SELECT `sh_id`, `user_id` FROM `rtr_shift` WHERE `sh_status`='false'");
$sqlGETShift->execute();
$resultGETShift = $sqlGETShift->fetch();

if ($resultGETShift == null) {
    header('location:./ManageShift.php');
    exit;
} else {
    if ($user_id != $resultGETShift['user_id']) {
        header('location:./ManageShift.php');
        exit;
    }
}
$code = null;
if (isset($_GET['code_or'])) {
    $code = $_GET['code_or'];
}
$codeOrderEncode = str_replace("#", "%23", $code);

$priceOrder = 0;
// SQL GET Order
$sqlGETOrder = $conn->prepare("SELECT * FROM `rtr_order` WHERE `od_code`='$code'");
$sqlGETOrder->execute();
$resultGETOrder = $sqlGETOrder->fetch();
if ($resultGETOrder != null) {
    $priceOrder = $resultGETOrder['sum_price'];
} else {
    $priceOrder = 0.00;
}

// SQL GET servicech
$sqlGETServicech = $conn->prepare("SELECT * FROM `rtr_servicech`");
$sqlGETServicech->execute();
$resultGETServicech = $sqlGETServicech->fetch();

// SQL GET Pay Type
$sqlGETPayType = $conn->prepare("SELECT * FROM `rtr_pay_type`");
$sqlGETPayType->execute();
$resultGETPayType = $sqlGETPayType->fetchAll();

// $textcode = '#sdfefewefwef';
// str_replace("#", "%23", $textcode);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css'> -->
<link rel="stylesheet" href="./style.css">
<!-- Modal -->
<div class="modal fade" id="barcodeScanner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Scan barcode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="interactive" class="viewport">
                    <video style="width: 100%;height: 100%;" autoplay="true" preload="auto" src="" muted="true" playsinline="true"></video>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .hid {
            display: none;
            /* ซ่อน  */
        }
    }

    .card-main{
        height: 850px;
    }
</style>
<div class="row">
    <div class="col-sm-6 hid">
        <div class="card card-main">
            <div class="float-end">
                <a type="button" data-bs-toggle="modal" data-bs-target="#ModalSearch" class="btn"><span class="iconify" data-icon="akar-icons:search" data-width="30" data-height="30"></span></a>
                <!-- Modal -->
                <div class="modal fade" id="ModalSearch" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5>ค้นหา</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="input-group">
                                    <input onkeyup="search(this.value)" value="" type="search" class="form-control" id="searchEngin" placeholder="#xxxxxxxxxx" autofocus>
                                </div>
                                <div id="statusSearch" hidden>
                                    <small style="color: red;">ไม่พบข้อมูล</small>
                                </div>
                                <!-- JS -->
                                <script>
                                    function search(value) {
                                        code_Order = value.substring(1);
                                        if (value != '') {
                                            var xmlhttp = new XMLHttpRequest();
                                            xmlhttp.onreadystatechange = function() {
                                                if (this.readyState == 4 && this.status == 200) {
                                                    var next = this.responseText;
                                                    console.log(this.responseText);
                                                    if (next == 'true') {
                                                        console.log(next);
                                                        var data = document.getElementById('searchEngin').value;
                                                        data = data.substring(1)
                                                        window.location.replace("./PaymentPage.php?code_or=%23" + data);
                                                    } else {
                                                        document.getElementById('statusSearch').hidden = false;
                                                    }
                                                }
                                            };
                                            xmlhttp.open("GET", "./ajax/ajax.php?searchBillEngin=%23" + code_Order, true);
                                            xmlhttp.send();
                                        }
                                    }
                                </script>
                                <!-- JS -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal -->

            </div>
            <div class="card-body" style="padding-left: 0px;padding-right: 0px;">
                <div class="form-group">
                    <center>
                        <div class="input-group" style="width: 70%;">
                            <input onkeyup="InsertCodeOrd(this.value)" value="<?php echo $code ?>" type="search" class="form-control" id="floatingInputPayment" placeholder="#xxxxxxxxxx" readonly>
                            <button class="btn btn-secondary" type="button" id="button-addon2" data-toggle="modal" data-target="#barcodeScanner">
                                <span class="iconify" data-icon="ant-design:camera-filled" data-width="20" data-height="20"></span>
                            </button>
                        </div>
                    </center>
                </div>
                <?php if ($resultGETOrder != null) { ?>
                    <?php if ($resultGETOrder['payment_status'] == 'false') { ?>
                        <div class="form-group">
                            <div class="card">
                                <div class="card-body" style="padding-right: 10px;padding-left: 10px;">
                                    <!-- calculate -->
                                    <div class="row">
                                        <div class="col-sm-4 mt-2">
                                            <?php $finalprice = $priceOrder; ?>
                                            <?php if (isset($_SESSION['discountType']) == null) { ?>
                                                <?php if (isset($_SESSION['serviceCharge']) == null) { ?>
                                                    <?php if (isset($_SESSION['vat']) == null) { ?>
                                                        <a type="button" class="text-primary" id="btndiscount" data-bs-toggle="modal" data-bs-target="#ModalDiscount">เพิ่มส่วนลด</a>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else if ($_SESSION['discountType'] == 'B') { ?>
                                                <a type="button" id="btndiscountB" data-bs-toggle="modal" data-bs-target="#ModalDiscountHave">
                                                    ส่วนลด <span class="badge rounded-pill" style="color: #3950A2;">฿<?php echo number_format($_SESSION['discount'], 2) ?></span>
                                                </a>
                                                <?php
                                                $finalprice = $finalprice - $_SESSION['discount'];
                                                ?>
                                            <?php } else if ($_SESSION['discountType'] == 'P') { ?>
                                                <a type="button" id="btndiscountP" data-bs-toggle="modal" data-bs-target="#ModalDiscountHave">
                                                    ส่วนลด <span class="badge rounded-pill" style="color: #3950A2;"><?php echo $_SESSION['discount'] ?>%</span>
                                                </a>
                                                <?php
                                                $finalprice = $finalprice - ($finalprice * $_SESSION['discount'] / 100);
                                                ?>
                                            <?php } ?>

                                            <!-- <a type="button" class="text-primary" id="btndiscount" data-bs-toggle="modal" data-bs-target="#ModalDiscount">เพิ่มส่วนลด</a> -->
                                            <!-- ModalDiscountHave -->
                                            <div class="modal fade" id="ModalDiscountHave" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content" style="background-color: #FFFFFFC9;">
                                                        <div class="modal-body">

                                                            <div class="card" style="border: 1px solid #23491D;height: 100%;background-color: #d3fccd;">
                                                                <div class="card-body">
                                                                    <center>
                                                                        <h3>ส่วนลด</h3><br>
                                                                        <?php if ($_SESSION['discountType'] == 'B') { ?>
                                                                            <p style="font-size: 22px;"><?php echo number_format($_SESSION['discount'], 2) . ' บาท' ?></p>
                                                                        <?php } else { ?>
                                                                            <p style="font-size: 22px;"><?php echo $_SESSION['discount'] . '%' ?></p>
                                                                        <?php } ?>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                            <a onclick="return confirm('ยืนยันการยกเลิกส่วนลด')" href="./action.php?cancelDiscount&code=<?php echo $codeOrderEncode ?>" class="btn btn-block btn-danger">ยกเลิกส่วนลด</a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /ModalDiscountHave -->
                                            <!-- ---------------------------------------- -->
                                            <!-- ModalDiscount -->
                                            <div class="modal fade" id="ModalDiscount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">

                                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">บาท</a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">%</a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content" id="myTabContent">

                                                                <!-- discountBath -->
                                                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                                    <form action="./action.php?code=<?php echo $codeOrderEncode ?>" method="post">
                                                                        <div class="form-group mt-3">
                                                                            <label for="" class="form-label">ราคาอาหาร</label>
                                                                            <input type="number" id="pricecing" value="<?php echo $priceOrder ?>" class="form-control" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="" class="form-label">ราคาส่วนลด(บาท)</label>
                                                                            <input onkeyup="discutB(this.value)" type="number" name="discountB" id="discountB" class="form-control" placeholder="กรอกราคาส่วนลด(บาท)" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="" class="form-label">ราคาสุทธิ</label>
                                                                            <input type="number" id="priceDiscutB" value="<?php echo $priceOrder ?>" class="form-control" readonly>
                                                                        </div>
                                                                        <div class="form-group mt-3">
                                                                            <button class="btn btn-block btn-primary" id="btndiscutB" type="submit" name="saveDiscountB">ตกลง</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <script>
                                                                    function discutB(value) {
                                                                        var Sumprice = document.getElementById('pricecing').value;
                                                                        var pricedis = Sumprice - value;
                                                                        document.getElementById('priceDiscutB').value = pricedis;
                                                                        if (pricedis < 0) {
                                                                            document.getElementById('btndiscutB').disabled = true;
                                                                        } else {
                                                                            document.getElementById('btndiscutB').disabled = false;
                                                                        }
                                                                    }
                                                                </script>
                                                                <!-- /discountBath -->

                                                                <!-- discountPercen -->
                                                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                                    <form action="./action.php?code=<?php echo $codeOrderEncode ?>" method="post">
                                                                        <div class="form-group mt-3">
                                                                            <label for="" class="form-label">ราคาอาหาร</label>
                                                                            <input type="number" id="pricecingP" value="<?php echo $priceOrder ?>" class="form-control" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="" class="form-label">ราคาส่วนลด(%)</label>
                                                                            <input onkeyup="discutP(this.value)" type="number" name="discountP" id="discountP" class="form-control" placeholder="กรอกราคาส่วนลด(%)" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="" class="form-label">ราคาสุทธิ</label>
                                                                            <input type="number" id="priceDiscutP" value="<?php echo $priceOrder ?>" class="form-control" readonly>
                                                                        </div>
                                                                        <div class="form-group mt-3">
                                                                            <button class="btn btn-block btn-primary" id="btndiscutP" type="submit" name="saveDiscountP">ตกลง</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <script>
                                                                    function discutP(value) {
                                                                        var Sumprice = document.getElementById('pricecingP').value;
                                                                        var discutPricePercen = Sumprice - (Sumprice * value / 100);
                                                                        document.getElementById('priceDiscutP').value = discutPricePercen;
                                                                        if (discutPricePercen < 0) {
                                                                            document.getElementById('btndiscutP').disabled = true;
                                                                        } else {
                                                                            document.getElementById('btndiscutP').disabled = false;
                                                                        }
                                                                    }
                                                                </script>
                                                                <!-- /discountPercen -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /ModalDiscount -->
                                        </div>
                                        <div class="col-sm-4 mt-2">
                                            <?php if ($resultGETServicech['servicech_status'] == 'true') { ?>
                                                <?php
                                                if (isset($_SESSION['serviceCharge']) != null) {
                                                    $check = 'checked';
                                                    $finalprice = $finalprice + ($finalprice * $_SESSION['serviceCharge'] / 100);
                                                } else {
                                                    $check = '';
                                                }
                                                ?>
                                                <?php if (isset($_SESSION['serviceCharge']) != null) { ?>
                                                    <div class="form-check" id="formcheck1">
                                                        <input <?php echo $check ?> onclick="addservice()" class="form-check-input" type="checkbox" value="<?php echo $resultGETServicech['servicech'] ?>" id="flexCheckDefault">
                                                        <label class="form-check-label text-primary" for="flexCheckDefault">
                                                            service charge(<?php echo '+' . $resultGETServicech['servicech'] ?>%)
                                                        </label>
                                                    </div>
                                                <?php } else { ?>
                                                    <?php if (isset($_SESSION['vat']) == null) { ?>
                                                        <div class="form-check" id="formcheck2">
                                                            <input <?php echo $check ?> onclick="addservice()" class="form-check-input" type="checkbox" value="<?php echo $resultGETServicech['servicech'] ?>" id="flexCheckDefault">
                                                            <label class="form-check-label text-primary" for="flexCheckDefault">
                                                                service charge(<?php echo '+' . $resultGETServicech['servicech'] ?>%)
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                                <script>
                                                    function addservice() {
                                                        var x = document.getElementById("floatingInputPayment").value;
                                                        window.location.href = "./action.php?addserviceCharge&code=%23" + x.substring(1);
                                                    }
                                                </script>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-4 mt-2">
                                            <div class="form-group">
                                                <?php if (isset($_SESSION['vat']) == null) { ?>
                                                    <div class="form-check">
                                                        <input onclick="vatout()" class="form-check-input" type="radio" name="vat" id="vatoutput" value="vatoutput">
                                                        <label class="form-check-label text-primary" for="vatoutput">
                                                            VAT นอก 7%
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input onclick="vatin()" class="form-check-input" type="radio" name="vat" id="vatinput" value="vatinput">
                                                        <label class="form-check-label text-primary" for="vatinput">
                                                            VAT ใน 7%
                                                        </label>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="form-check">
                                                        <button onclick="cancelvat()" class="btn text-primary">ยกเลิก VAT</button>
                                                    </div>
                                                    <?php
                                                    $vat = $_SESSION['vat'];
                                                    if ($vat == 'vatout') {
                                                        $finalprice = $finalprice + ($finalprice * 7 / 100);
                                                    } else {
                                                    }
                                                    ?>
                                                <?php } ?>
                                            </div>
                                            <!-- JS VAT -->
                                            <script>
                                                function vatout() {
                                                    var x = document.getElementById("floatingInputPayment").value;
                                                    window.location.href = "./action.php?vatout&code=%23" + x.substring(1);
                                                }

                                                function vatin() {
                                                    var x = document.getElementById("floatingInputPayment").value;
                                                    window.location.href = "./action.php?vatin&code=%23" + x.substring(1);
                                                }

                                                function cancelvat() {
                                                    var x = document.getElementById("floatingInputPayment").value;
                                                    window.location.href = "./action.php?cancelvat&code=%23" + x.substring(1);
                                                }
                                            </script>
                                            <!-- JS VAT -->
                                        </div>
                                    </div>
                                    <div class="card" style="border: 1px solid #C8C9CC;">
                                        <div class="card-body">
                                            <div class="float-end">
                                                <h3>฿<b id="showGETMoney">0.00</b></h3>
                                            </div>
                                            <h5 style="color: #A4A5A7;">ยอดรับเงิน</h5>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: -20px;margin-bottom: -20px;">
                                        <div class="col" style="margin-right: -18px;">
                                            <div class="card" style="background-color: #448CF8;">
                                                <div class="card-body" style="padding-top: 10px;padding-bottom: 10px;">
                                                    <b style="color: white;">ยอดที่ต้องชำระ</b>
                                                    <br>
                                                    <div class="float-end">
                                                        <h5 style="color: white;">฿<b id="sumprice"><?php echo number_format($finalprice, 2) ?></b></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card" style="background-color: #FF895C;">
                                                <div class="card-body" style="padding-top: 10px;padding-bottom: 10px;">
                                                    <b style="color: white;">เงินทอน</b>
                                                    <br>
                                                    <div class="float-end">
                                                        <h5 style="color: white;">฿<b id="monneyRecov">0.00</b></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ประเภทการจ่ายเงิน -->
                                    <div class="form-group">
                                        <select name="paybymoney" id="paybymoney" class="form-select">
                                            <?php foreach ($resultGETPayType as $keyresultGETPayType => $valueresultGETPayType) { ?>
                                                <option value="<?php echo $valueresultGETPayType['pay_type_name'] ?>"><?php echo $valueresultGETPayType['pay_type_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- numpad -->
                                    <div class="row">
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(7)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>7</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(8)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>8</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(9)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>9</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>


                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(4)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>4</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(5)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>5</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(6)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>6</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>


                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(1)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>1</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(2)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>2</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(3)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>3</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>


                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney('.')">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>.</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="addmoney(0)">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <h3>0</h3>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-4" style="padding: 0px 5px 0px 5px;margin-top: -20px;">
                                            <a type="button" class="btn-numpad" style="width: 100%;" onclick="removeMoney()">
                                                <div class="card" style="border: 1px solid #C8C9CC;">
                                                    <div class="card-body" style="padding-top: 10px;padding-bottom: 5px;">
                                                        <center>
                                                            <span class="iconify" data-icon="feather:delete" data-width="35" data-height="35"></span>
                                                        </center>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                    <!-- /numpad -->

                                    <div class="mt-1">
                                        <button disabled onclick="gopay()" id="btnpayfinal" type="button" class="btn btn-primary btn-payment" style="width: 100%;">ตกลง</button>
                                        <button id="showload" class="btn btn-primary" type="button" style="width: 100%;" disabled hidden>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            กำลังทำรายการ...
                                        </button>
                                    </div>

                                    <!-- /calculate -->
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <!-- status true -->
                        <div class="container mt-5">
                            <center>
                                <h3 style="color: green;"><span class="iconify" data-icon="clarity:success-standard-solid" data-width="30" data-height="30"></span> ชำระเงินเรียบร้อย</h3>
                                <br><br>
                                <?php if ($resultGETOrder['od_status'] == 'false') { ?>
                                    <a href="./action.php?confirmGETProduct&code=<?php echo $codeOrderEncode ?>" onclick="return confirm('ยืนยันการดำเนินการ')" type="button" class="btn btn-outline-success">ยืนยันการรับสินค้า</a>
                                <?php } else { ?>
                                    <h3 style="color: green;"><span class="iconify" data-icon="clarity:success-standard-solid" data-width="30" data-height="30"></span> รับสินค้าเรียบร้อย</h3>
                                <?php } ?>
                            </center>
                        </div>
                        <!-- status true -->
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card card-main">
            <div>
                <a onclick="window.print()" type="button" class="btn hid">
                    <span class="iconify" data-icon="fluent:print-48-filled" data-width="30" data-height="30"></span>
                </a>
            </div>
            <?php if ($resultGETOrder != null) { ?>
                <?php if ($resultGETOrder['payment_status'] == 'false') { ?>
                    <div class="card-body" id="showBillFood" style="color: black;">
                        <center>
                            ไม่มีรายการ
                        </center>
                    </div>
                <?php } else { ?>
                    <div class="card-body" id="showBillFoodPaySuccess" style="color: black;">
                        <center>
                            ไม่มีรายการ
                        </center>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="card-body" id="showBillFoodPaySuccess" style="color: black;">
                    <center>
                        ไม่มีรายการ
                    </center>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    function InsertCodeOrd(value) {
        code_Order = value.substring(1);
        if (value != '') {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("showBillFood").innerHTML = this.responseText;
                    document.getElementById("showBillFoodPaySuccess").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "./ajax/ajax.php?searchBill=%23" + code_Order, true);
            xmlhttp.send();
        }
    }
    $(document).ready(function() {
        var x = document.getElementById("floatingInputPayment").value;
        code_Order = x.substring(1);
        if (x != '') {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("showBillFood").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "./ajax/ajax.php?searchBill=%23" + code_Order, true);
            xmlhttp.send();

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("showBillFoodPaySuccess").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "./ajax/ajax.php?searchBillPaySuccess=%23" + code_Order, true);
            xmlhttp.send();
            var sumprice = document.getElementById('sumprice').innerHTML;
            sessionStorage.setItem("sumprice", sumprice);
        }
    });

    function addmoney(value) {
        var x = document.getElementById('showGETMoney').innerHTML;
        var sumprice = document.getElementById('sumprice').innerHTML;

        if (parseFloat(x) != 0) {
            var y = x + value;
            document.getElementById('showGETMoney').innerHTML = y;
            var monneyREcov = y - parseFloat(sumprice);
            document.getElementById('monneyRecov').innerHTML = monneyREcov.toFixed(2);
            if (monneyREcov < 0) {
                document.getElementById('btnpayfinal').disabled = true;
            } else {
                document.getElementById('btnpayfinal').disabled = false;
            }
        } else {
            document.getElementById('showGETMoney').innerHTML = value;
            var monneyREcov = value - parseFloat(sumprice);
            document.getElementById('monneyRecov').innerHTML = monneyREcov.toFixed(2);
            if (monneyREcov < 0) {
                document.getElementById('btnpayfinal').disabled = true;
            } else {
                document.getElementById('btnpayfinal').disabled = false;
            }
        }
    }

    function removeMoney() {
        document.getElementById('showGETMoney').innerHTML = '0.00';
        document.getElementById('monneyRecov').innerHTML = '0.00';
    }

    function gopay() {
        var paybymoney = document.getElementById('paybymoney').value;
        var code = document.getElementById("floatingInputPayment").value;
        var codeOrder = code.substring(1);

        var monmeyget = document.getElementById("showGETMoney").innerHTML;
        var sumprice = document.getElementById("sumprice").innerHTML;
        var moneyRecov = document.getElementById("monneyRecov").innerHTML;

        document.getElementById('showload').hidden = false;
        document.getElementById('btnpayfinal').hidden = true;

        monmeyget = parseFloat(monmeyget);
        sumprice = parseFloat(sumprice);
        moneyRecov = parseFloat(moneyRecov);

        window.location.href = "./action.php?monmeyget=" + monmeyget +
            "&sumprice=" + sumprice +
            "&moneyRecov=" + moneyRecov +
            "&codeOrder=%23" + codeOrder +
            "&payType=" + paybymoney +
            "&paymentOrder";
    }
</script>
<!-- partial -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.js'></script>
<script src="./script.js"></script>
<?php
include('./LLO.php');
?>
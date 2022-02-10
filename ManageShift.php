<link rel="stylesheet" href="numpad-light.css"/>
<script src="numpad.js"></script>
<?php
ob_start();
require_once("./FLO.php");

// SQL GET Shift
$sqlGETShift = $conn->prepare("SELECT `user_id`,`time_in`,`time_out` FROM `rtr_shift` WHERE `sh_status`='false'");
$sqlGETShift->execute();
$resultGETShift = $sqlGETShift->fetch();

$dateNow = date('Y-m-d');
// SQL GET All Shift
$sqlGETAllShift = $conn->prepare("SELECT      
                                    `rtr_change_in`,
                                    sum(`rtr_shift`.`rtr_change_in`) as tchangs_in,
                                    `rtr_total_amount`,
                                    `rtr_sales`,
                                    `rtr_cash`,
                                    `rtr_credit_card`,
                                    `rtr_transferpayment`,
                                    `rtr_othermoney`,
                                    `rtr_money_out`,
                                    `rtr_othermoney_in`,
                                    `rtr_expenses`,
                                    `rtr_othermoney_out`,
                                    `rtr_withdraw`
                                FROM `rtr_shift` WHERE `datework` = '$dateNow' ORDER BY `time_in` DESC");
$sqlGETAllShift->execute();
$resultGETAllShift = $sqlGETAllShift->fetch();

$sql_rtr_order = $conn->prepare("SELECT 
                                `rtr_order`.`od_id`,
                                `rtr_order`.`sh_number`,
                                `rtr_order`.`od_code`,
                                `rtr_order`.`od_q`,
                                `rtr_order`.`seat_id`,
                                `rtr_order`.`home`,
                                `rtr_order`.`order_discount`,
                                `rtr_order`.`type_discount`,
                                `rtr_order`.`sum_price`,
                                SUM(`rtr_order`.`sum_price`) AS tsum,
                                `rtr_order`.`real_price`,
                                `rtr_order`.`get_price`,
                                `rtr_order`.`chang_price`,
                                `rtr_order`.`payment_status`,
                                `rtr_order`.`payment_type`,
                                `rtr_order`.`od_status`,
                                `rtr_order`.`od_by_add`,
                                `rtr_order`.`od_date_add`,
                                `rtr_order`.`date`,
                                `rtr_order`.`cook_stauts`,
                                `rtr_order`.`stauts_serve`,
                                `rtr_order`.`bell`,
                                `rtr_order`.`name_payer`,
                                `rtr_order`.`phone_payer`,
                                `rtr_order`.`vat`,
                                `rtr_order`.`service_charge`
                            FROM `db_rtr`.`rtr_order` WHERE `rtr_order`.`date`='".$dateNow."'");
$sql_rtr_order->execute();
$resultsql_rtr_order = $sql_rtr_order->fetch();

function date_getFullTimeDifference( $start = null, $end = null ) //คิดเวลาทำงาน
{
    $str = '';
    $uts['start'] = strtotime($start);
    $uts['end'] = strtotime($end);
    if( $uts['start']!==-1 && $uts['end']!==-1 )
    {
        if( $uts['end'] >= $uts['start'] )
        {
            $diff =  $uts['end'] - $uts['start'];
            if( $years=intval((floor($diff/31104000))) )
                $diff = $diff % 31104000;
                $str .= $years > 0 ? $years.' Year ' : '';
            if( $months=intval((floor($diff/2592000))) )
                $diff = $diff % 2592000;
                $str .= $months > 0 ? $months.' months ' : '';
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
                $str .= $days > 0 ? $days.' days ' : '';
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
                $str .= $hours > 0 ? $hours.' hours ' : '';
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            
                // $str .= $minutes > 0 ? $minutes.' minutes ' : '';
                $str .= $minutes > 0 ? $minutes.' นาที ' : '';

            return $str;
        
        }
        else
        {
            echo "Ending date/time is earlier than the start date/time";
        }
    }
    else
    {
        echo "Invalid date/time data detected";
    }
}


//การเรียกใช้งาน
// echo date_getFullTimeDifference('2014-05-07 08:50:00','2019-05-07 22:54:00');
?>

<center>
    <div class="card col-sm-6">
        <div class="card-body">
            <?php if ($resultGETShift == null) { ?>
                <a href="./action.php?ShiftTimeIn" onclick="return confirm('ยืนยันเริ่มการทำงาน')" class="btn btn-primary" style="padding: 10px;">เริ่มการทำงาน</a>
                <hr>
                <b>การทำงานวันนี้ <?php echo DateThaiNotime(date('Y-m-d')); ?></b>
                <br><br>
                <div class="list-group">
                    <?php if ($resultGETAllShift == null) { ?>
                        <a type="button" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                            </div>
                            <p class="mb-1">ยังไม่มีการทำงานในวันนี้</p>
                        </a>
                    <?php } //else { ?>

                </div>
            <?php } else { ?>
                <?php
                $userid = $resultGETShift['user_id'];
                // SQL GET user
                $sqlGETUser = $conn->prepare("SELECT `user_title`,`user_name`,`user_surname` FROM `rtr_user` WHERE `user_id`=$userid");
                $sqlGETUser->execute();
                $resultGETUser = $sqlGETUser->fetch();
                ?>
                <?php if (isset($_GET['PINERROR'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        PIN ตัวเลข 4 หลัก ไม่ถูกต้อง
                    </div>
                <?php } ?>
                <b style="color: green;"><span class="iconify" data-icon="codicon:run-all" data-width="20" data-height="20"></span> กำลังทำงาน</b>
                <br><br>
                <p><?php echo $resultGETUser['user_title'] . $resultGETUser['user_name'] . ' ' . $resultGETUser['user_surname'] ?></p>
                <p><b>เริ่มเวลา : </b><?php echo DateThai($resultGETShift['time_in']) ?></p>
                <br>

                <a href="./kitchenPage.php" class="btn btn-outline-primary"><span class="iconify" data-icon="noto:cooking"></span> ห้องครัว</a>

                <br><br>

                <?php if ($userid == $_COOKIE['rtr_user_id']) { ?>
                    <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#ModalStopShift">สิ้นสุดการทำงาน</a>
                    <!-- Modal -->
                    <div class="modal fade" id="ModalStopShift" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">ยืนยันสิ้นสุดการทำงาน</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    PIN ตัวเลข 4 หลัก<br><br>
                                    <div class="row" style="width: 80%;">
                                        <div class="col">
                                            <input maxlength="1" type="password" name="pin1" id="pin1" class="form-control" readonly>
                                        </div>
                                        <div class="col">
                                            <input maxlength="1" type="password" name="pin2" id="pin2" class="form-control" readonly>
                                        </div>
                                        <div class="col">
                                            <input maxlength="1" type="password" name="pin3" id="pin3" class="form-control" readonly>
                                        </div>
                                        <div class="col">
                                            <input maxlength="1" type="password" name="pin4" id="pin4" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row" style="width: 70%;">
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(7)">7</button>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(8)">8</button>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(9)">9</button>
                                        </div>
                                        <br><br>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(4)">4</button>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(5)">5</button>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(6)">6</button>
                                        </div>
                                        <br><br>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(1)">1</button>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(2)">2</button>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(3)">3</button>
                                        </div>
                                        <br><br>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN('.')">.</button>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn" onclick="insertPIN(0)">0</button>
                                        </div>
                                        <div class="col-4">
                                            <button class="btn" onclick="deletePIN()"><span class="iconify" data-icon="akar-icons:arrow-left"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Modal -->
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</center>
<?php 
    // SQL GET Name
    $sqlGETName = $conn->prepare("SELECT `user_title`,`user_name`,`user_surname` FROM `rtr_user` WHERE `user_id`='".$_COOKIE['rtr_user_id']."'");
    $sqlGETName->execute();
    $resultGETName = $sqlGETName->fetch();
?>
  <!-- Basic Tables start -->
  <section class="section">
        <div class="row" id="basic-table">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">ผู้ปิดกะ: <?php echo $resultGETName['user_title'] . $resultGETName['user_name'] . ' ' . $resultGETName['user_surname'] ?></h6>
                        <h6 class="card-title"><?php echo DateThai(date('Y-m-d H:i:s')); ?></h6>
                    </div>
                    <div class="card-content">
                        <form action="./action.php" method="post" onsubmit="return CKFunction()">
                        <div class="card-body">
                        <div class="form-group">
                            <label for="basicInput">เงินทอนที่นำเข้า</label>
                            <input type="text" class="form-control" id="tchangs_in" value="<?php echo $resultGETAllShift['tchangs_in']?>" 
                            style="text-align: right;"  readonly>
                        </div>
                        <div class="form-group">
                            <label for="basicInput">เงินทั้งหมด</label>
                            <input type="text" class="form-control" id="total_amount" value="<?php echo $resultGETAllShift['rtr_total_amount']+$resultsql_rtr_order['tsum']+$resultGETAllShift['tchangs_in']+$resultGETAllShift['rtr_othermoney']?>" 
                            style="text-align: right;"  readonly>
                        </div>
                        <div class="form-group">
                            <label for="basicInput">ยอดขาย</label>
                            <input type="text" class="form-control" id="sales" value="<?php echo $resultsql_rtr_order['tsum']?>" 
                            style="text-align: right;"  readonly>
                        </div>
                        <?php 
                        $sqlorder = $conn->prepare("SELECT 
                        distinct 
                        `rtr_order`.`payment_type`,
                        `rtr_order`.`sum_price`
                        FROM `db_rtr`.`rtr_order` WHERE `rtr_order`.`date`='".$dateNow."'");
                        $sqlorder->execute();
                        $resultsqlorder = $sqlorder->fetchAll();
                        foreach ($resultsqlorder as $keyresultsqlorder => $valueresultsqlorder) //แยกประเภทเงิน
                        {
                            if($valueresultsqlorder['payment_type'] == "เงินสด")
                            {
                        ?>
                        <div class="form-group">
                            <label for="basicInput">เงินสด</label>
                            <input type="text" class="form-control" id="cash" value="<?php echo $valueresultsqlorder['sum_price']?>" 
                            style="text-align: right;"  readonly>
                        </div>
                        <?php        
                            }
                            elseif($valueresultsqlorder['payment_type'] == "โอนเงิน")
                            {
                        ?>
                        <div class="form-group">
                            <label for="basicInput">เงินโอน</label>
                            <input type="text" class="form-control" id="promtpay" value="<?php echo $valueresultsqlorder['sum_price']?>" 
                            style="text-align: right;"  readonly>
                        </div>
                        <?php
                            }
                            elseif($valueresultsqlorder['payment_type'] == "บัตรเครดิต")
                            {
                        ?>
                        <div class="form-group">
                            <label for="basicInput">บัตรเครดิต</label>
                            <input type="text" class="form-control" id="credit" value="<?php echo $valueresultsqlorder['sum_price']?>" 
                            style="text-align: right;"  readonly>
                        </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="form-group">
                            <label for="basicInput">อื่นๆ</label>
                            <input type="text" class="form-control" id="authermoney" value="<?php echo $resultGETAllShift['rtr_othermoney']?>" 
                            style="text-align: right;"  readonly>
                        </div>
                        <div class="form-group">
                            <label for="basicInput">เงินนำออกจากลิ้นชัก</label>
                            <input type="text" class="form-control" name="getmoneyout" id="getmoneyout"  placeholder="0" value="<?php echo $resultGETAllShift['rtr_withdraw']?>"
                            style="text-align: right;" >
                        </div>
                        <?php 
                            $btn = '';
                            if($resultGETAllShift['rtr_withdraw'] > 1)
                            {
                                $btn = 'disabled';
                            }
                        ?>
                        <div class="form-group" style="text-align: center;">
                            <button class="btn btn-dark" type="submit" name="Shiftclose" id="Shiftclose" <?php echo $btn?>>นำเงินออกจากลิ้นชัก</button>
                        </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Tables end -->
<script>
    function CKFunction() { //เช็คยอดเงินก่อนนำออกจากลิ้นชัก
        var getmoneyout = document.getElementById("getmoneyout").value;
        var total_amount = document.getElementById("total_amount").value;

        if (isNaN(getmoneyout) || getmoneyout < 1 ) {
            alert("กรุณากรอกจำนวนเงินให้ถูกต้อง");
            submitOK = "false";
        }else{
            if(getmoneyout > total_amount)
            {
                alert("กรุณากรอกจำนวนเงินให้ถูกต้อง");
                submitOK = "false";
            }
        }

        if (submitOK == "false") {
            return false;
        }
    }
    window.addEventListener("load", () => {
      // (C1) BASIC NUMPAD
      numpad.attach({target: document.getElementById("getmoneyout")});
    //   numpad.attach({target: document.getElementById("othermoney")});

      // (C2) WITH ALL POSSIBLE OPTIONS
    //   numpad.attach({
    //     target: document.getElementById("demoB"),
    //     max: 10, // MAX 10 DIGITS
    //     decimal: false, // NO DECIMALS ALLOWED
    //     onselect : () => { // CALL THIS AFTER SELECTING NUMBER
    //       alert("DEMO B number set.");
    //     },
    //     oncancel : () => { // CALL THIS AFTER CANCELING
    //       alert("DEMO B canceled.");
    //     }
    //   });
    });
    function insertPIN(value) {
        var pin1 = document.getElementById('pin1').value;
        var pin2 = document.getElementById('pin2').value;
        var pin3 = document.getElementById('pin3').value;
        var pin4 = document.getElementById('pin4').value;
        if (pin1 == '') {
            document.getElementById('pin1').value = value;
        } else if (pin2 == '') {
            document.getElementById('pin2').value = value;
        } else if (pin3 == '') {
            document.getElementById('pin3').value = value;
        } else if (pin4 == '') {
            document.getElementById('pin4').value = value;
            var x = pin1 + pin2 + pin3 + value;
            window.location.href = "./action.php?closeShift&pin=" + x;
        }
    }

    function deletePIN() {
        var pin1 = document.getElementById('pin1').value;
        var pin2 = document.getElementById('pin2').value;
        var pin3 = document.getElementById('pin3').value;
        var pin4 = document.getElementById('pin4').value;
        if (pin4 != '') {
            document.getElementById('pin4').value = '';
        } else if (pin3 != '') {
            document.getElementById('pin3').value = '';
        } else if (pin2 != '') {
            document.getElementById('pin2').value = '';
        } else if (pin1 != '') {
            document.getElementById('pin1').value = '';
        }
    }
</script>
<?php
require_once("./LLO.php");
?>
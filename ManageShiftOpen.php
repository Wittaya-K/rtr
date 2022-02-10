<link rel="stylesheet" href="numpad-light.css"/>
<script src="numpad.js"></script>
<?php
ob_start();
require_once("./FLO.php");

// SQL GET Shift
$sqlGETShift = $conn->prepare("SELECT * FROM `rtr_shift` WHERE `sh_status`='false'");
$sqlGETShift->execute();
$resultGETShift = $sqlGETShift->fetch();

$dateNow = date('Y-m-d');
// SQL GET All Shift
$sqlGETAllShift = $conn->prepare("SELECT * FROM `rtr_shift` WHERE `datework` = '$dateNow' ORDER BY `time_in` DESC");
$sqlGETAllShift->execute();
$resultGETAllShift = $sqlGETAllShift->fetch();

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

<?php 
    // SQL GET Name
    $sqlGETName = $conn->prepare("SELECT `user_title`,`user_name`,`user_surname` FROM `rtr_user` WHERE `user_id`='".$_COOKIE['rtr_user_id']."'");
    $sqlGETName->execute();
    $resultGETName = $sqlGETName->fetch();
?>
  <!-- Basic Tables start -->
  <section class="section">
        <div class="row" id="basic-table">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ผู้เปิดกะ: <?php echo $resultGETName['user_title'] . $resultGETName['user_name'] . ' ' . $resultGETName['user_surname'] ?></h4>
                        <h4 class="card-title">วันที่เปิดกะ: <?php echo DateThai(date('Y-m-d H:i:s')); ?></h4>
                    </div>
                    <div class="card-content">
                    <form action="./action.php" method="post" onsubmit="return CKFunction()">
                        <input type="hidden" name="sh_id" id="sh_id" value="<?php echo $resultGETAllShift['sh_id'] ?>">
                        <div class="card-body">
                        <div class="form-group">
                            <label for="basicInput">นำเงินเข้าลิ้นชักจำนวน</label>
                            <input type="text" class="form-control" name="change_in" id="change_in" placeholder="0.00"
                            style="text-align: right;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div>
                        <div class="form-group">
                            <label for="basicInput">เงินอื่นๆ</label>
                            <input type="text" class="form-control" name="othermoney" id="othermoney" placeholder="0.00" 
                            style="text-align: right;" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                        </div>
                        <div style="text-align-last: center;">
                        <button class="btn btn-primary" type="submit" name="addrChange">
                        <span class="iconify" data-icon="mdi:check-circle" data-width="24" data-height="24"></span> ตกลง </button>
                        <button class="btn btn-danger" type="reset">
                        <span class="iconify" data-icon="mdi:close-circle" data-width="24" data-height="24"></span> ยกเลิก </button>
                        </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <!-- <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">วันที่ปิดกะ: <?php //echo DateThai($resultGETShift['time_in']) ?></h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>
    <!-- Basic Tables end -->

        <!-- (B) INPUT FIELDS -->
    <!-- Field A: <input type="text" id="demoA"/> -->
    <!-- Field B: <textarea id="demoB"></textarea> -->

    <!-- (C) ATTACH NUMPAD -->
<script>
    function CKFunction() {
    var change_in = document.getElementById("change_in").value;
    var othermoney = document.getElementById("othermoney").value;
    submitOK = "true";

    if (isNaN(change_in) || change_in < 1 ) {
        alert("กรุณากรอกจำนวนเงินให้ถูกต้อง");
        submitOK = "false";
    }
    else
    {
        if (isNaN(othermoney) || othermoney < 1 ) {
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
      numpad.attach({target: document.getElementById("change_in")});
      numpad.attach({target: document.getElementById("othermoney")});

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
</script>
<?php
require_once("./LLO.php");
?>
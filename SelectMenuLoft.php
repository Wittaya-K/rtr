<?php
// include ('Include/header.php');
require_once("./FLO.php");
$_SESSION["ResName"];
$_SESSION["ZoneName"];
$_SESSION["User"];
$_SESSION["SelectDate"];
@ini_set('display_errors', '0');

?>
<script language="javascript">
    function fncSubmit(strPage) {
        if (strPage == "page1") {
            document.form1.action = "DeleteAll.php?action=search";
        }
        document.form1.submit();
    }
</script>

<div class="col-lg-4 col-md-5">
<form method="post" id="InsertGuest" name="form1">
    <div class="card card-user">
    <div class="card-content">
        <div class="card-body">
            <?php
            // require_once("connection.php");
            function ListPrice($conn)
            {
                $output = '';
                $sql = $conn->prepare("SELECT * FROM listmenu Where ResName = '$_SESSION[ResName]' GROUP BY SetHid ORDER BY SetHid ASC");
                $sql->execute();
                $rssql = $sql->fetchAll();
                foreach ($rssql as $keyrssql => $valuerssql) {
                    // $sql = "SELECT * FROM listmenu Where ResName = '$_SESSION[ResName]' GROUP BY SetHid ORDER BY SetHid ASC";
                    // $result = mysqli_query($conn, $sql);  
                    // while($row = mysqli_fetch_array($result))  
                    // {  
                    $output .= '<option value="' . $valuerssql["ListPrice"] . $valuerssql["SetType"] . '">ราคา ' . number_format($valuerssql["ListPrice"]) . ' บาท/ท่าน  SET : ' . $valuerssql["SetType"] . ' </option>';
                }

                return $output;
            }
            $sql_q = $conn->prepare("SELECT COUNT(*) AS Q FROM guest");
            $sql_q->execute();
            $rssql_q = $sql_q->fetchAll();
            $x = 1;
            foreach ($rssql_q as $keyrssql_q => $valuerssql_q) {
                while($x <= $valuerssql_q['Q']) {
                    $x++;
                  }
            }
            ?>
            <h5 class="card-title">จำนวนโต๊ะจอง วันที่ <?php echo DateThai($_SESSION["SelectDate"]); ?></h5>
            <h5 class="card-title">คิวที่ <?php echo $x;?></h5>
            <?php
            
            //  require_once("connection.php");
            // $sql2 = "SELECT count( TableID ) AS totalmember FROM bookingres WHERE bookingres.ResName = '$_SESSION[ResName]' AND DataIn = '$_SESSION[SelectDate]' "; 
            // $query2 = mysqli_query($conn,$sql2);
            // $row=mysqli_num_rows($query2); 
            // $rown=mysqli_fetch_array($query2);
            $sql2 = $conn->prepare("SELECT count( TableID ) AS totalmember FROM bookingres WHERE bookingres.ResName = '$_SESSION[ResName]' AND DataIn = '$_SESSION[SelectDate]'");
            $sql2->execute();
            $rssql2 = $sql2->fetch();
            ?>
            <h6 style="text-align: center; font-size: 16px; color: red;">
                จองแล้ว <?php echo $rssql2['totalmember']; ?> โต๊ะ </a></h6><br>
            <hr><br>

            <h5 class="card-title">หมายเลขโต๊ะ</h5>
            <div class="text-center">
                <div class="row">
                    <?php
                    if (!empty($_SESSION["shopping_cart"])) {
                        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                    ?>
                            <div class="tableNumber">
                                <h5><?php echo $new_var = (int)substr($values["item_name"], 3, 3); ?></h5><small>หมายเลขโต๊ะ</small>
                                <h6><a href="<?php echo $_SESSION["ZoneName"]; ?>.php?action=delete&id=<?php echo base64url_encode($values["item_id"]); ?>"><br>ลบ</a></h6><br>
                            </div>

                            <input type="hidden" id="TableID" name="TableID[]" value="<?php echo $values["item_id"]; ?>">
                            <input type="hidden" id="ResName" name="ResName" value="<?php echo $_SESSION["ResName"]; ?>">
                            <input type="hidden" id="RecordBy" name="RecordBy" value="<?php echo $_SESSION["User"]; ?>">

                    <?php }
                    } ?>
                    <?php if ($values["item_id"] == '') {
                    } else { ?>
                        <br><input class="btn btn-danger btn-sm" type="button" value="ลบทั้งหมดที่เลือก" onClick="JavaScript:fncSubmit('page1')">
                    <?php } ?>
                </div>
            </div><br>
            <hr>

                <br>
                <h5 class="title">กรอกข้อมูลลูกค้า</h5><br>
                <div class="container" style="width:100%;">
                    <div class="control-group">
                        <label class="control-label">ชื่อบริษัท</label>
                        <div class="controls">
                            <input type="text" name="GuestCompany" id="GuestCompany" placeholder="กรุณากรอกชื่อบริษัท" class="form-control border-input" />
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input1">ชื่อ - นามสกุล (ผู้จอง)</label>
                        <div class="controls">
                            <input type="text" name="GuestName" id="GuestName" placeholder="กรุณากรอกชื่อ - นามสกุล (ผู้จอง)" class="form-control border-input" />
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input5">อีเมล</label>
                        <div class="controls">
                            <input type="text" name="GuestEmail" id="GuestEmail" placeholder="กรุณากรอก E-mail " class="form-control border-input" />
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input3">เบอร์โทรติดต่อ</label>
                        <div class="controls">
                            <input type="text" name="GuestTel" id="GuestTel" placeholder="กรุณากรอกเบอร์โทรติดต่อ" class="form-control border-input" maxlength="10" />
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input3">เบอร์โทรติดต่อสำรอง</label>
                        <div class="controls">
                            <input type="text" name="GuestTelBackup" id="GuestTelBackup" placeholder="กรุณากรอกเบอร์โทรสำรอง" class="form-control border-input" maxlength="10" />
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input3">Line ID</label>
                        <div class="controls">
                            <input type="text" name="LineID" id="LineID" placeholder="กรุณากรอก Line ID" class="form-control border-input" maxlength="10" />
                        </div>
                    </div>
                    <div class="control-group">
                        <br /><label class="control-label" for="input4">วันเวลาที่เข้าร้าน</label>
                        <div class="controls">
                            <input type="text" class="form-control border-input" value="<?php echo DateThai($_SESSION["SelectDate"]); ?>" readonly style="text-align: center; background-color:#fffcf5; font-weight: 600;">
                            <input type="hidden" name="DataIn" id="DataIn" class="form-control border-input" value="<?php echo $_SESSION["SelectDate"]; ?>">
                            <input type="hidden" name="TimeRange" id="TimeRange" class="form-control border-input" value="<?php echo $_SESSION["SelectTime"]; ?>">

                            <input id="" type="time" name="TimeIn" value="<?php echo date("H:i"); ?>" style="text-align: center; font-weight: 600;" class="form-control border-input">
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input4">จำนวนลูกค้า</label>
                        <div class="controls">
                            <input type="text" name="GuestNum" id="GuestNum" placeholder="กรอกจำนวนลูกค้า" class="form-control border-input" />
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input6">รวมราคาอาหาร</label>
                        <div class="controls">
                            <input type="text" name="TotalPrice" id="TotalPrice" placeholder="รวมราคาอาหาร" class="form-control border-input" />
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input6">จำนวนเงินมัดจำ</label>
                        <div class="controls">
                            <input type="text" name="Pledge" id="Pledge" placeholder="จำนวนเงินมัดจำ" class="form-control border-input" />
                        </div>
                    </div>

                    <div class="control-group">
                        <br /><label class="control-label" for="input6">เลือกประเภทรายการอาหาร</label>
                        <select name="GuestType" id="GuestType" class="toggle-divs form-control border-input form-select">
                            <option value="">ประเภทรายการอาหาร</option>
                            <option value="1">รายการอาหารลูกค้าทั่วไป</option>
                            <option value="2">รายการอาหารลูกค้ากรุ๊ปทัวร์</option>
                            <option value="3">รายการอาหารลูกค้าVIP</option>
                        </select>
                    </div>

                    <div class="control-group">
                        <div class="div1" style="display:none;">
                            <br><label>กรอกรายการอาหารตามสั่ง</label>
                            <textarea rows="4" name="MenuOther1" class="form-control border-input" placeholder="กรอกรายการอาหาร"></textarea>
                        </div>

                        <div class="div2" style="display:none; text-align:  center;">
                            <br><label class="control-label" for="input6" style="float:left;">เลือกสีไอคอนโต๊ะอาหาร</label>

                            <table width="100%" border="0" style="margin-left: -20px;">
                                <tr>
                                    <td>
                                        <div class="radio div2" style="display:none;">
                                            <label><input type="radio" name="SelectColor" value="Blue" />
                                                <img src="images/MantraPlan/TableIcon/CircleBlue.png" width="35px" style="float:left;">
                                            </label>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="radio div2" style="display:none;">
                                            <label><input type="radio" name="SelectColor" value="Yellow" />
                                                <img src="images/MantraPlan/TableIcon/CircleYellow.png" width="35px" style="float:left;">
                                            </label>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="radio div2" style="display:none;">
                                            <label><input type="radio" name="SelectColor" value="Magenta" />
                                                <img src="images/MantraPlan/TableIcon/CircleMagenta.png" width="35px" style="float: left;">
                                            </label>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="radio div2" style="display:none;">
                                            <label><input type="radio" name="SelectColor" value="Red" />
                                                <img src="images/MantraPlan/TableIcon/CircleReserved.png" width="35px" style="float: left;">
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <label class="control-label" for="input6" style="float:left;">เลือกรายการอาหาร</label>
                            <select name="ListPriceSel" id="ListPriceSel" class="form-control border-input action form-select">
                                <option value="">-- เลือกเซทราคาอาหาร --</option>
                                <?php echo ListPrice($conn); ?>
                            </select>
                            <br />

                            <script>
                                $(document).ready(function() {
                                    $('#ListPriceSel').change(function() {
                                        var ListPrice = $(this).val();
                                        var SetType = $(this).val();
                                        $.ajax({
                                            url: "SelectLoadDataLoft.php",
                                            method: "POST",
                                            data: {
                                                ListPrice: ListPrice,
                                                SetType: SetType
                                            },
                                            success: function(data) {
                                                $('#showMenu').html(data);
                                            }
                                        });
                                    });
                                });
                            </script>

                            <script>
                                var elem = document.getElementById("ListPriceSel");
                                elem.onchange = function() {
                                    var hiddenDiv = document.getElementById("showMenu");
                                    hiddenDiv.style.display = (this.value == "") ? "none" : "block";
                                };
                            </script>

                            <div id="showMenu" style='display:none;'>
                            </div>

                            <div class="div2" style="display:none;">
                                <label style="float: left;">กรอกรายการอาหารตามสั่ง</label>
                                <textarea rows="4" name="MenuOther2" class="form-control border-input" placeholder="กรอกรายการอาหาร"></textarea>
                            </div>
                        </div>

                        <div class="div3" style="display:none;">
                            <br /><label>กรอกรายการอาหารตามสั่ง</label>
                            <textarea rows="4" name="MenuOther3" class="form-control border-input" placeholder="กรอกรายการอาหาร"></textarea>
                        </div>

                        <div class="div3" style="display:none; text-align:  center;">

                            <label class="control-label" for="input6" style="float:left;">เลือกรายการอาหาร</label>
                            <select name="ListPriceSelVIP" id="ListPriceSelVIP" class="form-control border-input action form-select">
                                <option value="">-- เลือกเซทราคาอาหาร --</option>
                                <?php echo ListPrice($conn); ?>
                            </select>
                            <br />

                            <div id="showMenuVIP" style='display:none;'>
                            </div>

                            <label class="control-label" for="input6" style="float:left;">เลือกสีไอคอนโต๊ะอาหาร</label>
                            <table width="100%" border="0" style="margin-left: -20px;">
                                <tr>
                                    <td>
                                        <div class="radio div3" style="display:none;">
                                            <label><input type="radio" name="SelectColor" value="BlueVIP" />
                                                <img src="images/MantraPlan/TableIcon/CircleBlueVIP.png" width="35px" style="float:left;">
                                            </label>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="radio div3" style="display:none;">
                                            <label><input type="radio" name="SelectColor" value="YellowVIP" />
                                                <img src="images/MantraPlan/TableIcon/CircleYellowVIP.png" width="35px" style="float:left;">
                                            </label>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="radio div3" style="display:none;">
                                            <label><input type="radio" name="SelectColor" value="MagentaVIP" />
                                                <img src="images/MantraPlan/TableIcon/CircleMagentaVIP.png" width="35px" style="float: left;">
                                            </label>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="radio div3" style="display:none;">
                                            <label><input type="radio" name="SelectColor" value="RedVIP" />
                                                <img src="images/MantraPlan/TableIcon/CircleVIP.png" width="35px" style="float: left;">
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <script>
                                $(document).ready(function() {
                                    $('.toggle-divs').on('change', function() {
                                        var nextAside = $(this).parent('.control-group').next('.control-group');
                                        nextAside.find("div").hide();
                                        nextAside.find(".div" + this.value).show()
                                    });
                                })
                            </script>

                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('#ListPriceSelVIP').change(function() {
                                var ListPrice = $(this).val();
                                var SetType = $(this).val();
                                $.ajax({
                                    url: "SelectLoadDataLoft.php",
                                    method: "POST",
                                    data: {
                                        ListPrice: ListPrice,
                                        SetType: SetType
                                    },
                                    success: function(data) {
                                        $('#showMenuVIP').html(data);
                                    }
                                });
                            });
                        });
                    </script>
                    <script>
                        var elem = document.getElementById("ListPriceSelVIP");
                        elem.onchange = function() {
                            var hiddenDiv = document.getElementById("showMenuVIP");
                            hiddenDiv.style.display = (this.value == "") ? "none" : "block";
                        };
                    </script>

                    <br>
                    <div class="form-actions" style="text-align:  center;">
                        <span id="sendingmail"><input type="hidden" name="insert" id="insert" value="submit">
                            <button type="submit" class="btn btn-success" style="width:49%;">
                                <i class="icon-ok icon-white"></i> บันทึกข้อมูล</span>
                        </button>
                        <button type="reset" class="btn btn-danger" style="width:49%;">
                            <i class="icon-refresh icon-black"></i> ยกเลิก
                        </button>
                    </div>
                </div>
            
        </div>
    </div>
    </div>
    </form>
</div>

<script>
    $("#InsertGuest").submit(function() {
        $("#sendingmail").html("ระบบกำลังทำการส่งข้อมูลโปรดรอสักครู่.....");
        return true;
    });
</script>
<script>
    $(document).ready(function() {
        $('#InsertGuest').on("submit", function(event) {
            event.preventDefault();

            $.ajax({
                url: "insertGuest.php",
                method: "POST",
                data: $('#InsertGuest').serialize(),

                success: function(data) {

                    if (!alertify.showSuccess) {
                        alertify.dialog('showSuccess', function factory() {
                            return {
                                build: function() {
                                    var errorHeader = '<span class="fa fa-check-circle fa-2x" ' +
                                        'style="vertical-align:middle;color:#00CC00;">' +
                                        '</span>&nbsp;&nbsp; เพิ่มข้อมูลสำเร็จ';
                                    this.setHeader(errorHeader);
                                }
                            };
                        }, true, 'alert');
                    }
                    alertify.defaults.theme.ok = "btn btn-primary";
                    $(function() {
                        alertify.showSuccess('<p style="font-size:18px;">เพิ่มข้อมูลการจองลูกค้าสำเร็จ<br/><br/>',
                            function(e) {
                                if (e) {
                                    window.location.reload();
                                } else {
                                    return false;
                                }
                            });
                    });
                }
            });
        });
    });
</script>
<?php session_start(); ?>
<?php

// require_once("connection.php");
// require_once("./FLO.php");
include("./database/conn.php");
include("model/datetime.php");
$_SESSION["SelectDate"];
if (isset($_REQUEST['id'])) {

	$TableID = $_REQUEST['id'];
	$sql = "SELECT *  FROM tablemantraplan INNER JOIN bookingres ON  tablemantraplan.TableID = bookingres.TableID INNER JOIN guest ON guest.GuestID=bookingres.GuestID WHERE bookingres.DataIn = '$_SESSION[SelectDate]' AND bookingres.TimeRange = '$_SESSION[SelectTime]' AND tablemantraplan.TableID ='" . $TableID . "' ORDER BY tablemantraplan.TableID ASC";
	// $query = mysqli_query($conn, $sql) or die(mysqli_error($conn));
	// $valuerssql = mysqli_fetch_array($query,MYSQLI_ASSOC);
	$sql = $conn->prepare("SELECT bookingres.ResName,
	TableNumber,TableNumber,
	GuestCompany,GuestName,
	GuestEmail,GuestTel,
	GuestTelBackup,GuestLine,
	bookingres.DataIn,
	bookingres.TimeIn,bookingres.DateRecode,
	guest.GuestNum,bookingres.MenuName1,
	bookingres.MenuName2,bookingres.MenuName3,
	bookingres.MenuName4,bookingres.MenuName5,
	bookingres.MenuName6,bookingres.MenuName7,
	bookingres.MenuName8,bookingres.MenuName9,
	bookingres.MenuName10,rtr_menu.menu_name
	FROM 
	tablemantraplan
        INNER JOIN
    bookingres ON tablemantraplan.TableID = bookingres.TableID
        INNER JOIN
    rtr_order ON rtr_order.seat_id = bookingres.TableID
        INNER JOIN
    rtr_sub_order ON rtr_sub_order.od_code = rtr_order.od_code
        INNER JOIN
    rtr_menu ON rtr_menu.menu_id = rtr_sub_order.menu_id
        INNER JOIN
    guest ON guest.GuestID = bookingres.GuestID
	WHERE bookingres.DataIn = '$_SESSION[SelectDate]'	
	AND bookingres.TimeRange = '$_SESSION[SelectTime]' 
	AND tablemantraplan.TableID ='$TableID' 
	ORDER BY tablemantraplan.TableID ASC");
	$sql->execute();
	$rssql = $sql->fetchAll();
	foreach ($rssql as $keyrssql => $valuerssql) { ?>

		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<tr>
					<th style="width: 45%;">หมายเลขโต๊ะ</th>
					<th><?php if ($valuerssql["ResName"] == 'Loft') {
							echo $NewVar = (int)substr($valuerssql["TableNumber"], 3, 2);
						} else {
							echo $valuerssql["TableNumber"];
						} ?>
					</th>
				</tr>
				<tr>
					<th>ชื่อบริษัท</th>
					<td><?php echo $valuerssql["GuestCompany"]; ?></td>
				</tr>
				<tr>
					<th>ชื่อ - นามสกุล (ลูกค้า)</th>
					<td><?php echo $valuerssql["GuestName"]; ?></td>
				</tr>
				<tr>
					<th>อีเมล</th>
					<td><?php echo $valuerssql["GuestEmail"]; ?></td>
				</tr>
				<tr>
					<th>เบอร์โทรติดต่อ / เบอร์โทรสำรอง</th>
					<td><?php echo $valuerssql["GuestTel"]; ?> เบอร์โทรสำรอง ( <?php echo $valuerssql["GuestTelBackup"]; ?> )</td>
				</tr>
				<tr>
					<th>Line ID</th>
					<td><?php echo $valuerssql["GuestLine"]; ?></td>
				</tr>
				<tr>
					<th>วัน เวลาที่เข้าร้าน</th>
					<td>วันที่ : <?php echo DateThai($valuerssql["DataIn"]); ?> เวลา : <?php echo $valuerssql["TimeIn"]; ?> น.</td>
				</tr>
				<tr>
					<th>จำนวนลูกค้า</th>
					<td><?php echo $valuerssql["GuestNum"]; ?> ท่าน </td>
				</tr>
				<tr>
					<th style="vertical-align: initial;">รายการอาหาร</th>
					<td> <?php if (!empty($valuerssql["MenuName1"])) { ?>
							รายการ 1. <?php echo $valuerssql["MenuName1"]; ?><br>
							รายการ 2. <?php echo $valuerssql["MenuName2"]; ?><br>
							รายการ 3. <?php echo $valuerssql["MenuName3"]; ?><br>
							รายการ 4. <?php echo $valuerssql["MenuName4"]; ?><br>
							รายการ 5. <?php echo $valuerssql["MenuName5"]; ?><br>
							รายการ 6. <?php echo $valuerssql["MenuName6"]; ?><br>
							<?php if (!empty($valuerssql["MenuName7"])) { ?>
								รายการ 7. <?php echo $valuerssql["MenuName7"]; ?><br><?php } ?>
							<?php if (!empty($valuerssql["MenuName8"])) { ?>
								รายการ 8. <?php echo $valuerssql["MenuName8"]; ?><br><?php } ?>
							<?php if (!empty($valuerssql["MenuName9"])) { ?>
								รายการ 9. <?php echo $valuerssql["MenuName9"]; ?><br><?php } ?>
							</br>เพิ่มเติม <?php echo $valuerssql["MenuName10"]; ?><br><br>
						<?php } ?>
				</tr>
				<tr>
					<th style="vertical-align: initial;">รายการอาหารอื่นๆ</th>
					<td><?php echo $valuerssql["menu_name"]; ?></td>
				</tr>
				<tr>
					<th>จองเมื่อวันที่</th>
					<td><?php echo DateThai($valuerssql["DateRecode"]); ?></td>
				</tr>
			</table>

		</div><?php } ?>
<?php
}

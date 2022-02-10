<?php
require_once("./FLO.php");

// SQL GET Seat All
$sqlGETSeatAll = $conn->prepare("SELECT `seat_id`,`seat_number`,`seat_zone` FROM `rtr_seat`");
$sqlGETSeatAll->execute();
$resultGETSeatAll = $sqlGETSeatAll->fetchAll();

// SQL GET Zone All
$sqlGETZoneAll = $conn->prepare("SELECT `zone_id`,`zone_name`,`zone_by_add`,`zone_date_add` FROM `rtr_zone`");
$sqlGETZoneAll->execute();
$resultGETZoneAll = $sqlGETZoneAll->fetchAll();
?>

<div class="float-end">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#ModalAddSeat">เพิ่มโต๊ะ
    <svg class="svg-inline--fa fa-plus-square fa-w-14 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-32 252c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92H92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"></path></svg>
    </button>
    <!-- Modal -->
    <div class="modal fade" id="ModalAddSeat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./action.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="seat" class="form-label">หมายเลขโต๊ะอาหาร</label>
                            <input type="text" name="seat" id="seat" class="form-control" placeholder="กรอกหมายเลขโต๊ะอาหาร" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"  required>
                        </div>
                        <div class="form-group">
                            <label for="">โซนที่นั่ง</label>
                                <select class="form-select" name="zone" id="zone">
                                    <option value="0">เลือก</option>
                                <?php foreach ($resultGETZoneAll as $keyresultGETZoneAll => $valueresultGETZoneAll) {  ?>
                                        <option value="<?php echo $valueresultGETZoneAll['zone_id'] ?>"><?php echo $valueresultGETZoneAll['zone_name'] ?></option>
                                <?php }?>
                                </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด
                        <svg class="svg-inline--fa fa-window-close fa-w-16 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="window-close" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M464 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-83.6 290.5c4.8 4.8 4.8 12.6 0 17.4l-40.5 40.5c-4.8 4.8-12.6 4.8-17.4 0L256 313.3l-66.5 67.1c-4.8 4.8-12.6 4.8-17.4 0l-40.5-40.5c-4.8-4.8-4.8-12.6 0-17.4l67.1-66.5-67.1-66.5c-4.8-4.8-4.8-12.6 0-17.4l40.5-40.5c4.8-4.8 12.6-4.8 17.4 0l66.5 67.1 66.5-67.1c4.8-4.8 12.6-4.8 17.4 0l40.5 40.5c4.8 4.8 4.8 12.6 0 17.4L313.3 256l67.1 66.5z"></path></svg>
                        </button>
                        <button type="submit" name="addSeat" class="btn btn-success">บันทึก
                        <svg class="svg-inline--fa fa-save fa-w-14 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="save" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM224 416c-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64s64 28.654 64 64c0 35.346-28.654 64-64 64zm96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A11.996 11.996 0 0 1 320 111.48z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script>
		$(document).ready(function(){

        $.ajax({
        url: "selectzone.php", //ทำงานกับไฟล์นี้
        data: "bid=" + $("#brand").val(),  //ส่งตัวแปร
        type: "POST",
        async:false,
        success: function(data, status) {
                $("#model").html(data);
                
            },
            
        error: function(xhr, status, exception) { alert(status); }
            
        });  //

		$("#brand").change(function(){  //
		$.ajax({
		url: "selectzone.php", //ทำงานกับไฟล์นี้
		data: "bid=" + $("#brand").val(),  //ส่งตัวแปร
		type: "POST",
		async:false,
		success: function(data, status) {
				$("#model").html(data);
				
			},
			
		error: function(xhr, status, exception) { alert(status); }
			
		});
			//return flag;
		});
		});
		</script>
<h3>จัดการโซนที่นั่ง</h3>
<div class="col-md-3 mb-3">
    <label for="">โซนที่นั่ง</label>
    <select class="form-select" id='brand' name='bid'>
        <option value="0">เลือก</option>
    <?php foreach ($resultGETZoneAll as $keyresultGETZoneAll => $valueresultGETZoneAll) {  ?>
            <option value="<?php echo $valueresultGETZoneAll['zone_id'] ?>"><?php echo $valueresultGETZoneAll['zone_name'] ?></option>
    <?php }?>
    </select>
</div>
<div id='model' name='mid'></div>

<?php
require_once("./LLO.php");
?>
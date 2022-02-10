<?php
include("./database/conn.php");
$bid =$_REQUEST["bid"];
$sqlGETSeatAll = "";
if($bid == 0)
{
// SQL GET Seat All
$sqlGETSeatAll = $conn->prepare("SELECT `seat_id`,`seat_number`,`seat_status`,`seat_zone` FROM `rtr_seat`");
$sqlGETSeatAll->execute();
$resultGETSeatAll = $sqlGETSeatAll->fetchAll();
}
else
{
// SQL GET Seat All
$sqlGETSeatAll = $conn->prepare("SELECT `seat_id`,`seat_number`,`seat_status`,`seat_zone` FROM `rtr_seat` WHERE  `zone_id`=$bid");
$sqlGETSeatAll->execute();
$resultGETSeatAll = $sqlGETSeatAll->fetchAll();
}

// SQL GET Zone All
$sqlGETZoneAll = $conn->prepare("SELECT `zone_id`,`zone_name`,`zone_by_add`,`zone_date_add` FROM `rtr_zone`");
$sqlGETZoneAll->execute();
$resultGETZoneAll = $sqlGETZoneAll->fetchAll();
?>
<div class="row mt-4">
    <?php foreach ($resultGETSeatAll as $keyresultGETSeatAll => $valueresultGETSeatAll) { ?>
        <div class="col-sm-2">
            <a type="button" data-bs-toggle="modal" data-bs-target="#ModalSeat<?php echo $valueresultGETSeatAll['seat_id'] ?>" style="width: 100%;">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <center>
                                    <span class="iconify" data-icon="vs:table-alt" data-width="50" data-height="50"></span>
                                </center>
                            </div>
                            <div class="col-6">
                                <b>โต๊ะ <?php echo $valueresultGETSeatAll['seat_number'] ?></b>
                                <br>
                                <?php if ($valueresultGETSeatAll['seat_status'] == 'true') { ?>
                                    <span class="badge bg-success">ใช้งาน</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">ไม่ใช้งาน</span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Modal -->
            <div class="modal fade" id="ModalSeat<?php echo $valueresultGETSeatAll['seat_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <?php
                        $seatid = $valueresultGETSeatAll['seat_id'];
                        // SQL GET Data Seat
                        $sqlGETDataSeat = $conn->prepare("SELECT `seat_number`,`seat_status`,`seat_zone` FROM `rtr_seat` WHERE `seat_id`=$seatid");
                        $sqlGETDataSeat->execute();
                        $resultGETDataSeat = $sqlGETDataSeat->fetch();
                        ?>
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">โต๊ะ <?php echo $resultGETDataSeat['seat_number'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="./action.php?id=<?php echo $seatid ?>" method="post">
                            <div class="modal-body">
                            <div class="form-group">
                                <label for="seat_number" class="form-label">หมายเลขโต๊ะอาหาร</label>
                                <input type="text" name="seat_number" id="seat_number" class="form-control" placeholder="กรอกหมายเลขโต๊ะอาหาร" value="<?php echo $resultGETDataSeat['seat_number']?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                            </div>
                                <?php
                                if ($resultGETDataSeat['seat_status'] == 'true') {
                                    $true = 'checked';
                                    $false = '';
                                } else {
                                    $true = '';
                                    $false = 'checked';
                                }
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="true" name="seat_status" id="flexRadioDefault1" <?php echo $true ?>>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        ใช้งาน
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="false" name="seat_status" id="flexRadioDefault2" <?php echo $false ?>>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        ไม่ใช้งาน
                                    </label>
                                </div>
                                <div class="form-group">
                                <label for="">โซนโต๊ะอาหาร</label>
                                <select class="form-select" name="seat_zone" id="seat_zone">
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
                                <button type="submit" name="EditStatusSeat" class="btn btn-success">บันทึก
                                <svg class="svg-inline--fa fa-save fa-w-14 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="save" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM224 416c-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64s64 28.654 64 64c0 35.346-28.654 64-64 64zm96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A11.996 11.996 0 0 1 320 111.48z"></path></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Modal -->
        </div>
    <?php } ?>
</div>
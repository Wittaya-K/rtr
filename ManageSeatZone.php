<?php
include("./FLO.php");

// SQL GET Zone All
$sqlGETZoneAll = $conn->prepare("SELECT `zone_id`,`zone_name`,`zone_by_add`,`zone_date_add` FROM `rtr_zone`");
$sqlGETZoneAll->execute();
$resultGETZoneAll = $sqlGETZoneAll->fetchAll();
?>

<div class="float-end">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#ModalAddGroupMenu">เพิ่ม
    <svg class="svg-inline--fa fa-plus-square fa-w-14 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M400 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-32 252c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92H92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"></path></svg>
    </button>
    <!-- Modal -->
    <div class="modal fade" id="ModalAddGroupMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">โซนที่นั่ง</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./action.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="groupmenu" class="form-label">โซนที่นั่ง</label>
                            <input type="text" name="seat_zone" id="seat_zone" class="form-control" placeholder="กรอกโซนที่นั่ง" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด
                        <svg class="svg-inline--fa fa-window-close fa-w-16 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="window-close" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M464 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-83.6 290.5c4.8 4.8 4.8 12.6 0 17.4l-40.5 40.5c-4.8 4.8-12.6 4.8-17.4 0L256 313.3l-66.5 67.1c-4.8 4.8-12.6 4.8-17.4 0l-40.5-40.5c-4.8-4.8-4.8-12.6 0-17.4l67.1-66.5-67.1-66.5c-4.8-4.8-4.8-12.6 0-17.4l40.5-40.5c4.8-4.8 12.6-4.8 17.4 0l66.5 67.1 66.5-67.1c4.8-4.8 12.6-4.8 17.4 0l40.5 40.5c4.8 4.8 4.8 12.6 0 17.4L313.3 256l67.1 66.5z"></path></svg>
                        </button>
                        <button type="submit" class="btn btn-success" name="addSeatZone">บันทึก
                        <svg class="svg-inline--fa fa-save fa-w-14 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="save" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM224 416c-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64s64 28.654 64 64c0 35.346-28.654 64-64 64zm96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A11.996 11.996 0 0 1 320 111.48z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
</div>
<h3>จัดการโซนที่นั่ง</h3>

<br>

<center>
    <div class="card col-sm-6">
        <div class="card-body">
            <div class="list-group">
                <ul class="list-group">
                    <?php foreach ($resultGETZoneAll as $keyresultGETZoneAll => $valueresultGETZoneAll) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $valueresultGETZoneAll['zone_name'] ?>
                            <span class="badge rounded-pill">
                                <a style="color: #3950A2;" type="button" data-bs-toggle="modal" data-bs-target="#ModalEditGroupMenu<?php echo $valueresultGETZoneAll['zone_id'] ?>">แก้ไข</a>
                                <!-- Modal -->
                                <div class="modal fade" id="ModalEditGroupMenu<?php echo $valueresultGETZoneAll['zone_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="text-align: left;">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">แก้ไข</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="./action.php?id=<?php echo $valueresultGETZoneAll['zone_id'] ?>" method="post">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="seat_zone" class="form-label">โซนที่นั่ง</label>
                                                        <input type="text" value="<?php echo $valueresultGETZoneAll['zone_name'] ?>" name="seat_zone" id="seat_zone" class="form-control" placeholder="กรอกโซนที่นั่ง" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด
                                                    <svg class="svg-inline--fa fa-window-close fa-w-16 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="window-close" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M464 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-83.6 290.5c4.8 4.8 4.8 12.6 0 17.4l-40.5 40.5c-4.8 4.8-12.6 4.8-17.4 0L256 313.3l-66.5 67.1c-4.8 4.8-12.6 4.8-17.4 0l-40.5-40.5c-4.8-4.8-4.8-12.6 0-17.4l67.1-66.5-67.1-66.5c-4.8-4.8-4.8-12.6 0-17.4l40.5-40.5c4.8-4.8 12.6-4.8 17.4 0l66.5 67.1 66.5-67.1c4.8-4.8 12.6-4.8 17.4 0l40.5 40.5c4.8 4.8 4.8 12.6 0 17.4L313.3 256l67.1 66.5z"></path></svg>
                                                    </button>
                                                    <button type="submit" class="btn btn-success" name="editSeatZone">บันทึก
                                                    <svg class="svg-inline--fa fa-save fa-w-14 fa-fw select-all" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="save" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM224 416c-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64s64 28.654 64 64c0 35.346-28.654 64-64 64zm96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A11.996 11.996 0 0 1 320 111.48z"></path></svg>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                            </span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</center>

<?php
include("./LLO.php");
?>
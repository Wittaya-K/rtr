<?php
include("./FLO.php");

// SQL GET Group Menu
$sqlGETGroupMenu = $conn->prepare("SELECT * FROM `rtr_group_menu`");
$sqlGETGroupMenu->execute();
$resultGETGroupMenu = $sqlGETGroupMenu->fetchAll();
?>

<div class="float-end">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#ModalAddGroupMenu">เพิ่ม</button>
    <!-- Modal -->
    <div class="modal fade" id="ModalAddGroupMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./action.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="groupmenu" class="form-label">หมวดอาหาร</label>
                            <input type="text" name="groupmenu" id="groupmenu" class="form-control" placeholder="กรอกชื่อหมวดอาหาร" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary" name="addGroupMenu">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
</div>
<h3>จัดการหมวดหมู่อาหาร</h3>

<br>

<center>
    <div class="card col-sm-6">
        <div class="card-body">
            <div class="list-group">
                <ul class="list-group">
                    <?php foreach ($resultGETGroupMenu as $keyresultGETGroupMenu => $valueresultGETGroupMenu) { ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $valueresultGETGroupMenu['group_name'] ?>
                            <span class="badge rounded-pill">
                                <a style="color: #3950A2;" type="button" data-bs-toggle="modal" data-bs-target="#ModalEditGroupMenu<?php echo $valueresultGETGroupMenu['group_id'] ?>">แก้ไข</a>
                                <!-- Modal -->
                                <div class="modal fade" id="ModalEditGroupMenu<?php echo $valueresultGETGroupMenu['group_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="text-align: left;">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">แก้ไข</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="./action.php?id=<?php echo $valueresultGETGroupMenu['group_id'] ?>" method="post">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="groupmenu" class="form-label">หมวดอาหาร</label>
                                                        <input type="text" value="<?php echo $valueresultGETGroupMenu['group_name'] ?>" name="groupmenu" id="groupmenu" class="form-control" placeholder="กรอกหมวดอาหาร" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                    <button type="submit" class="btn btn-primary" name="EditGroupMenu">บันทึก</button>
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
<?php
include("./FLO.php");

// SQL GET All User
$sqlGETAllUser = $conn->prepare("SELECT * FROM `rtr_user`");
$sqlGETAllUser->execute();
$resultGETAllUser = $sqlGETAllUser->fetchAll();
?>

<div class="float-end">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#ModalAddUser">เพิ่ม</button>
    <!-- Modal -->
    <div class="modal fade" id="ModalAddUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มพนักงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="./action.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="img" class="form-label">รูปภาพ</label>
                            <input type="file" name="img" id="img" class="form-control" accept="image/*" required>
                        </div>

                        <div class="form-group">
                            <label for="title" class="form-label">คำนำหน้า</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="คำนำหน้าชื่อ" required>
                        </div>

                        <div class="form-group">
                            <label for="name" class="form-label">ชื่อจริง</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="กรอกชื่อจริง" required>
                        </div>

                        <div class="form-group">
                            <label for="surname" class="form-label">นามสกุล</label>
                            <input type="text" name="surname" id="surname" class="form-control" placeholder="กรอกนามสกุล" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" maxlength="10" pattern="[0-9]{10}" name="phone" id="phone" class="form-control" placeholder="กรอกเบอร์โทรศัพท์" required>
                        </div>

                        <div class="form-group">
                            <label for="pin" class="form-label">PIN ตัวเลข 4 หลัก</label>
                            <input type="tel" maxlength="4" pattern="[0-9]{4}" name="pin" id="pin" class="form-control" placeholder="กรอกตัวเลข 4 หลัก" required>
                        </div>

                        <div class="form-group">
                            <label for="position" class="form-label">ตำแหน่ง</label>
                            <select class="form-control" name="position" id="position" required>
                                <option value="">-- เลือกตำแหน่ง --</option>
                                <option value="admin">admin</option>
                                <option value="พนักงาน">พนักงาน</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary" name="addUser">บันทึก</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- /Modal -->
</div>
<h3>พนักงาน</h3>
<br>
<center>
    <div class="card col-sm-6">
        <div class="card-body">
            <div class="list-group" style="text-align: left;">

                <?php foreach ($resultGETAllUser as $keyresultGETAllUser => $valueresultGETAllUser) {
                    if ($valueresultGETAllUser['user_name'] != 'Myhost') { ?>
                        <a href="./ManageUserOne.php?id=<?php echo $valueresultGETAllUser['user_id'] ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo $valueresultGETAllUser['user_title'] . $valueresultGETAllUser['user_name'] . ' ' . $valueresultGETAllUser['user_surname'] ?></h5>
                                <small class="text-muted">รายละเเอียด</small>
                            </div>
                            <p class="mb-1"><?php echo $valueresultGETAllUser['user_phone'] ?></p>
                            <small class="text-muted"><?php echo $valueresultGETAllUser['user_position'] ?></small>
                        </a>
                <?php }
                } ?>

            </div>
        </div>
    </div>
</center>

<?php
include("./LLO.php");
?>
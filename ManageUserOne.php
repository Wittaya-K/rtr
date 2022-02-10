<?php
include("./FLO.php");

$id = $_GET['id'];

// SQL GET All User
$sqlGETAllUser = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_id`=$id");
$sqlGETAllUser->execute();
$resultGETAllUser = $sqlGETAllUser->fetch();
?>

<style>
    img {
        -webkit-border-radius: 21px;
        border-radius: 21px;
    }
</style>

<div class="flaot-start">
    <a href="./ManageEmplayee.php"><span class="iconify" data-icon="akar-icons:arrow-back"></span> ย้อนกลับ</a>
</div>
<br><br>
<h3><?php echo $resultGETAllUser['user_title'] . $resultGETAllUser['user_name'] . ' ' . $resultGETAllUser['user_surname'] ?></h3>
<br>
<center>
    <div class="card col-sm-6">
        <div class="card-body">
            <div class="list-group" style="text-align: left;">

                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditPic">แก้ไข</a>
                    </div>
                    <b>รูปภาพ</b>
                    <center>
                        <p>
                            <img style="height: 90px;width: 130px;;object-fit: cover;border: 1px solid #EBEBEB" src="./assets/assets/user/<?php echo $resultGETAllUser['user_pic'] ?>" alt="">
                        </p>
                    </center>
                </div>
                <hr>
                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditFullName">แก้ไข</a>
                    </div>
                    <b>ชื่อ-นามสกุล</b>
                    <center>
                        <p>
                            <?php echo $resultGETAllUser['user_title'] . $resultGETAllUser['user_name'] . ' ' . $resultGETAllUser['user_surname'] ?>
                        </p>
                    </center>
                </div>
                <hr>
                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditPhoneNumber">แก้ไข</a>
                    </div>
                    <b>เบอร์โทรศัพท์</b>
                    <center>
                        <p>
                            <?php echo $resultGETAllUser['user_phone'] ?>
                        </p>
                    </center>
                </div>
                <hr>
                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditPosition">แก้ไข</a>
                    </div>
                    <b>ตำแหน่ง</b>
                    <center>
                        <p>
                            <?php echo $resultGETAllUser['user_position'] ?>
                        </p>
                    </center>
                </div>
                <hr>
                <div class="form-group">
                    <?php
                    $addby = $resultGETAllUser['user_by_add'];
                    // SQL GET ADD BY
                    $sqlGETAddBy = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_id`=$addby");
                    $sqlGETAddBy->execute();
                    $resultGETAddBy = $sqlGETAddBy->fetch();
                    ?>
                    <b>เพิ่มโดย</b> <?php echo $resultGETAddBy['user_title'] . $resultGETAddBy['user_name'] . ' ' . $resultGETAddBy['user_surname'] ?>
                    <br>
                    <b>วันที่เพิ่ม</b> <?php echo DateThai($resultGETAddBy['user_date_add']) ?>
                    <br>
                    <b>สถานะ</b> <?php if ($resultGETAddBy['user_status'] == 'true') {
                                        echo 'เป็นพนักงาน';
                                    } else {
                                        echo 'ไม่เป็นพนักงาน';
                                    } ?>
                </div>

            </div>
        </div>
    </div>
</center>

<!-- ModalEditPic -->
<div class="modal fade" id="ModalEditPic" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">รูปภาพ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $resultGETAllUser['user_id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="img" class="form-label"></label>
                        <input type="file" name="img" id="img" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="editPicUser">ตกลง</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditPic -->
<!-- ModalEditFullName -->
<div class="modal fade" id="ModalEditFullName" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ชื่อ-นามสกุล</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $resultGETAllUser['user_id'] ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title" class="form-label">คำนำหน้า</label>
                        <input value="<?php echo $resultGETAllUser['user_title'] ?>" class="form-control" type="text" name="title" id="title" class="form-comtrol" placeholder="กรอกคำนำหน้า" required>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">ชื่อ</label>
                        <input value="<?php echo $resultGETAllUser['user_name'] ?>" class="form-control" type="text" name="name" id="name" class="form-comtrol" placeholder="กรอกชื่อ" required>
                    </div>
                    <div class="form-group">
                        <label for="surname" class="form-label">นามสกุล</label>
                        <input value="<?php echo $resultGETAllUser['user_surname'] ?>" class="form-control" type="text" name="surname" id="surname" class="form-comtrol" placeholder="กรอกนามสกุล" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="editFullNameUser">ตกลง</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditFullName -->
<!-- ModalEditPhoneNumber -->
<div class="modal fade" id="ModalEditPhoneNumber" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">เบอร์โทรศัพท์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $resultGETAllUser['user_id'] ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="tel" maxlength="10" pattern="[0-9]{10}" value="<?php echo $resultGETAllUser['user_phone'] ?>" class="form-control" name="phone" id="phone" placeholder="กรอกเบอร์โทรศัพท์" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="editPhoneNumberUser">ตกลง</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditPhoneNumber -->
<!-- ModalEditPosition -->
<div class="modal fade" id="ModalEditPosition" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ตำแหน่ง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $resultGETAllUser['user_id'] ?>" method="post">
                <div class="modal-body">
                    <?php
                        if ($resultGETAllUser['user_position'] == 'admin') {
                            $admin = 'checked';
                            $user = '';
                        }else {
                            $admin = '';
                            $user = 'checked';
                        }
                    ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="admin" name="position" id="flexRadioDefault1" <?php echo $admin ?>>
                        <label class="form-check-label" for="flexRadioDefault1">
                            admin
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="พนักงาน" name="position" id="flexRadioDefault2" <?php echo $user ?>>
                        <label class="form-check-label" for="flexRadioDefault2">
                            พนักงาน
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="editPositionUser">ตกลง</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditPosition -->


<script>
    function goBack() {
        window.history.back();
    }
</script>
<?php
include("./LLO.php");
?>
<?php
include("./FLO.php");

// SQL GET Notify
$sqlGETNotify = $conn->prepare("SELECT * FROM `rtr_token_notify`");
$sqlGETNotify->execute();
$resultGETNotify = $sqlGETNotify->fetch();

// SQL GET RTR Name
$sqlGETRTRName = $conn->prepare("SELECT * FROM `rtr_name`");
$sqlGETRTRName->execute();
$resultGETRTRName = $sqlGETRTRName->fetch();

// SQL GET servicech
$sqlGETServicech = $conn->prepare("SELECT * FROM `rtr_servicech`");
$sqlGETServicech->execute();
$resultGETServicech = $sqlGETServicech->fetch();

// SQL GET Img Logo
$sqlGETImgLogo = $conn->prepare("SELECT * FROM `rtr_img_logo` WHERE `logo_status`='true'");
$sqlGETImgLogo->execute();
$resultGETImgLogo = $sqlGETImgLogo->fetch();

// SQL GET Img ALL Logo
$sqlGETAllImgLogo = $conn->prepare("SELECT * FROM `rtr_img_logo`");
$sqlGETAllImgLogo->execute();
$resultGETImgAllLogo = $sqlGETAllImgLogo->fetchAll();

$status_true = '';
$status_false = '';
$status_true_servicecharge = '';
$status_false_servicecharge = '';
?>
<style>
    .limit-txt {
        white-space: nowrap;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        -ms-text-overflow: ellipsis;
        overflow: hidden;
        width: 150px;
    }
</style>
<center>
    <div class="card col-sm-6">
        <div class="card-body">
            <div class="list-group" style="text-align: left;">

                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditLogo">แก้ไข</a>
                    </div>
                    <b>โลโก้</b>
                    <center>
                        <p>
                            <?php if ($resultGETImgLogo != null) { ?>
                                <img style="width: 50%;" src="./assets/assets/images/logo/<?php echo $resultGETImgLogo['logo_name'] ?>" alt="">
                            <?php } else { ?>
                                ว่าง
                            <?php } ?>
                        </p>
                    </center>
                </div>
                <hr>
                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditname">แก้ไข</a>
                    </div>
                    <b>ชื่อร้าน</b>
                    <center>
                        <p>
                            <?php if ($resultGETRTRName['name'] != null || $resultGETRTRName['name'] != '') { ?>
                                <?php echo $resultGETRTRName['name'] ?>
                            <?php } else { ?>
                                ว่าง
                            <?php } ?>
                        </p>
                    </center>
                </div>
                <hr>
                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditStatus">แก้ไข</a>
                    </div>
                    <b>การแจ้งเตือน</b>
                    <center>
                        <p>
                            <?php if ($resultGETNotify['status'] == 'true') { ?>
                                กำลังใช้งาน
                                <?php
                                $status_true = 'checked';
                                $status_false = '';
                                ?>
                            <?php } else { ?>
                                ปิดการใช้งาน
                                <?php
                                $status_true = '';
                                $status_false = 'checked';
                                ?>
                            <?php } ?>
                        </p>
                    </center>
                </div>
                <hr>
                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditToken">แก้ไข</a>
                    </div>
                    <b>Token</b>
                    <center>
                        <p class="limit-txt">
                            <?php if ($resultGETNotify['token'] != null || $resultGETNotify['token'] != '') { ?>
                                <?php echo $resultGETNotify['token'] ?>
                            <?php } else { ?>
                                ว่าง
                            <?php } ?>
                        </p>
                    </center>
                </div>
                <hr>
                <div class="form-group">
                    <div class="float-end">
                        <a type="button" data-bs-toggle="modal" data-bs-target="#ModalServiceCh">แก้ไข</a>
                    </div>
                    <b>service charge</b>
                    <center>
                        <p class="limit-txt">
                            <?php if ($resultGETServicech['servicech'] != null) { ?>
                                <?php
                                echo $resultGETServicech['servicech'] . '%/';
                                if ($resultGETServicech['servicech_status'] == 'true') {
                                    echo 'กำลังใช้งาน';
                                    $status_true_servicecharge = 'checked';
                                } else {
                                    echo 'ปิดการใช้งาน';
                                    $status_false_servicecharge = 'checked';
                                }
                                ?>

                            <?php } else {
                                echo 'ไม่ตั้งค่า' . '/';
                                if ($resultGETServicech['servicech_status'] == 'true') {
                                    echo 'กำลังใช้งาน';
                                    $status_true_servicecharge = 'checked';
                                } else {
                                    echo 'ปิดการใช้งาน';
                                    $status_false_servicecharge = 'checked';
                                }
                            } ?>
                        </p>
                    </center>
                </div>

            </div>
        </div>
    </div>
</center>
<style>
    .card-in-file:hover {
        background-color: #EEEEEE;
    }
</style>
<!-- ModalEditLogo -->
<div class="modal fade" id="ModalEditLogo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">แก้ไขโลโก้</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-3">
                    <a style="width: 100%;" id="btnshowaddlogo" onclick="showinputimglogo()" type="button">
                        <div class="card card-in-file" style="border: 1px solid #818181;">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <center>
                                            <span class="iconify" data-icon="fluent:add-12-filled" data-width="40" data-height="40"></span>
                                        </center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div id="showaddlogo" hidden>
                    <form action="./action.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="file" name="file" id="file" class="form-control" required>
                            <br>
                            <button class="btn btn-success" type="submit" name="addlogo">บันทึก</button>
                        </div>
                    </form>
                </div>
                <hr>

                <?php foreach ($resultGETImgAllLogo as $keyresultGETImgAllLogo => $valueresultGETImgAllLogo) { ?>
                    <?php if ($valueresultGETImgAllLogo['logo_status'] == 'true') { ?>
                        <div class="card" style="border: 1px solid green;">
                            <div style="position: absolute;right: 1px;color: green;">
                                <span class="iconify" data-icon="clarity:success-standard-solid" data-width="30" data-height="30"></span>
                            </div>
                            <div class="card-body">
                                <img style="width: 50%;" src="./assets/assets/images/logo/<?php echo $valueresultGETImgAllLogo['logo_name'] ?>" alt="">
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php foreach ($resultGETImgAllLogo as $keyresultGETImgAllLogo => $valueresultGETImgAllLogo) { ?>
                    <?php if ($valueresultGETImgAllLogo['logo_status'] == 'false') { ?>
                        <a href="./action.php?editlogo&id=<?php echo $valueresultGETImgAllLogo['logo_id'] ?>" style="width: 100%;">
                            <div class="card" style="border: 1px solid #676767;">
                                <div style="position: absolute;right: 1px;color: #676767;">
                                    <span class="iconify" data-icon="akar-icons:circle" data-width="30" data-height="30"></span>
                                </div>
                                <div class="card-body">
                                    <img style="width: 50%;" src="./assets/assets/images/logo/<?php echo $valueresultGETImgAllLogo['logo_name'] ?>" alt="">
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- ModalEditLogo -->
<script>
    function showinputimglogo() {
        document.getElementById("showaddlogo").hidden = false;
        document.getElementById("btnshowaddlogo").hidden = true;
    }
</script>
<!-- ModalEditname -->
<div class="modal fade" id="ModalEditname" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">แก้ไขชื่อร้าน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="name" id="" class="form-control" placeholder="ชื่อร้าน" value="<?php echo $resultGETRTRName['name'] ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" name="EditRTRname" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ModalEditname -->
<!-- ModalEditStatus -->
<div class="modal fade" id="ModalEditStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">การแจ้งเตือน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php" method="post">
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rtrstatusnotify" id="flexRadioDefault1" value="true" <?php echo $status_true ?>>
                        <label class="form-check-label" for="flexRadioDefault1" style="color: green;">
                            เปิดการใช้งาน <span class="iconify" data-icon="clarity:success-standard-solid" data-width="20" data-height="20"></span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rtrstatusnotify" id="flexRadioDefault2" value="false" <?php echo $status_false ?>>
                        <label class="form-check-label" for="flexRadioDefault2" style="color: red;">
                            ปิดการใช้งาน <span class="iconify" data-icon="akar-icons:circle-x-fill" data-width="20" data-height="20"></span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" name="EditRTRStatusNotify" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ModalEditStatus -->
<!-- ModalEditToken -->
<div class="modal fade" id="ModalEditToken" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">แก้ไข Token</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="token" id="" class="form-control" placeholder="Token" value="<?php echo $resultGETNotify['token'] ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" onclick="return confirm('ยืนยันการแก้ไข Token')" name="EditRTRToken" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ModalEditToken -->
<!-- ModalServiceCh -->
<div class="modal fade" id="ModalServiceCh" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">service charge</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="number" min="1" max="100" name="servicech" id="" class="form-control" placeholder="service charge(%)" value="<?php echo $resultGETServicech['servicech'] ?>">
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="servicech_status" id="servicech_status1" value="true" <?php echo $status_true_servicecharge ?>>
                            <label class="form-check-label" for="servicech_status1" style="color: green;">
                                เปิดการใช้งาน <span class="iconify" data-icon="clarity:success-standard-solid" data-width="20" data-height="20"></span>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="servicech_status" id="servicech_status2" value="false" <?php echo $status_false_servicecharge ?>>
                            <label class="form-check-label" for="servicech_status2" style="color: red;">
                                ปิดการใช้งาน <span class="iconify" data-icon="akar-icons:circle-x-fill" data-width="20" data-height="20"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" onclick="return confirm('ยืนยันการแก้ไข')" name="EditServicecharge" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ModalServiceCh -->

<?php
include("./LLO.php");
?>
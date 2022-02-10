<?php
require_once("./FLO.php");

$id = $_GET['idcode'];

// SQL GET Menu
$sqlGETMenu = $conn->prepare("SELECT * FROM `rtr_menu` WHERE `menu_id`=$id");
$sqlGETMenu->execute();
$resultGETMenu = $sqlGETMenu->fetch();

// SQL GET SubMenu
$sqlGETSubMenu = $conn->prepare("SELECT * FROM `rtr_sub_menu` WHERE `menu_id`=$id");
$sqlGETSubMenu->execute();
$resultGETSubMenu = $sqlGETSubMenu->fetchAll();
?>

<style>
    .img_food {
        -webkit-border-radius: 20px;
        border-radius: 20px;
    }
</style>

<a href="./ManageMenu.php"><span class="iconify" data-icon="eva:arrow-back-outline"></span> จัดการเมนู</a>

<center>
    <div class="row">

        <div class="mt-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="list-group" style="text-align: left;">

                        <div class="form-group">
                            <div class="float-end">
                                <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditPic">แก้ไข</a>
                            </div>
                            <b>ภาพอาหาร</b>
                            <center>
                                <p>
                                    <?php if ($resultGETMenu['menu_img'] != null) { ?>
                                        <img class="img_food" style="height: 90px;width: 130px;object-fit: cover;border: 1px solid #EBEBEB" src="./assets/assets/images/food/<?php echo $resultGETMenu['menu_img'] ?>" alt="">
                                    <?php } else { ?>
                                        <img class="img_food" style="height: 90px;width: 130px;object-fit: cover;border: 1px solid #EBEBEB" src="./assets/assets/images/food/nopic.png" alt="">
                                    <?php } ?>
                                </p>
                            </center>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="float-end">
                                <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditName">แก้ไข</a>
                            </div>
                            <b>ชื่อเมนู</b>
                            <center>
                                <p>
                                    <?php echo $resultGETMenu['menu_name'] ?>
                                </p>
                            </center>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="float-end">
                                <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditPrice">แก้ไข</a>
                            </div>
                            <b>ราคา</b>
                            <center>
                                <p>
                                    <?php echo $resultGETMenu['menu_price'] ?> บาท
                                </p>
                            </center>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="float-end">
                                <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditGroup">แก้ไข</a>
                            </div>
                            <b>หมวดหมู่</b>
                            <center>
                                <p>
                                    <?php
                                    $groupID = $resultGETMenu['menu_group'];
                                    // SQL GET Group Name
                                    $sqlGETGroupName = $conn->prepare("SELECT `group_name` FROM `rtr_group_menu` WHERE `group_id`=$groupID");
                                    $sqlGETGroupName->execute();
                                    $resultGETGroupName = $sqlGETGroupName->fetch();
                                    ?>
                                    <?php echo $resultGETGroupName['group_name'] ?>
                                </p>
                            </center>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="float-end">
                                <a type="button" data-bs-toggle="modal" data-bs-target="#ModalEditStatus">แก้ไข</a>
                            </div>
                            <b>สถานะ</b>
                            <center>
                                <p>
                                    <?php if ($resultGETMenu['menu_status'] == 'true') { ?>
                                        <span class="badge bg-success">ใช้งาน</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">ไม่ใช้งาน</span>
                                    <?php } ?>
                                </p>
                            </center>
                        </div>
                        <hr>
                        <div class="form-group">
                            <?php
                            $addby = $resultGETMenu['menu_by_add'];
                            // SQL GET ADD BY
                            $sqlGETAddBy = $conn->prepare("SELECT * FROM `rtr_user` WHERE `user_id`=$addby");
                            $sqlGETAddBy->execute();
                            $resultGETAddBy = $sqlGETAddBy->fetch();
                            ?>
                            <b>เพิ่มโดย</b> <?php echo $resultGETAddBy['user_title'] . $resultGETAddBy['user_name'] . ' ' . $resultGETAddBy['user_surname'] ?>
                            <br>
                            <b>วันที่เพิ่ม</b> <?php echo DateThai($resultGETMenu['menu_date_add']) ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <?php if ($resultGETSubMenu == null) { ?>
                        <b>ไม่มีเครื่องเคียง</b>
                    <?php } else { ?>
                        <ul class="list-group">
                            <?php foreach ($resultGETSubMenu as $keyresultGETSubMenu => $valueresultGETSubMenu) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo $valueresultGETSubMenu['sm_name'] ?>
                                    <span class="badge rounded-pill" style="color: #364B98;">+ <?php echo $valueresultGETSubMenu['sm_price'] ?> บาท
                                        <a onclick="return confirm('ยืนยันการลบ : <?php echo $valueresultGETSubMenu['sm_name'] ?>')" style="margin-left: 10px;" href="./action.php?RemoveSubMenu=<?php echo $valueresultGETSubMenu['sm_id'] ?>&id=<?php echo $id ?>" class="badge bg-danger">ลบ</a>
                                    </span>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                    <hr>
                    <a type="button" onclick="showtagInput()" id="btnadd">
                        <span class="iconify" data-icon="icon-park:add-three" data-width="40" data-height="40"></span>
                    </a>

                    <div class="form-group mt-3" id="inputSubmenu" hidden>
                        <form action="./action.php?id=<?php echo $id ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="subMenu" id="floatingInput" placeholder="เครื่องเคียง" required>
                                <label for="floatingInput">เครื่องเคียง</label>
                            </div>
                            <div class="form-floating">
                                <input type="number" class="form-control" name="priceSubMenu" id="floatingPassword" placeholder="ราคาเครื่องเคียง(บาท)" required>
                                <label for="floatingPassword">ราคาเครื่องเคียง(บาท)</label>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-block" style="background-color: #EC5F47;color: white;" name="addSubMenu">เพิ่ม</button>
                            </div>
                        </form>
                    </div>

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
                <h5 class="modal-title" id="exampleModalLabel">ภาพอาหาร</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $id ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_img" class="form-label"></label>
                        <input type="file" name="menu_img" id="menu_img" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="ModalEditMenuPic">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditPic -->
<!-- ModalEditName -->
<div class="modal fade" id="ModalEditName" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ชื่อเมนู</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $id ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_name" class="form-label"></label>
                        <input value="<?php echo $resultGETMenu['menu_name'] ?>" class="form-control" type="text" name="menu_name" id="menu_name" class="form-comtrol" placeholder="กรอกชื่อเมนู" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="ModalEditMenuName">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditName -->
<!-- ModalEditPrice -->
<div class="modal fade" id="ModalEditPrice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ราคา</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $id ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_price" class="form-label"></label>
                        <input value="<?php echo $resultGETMenu['menu_price'] ?>" class="form-control" type="number" name="menu_price" id="menu_price" class="form-comtrol" placeholder="กรอกชื่อเมนู" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="ModalEditMenuPrice">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditPrice -->
<!-- ModalEditGroup -->
<div class="modal fade" id="ModalEditGroup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">หมวดหมู่อาหาร</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $id ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php
                    $groupID = $resultGETMenu['menu_group'];
                    // SQL GET Group Name All
                    $sqlGETGroupNameAll = $conn->prepare("SELECT * FROM `rtr_group_menu`");
                    $sqlGETGroupNameAll->execute();
                    $resultGETGroupNameAll = $sqlGETGroupNameAll->fetchAll();
                    ?>
                    <div class="form-group">
                        <label for="menu_group" class="form-label"></label>
                        <select name="menu_group" id="menu_group" class="form-control" required>
                            <?php foreach ($resultGETGroupNameAll as $keyresultGETGroupNameAll => $valueresultGETGroupNameAll) { ?>
                                <?php if ($valueresultGETGroupNameAll['group_id'] == $groupID) { ?>
                                    <option value="<?php echo $valueresultGETGroupNameAll['group_id'] ?>"><?php echo $valueresultGETGroupNameAll['group_name'] ?></option>
                                <?php } ?>
                            <?php } ?>
                            <?php foreach ($resultGETGroupNameAll as $keyresultGETGroupNameAll => $valueresultGETGroupNameAll) { ?>
                                <?php if ($valueresultGETGroupNameAll['group_id'] != $groupID) { ?>
                                    <option value="<?php echo $valueresultGETGroupNameAll['group_id'] ?>"><?php echo $valueresultGETGroupNameAll['group_name'] ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="ModalEditMenuGroup">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditGroup -->
<!-- ModalEditStatus -->
<div class="modal fade" id="ModalEditStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">สถานะ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="./action.php?id=<?php echo $id ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php
                    if ($resultGETMenu['menu_status'] == 'true') {
                        $true = 'checked';
                        $false = '';
                    } else {
                        $true = '';
                        $false = 'checked';
                    }
                    ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="true" name="menu_status" id="flexRadioDefault1" <?php echo $true ?>>
                        <label class="form-check-label" for="flexRadioDefault1">
                            ใช้งาน
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="false" name="menu_status" id="flexRadioDefault2" <?php echo $false ?>>
                        <label class="form-check-label" for="flexRadioDefault2">
                            ไม่ใช้งาน
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary" name="ModalEditMenuStatus">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /ModalEditStatus -->

<!-- JS -->
<script>
    function showtagInput() {
        var x = document.getElementById('inputSubmenu').hidden;

        if (x == false) {
            document.getElementById('inputSubmenu').hidden = true;
        } else {
            document.getElementById('inputSubmenu').hidden = false;
        }
    }
</script>
<!-- /JS -->
<?php
require_once("./LLO.php");
?>
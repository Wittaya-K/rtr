<?php
require_once("./FLO.php");

if (isset($_GET['idgroup'])) {
    $idgroup = $_GET['idgroup'];
    // SQL GET Name Group
    $sqlGETNameGroup = $conn->prepare("SELECT `group_name` FROM `rtr_group_menu` WHERE `group_id`=$idgroup");
    $sqlGETNameGroup->execute();
    $resultGETNameGroup = $sqlGETNameGroup->fetch();
    $namegroup = $resultGETNameGroup['group_name'];

    // SQL GET Menu
    $sqlGETMenu = $conn->prepare("SELECT * FROM `rtr_menu` WHERE `menu_group`=$idgroup");
    $sqlGETMenu->execute();
    $resultGETMenu = $sqlGETMenu->fetchAll();
} else {
    $idgroup = null;
    $namegroup = 'ทั้งหมด';

    // SQL GET Menu
    $sqlGETMenu = $conn->prepare("SELECT * FROM `rtr_menu`");
    $sqlGETMenu->execute();
    $resultGETMenu = $sqlGETMenu->fetchAll();
}

// SQL GET Group Menu
$sqlGETGroupMenu = $conn->prepare("SELECT `group_id`, `group_name`, `group_by_add`, `group_date_add` FROM `rtr_group_menu`");
$sqlGETGroupMenu->execute();
$resultGETGroupMenu = $sqlGETGroupMenu->fetchAll();

// SQL Check Count
$sqlCheckCount = $conn->prepare("SELECT COUNT(*) FROM `rtr_menu`");
$sqlCheckCount->execute();
$resultCheckCount = $sqlCheckCount->fetch();

?>

<style>
    .card-main {
        background-color: #F2F7FF;
    }

    .card-menu-main:hover {
        -webkit-box-shadow: 5px 5px 15px 5px rgba(236, 102, 73, 0.11);
        box-shadow: 5px 5px 15px 5px rgba(236, 102, 73, 0.11);

        -moz-transform: rotate(2deg);
        -webkit-transform: rotate(2deg);
        -o-transform: rotate(2deg);
        -ms-transform: rotate(2deg);
        transform: rotate(2deg);
    }
</style>

<div class="float-end">
    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#ModalSelectGroupMenu">เลือกหมวดหมู่</button>
    <!-- Modal -->
    <div class="modal fade" id="ModalSelectGroupMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">หมวดหมู่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <a href="./ManageMenu.php" class="list-group-item list-group-item-action <?php if ($idgroup == null) { ?>active<?php } ?>">ทั้งหมด(<?php echo $resultCheckCount[0] ?>)</a>
                        <?php foreach ($resultGETGroupMenu as $keyresultGETGroupMenu => $valueresultGETGroupMenu) { ?>
                            <?php
                            $groupid = $valueresultGETGroupMenu['group_id'];
                            // SQL Check Count
                            $sqlCheckCount = $conn->prepare("SELECT COUNT(*) FROM `rtr_menu` WHERE menu_group=$groupid");
                            $sqlCheckCount->execute();
                            $resultCheckCount = $sqlCheckCount->fetch();
                            ?>
                            <a href="./ManageMenu.php?idgroup=<?php echo $valueresultGETGroupMenu['group_id'] ?>" class="list-group-item list-group-item-action <?php if ($idgroup == $valueresultGETGroupMenu['group_id']) { ?>active<?php } ?>"><?php echo $valueresultGETGroupMenu['group_name'] ?>(<?php echo $resultCheckCount[0] ?>)</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal -->
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#ModalAddMenu">เพิ่ม</button>
    <!-- Modal -->
    <div class="modal fade" id="ModalAddMenu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มเมนู</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./action.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="menu_img" class="form-label">ภาพอาหาร</label>
                            <input type="file" name="menu_img" id="menu_img" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="menu_name" class="form-label">ชื่อเมนู</label>
                            <input type="text" name="menu_name" id="menu_name" class="form-control" placeholder="กรอกชื่อเมนู" required>
                        </div>
                        <div class="form-group">
                            <label for="menu_price" class="form-label">ราคา(บาท)</label>
                            <input type="number" name="menu_price" id="menu_price" class="form-control" placeholder="กรอกราคา" required>
                        </div>
                        <div class="form-group">
                            <label for="menu_group" class="form-label">หมวดหมู่อาหาร</label>
                            <select class="form-control" name="menu_group" id="menu_group" required>
                                <option value="">-- เลือกหมวดหมู่อาหาร --</option>
                                <?php foreach ($resultGETGroupMenu as $keyresultGETGroupMenu => $valueresultGETGroupMenu) { ?>
                                    <option value="<?php echo $valueresultGETGroupMenu['group_id'] ?>"><?php echo $valueresultGETGroupMenu['group_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label"></label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="true" name="checkboxgoDetail" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    ไปยังหน้าเพิ่มรายละเอียดหรือไม่ เช่น พิเศษ ไข่ดาว อื่นๆ
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary" name="addMeNu">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Modal -->
</div>
<h3>จัดการเมนู</h3>
<p><?php echo $namegroup ?></p>
<br>
<div class="card card-main">
    <div class="card-body">

        <div class="row">
            <?php foreach ($resultGETMenu as $keyresultGETMenu => $valueresultGETMenu) { ?>
                <?php
                $groupIDloop = $valueresultGETMenu['menu_group'];
                // SQL GET Name Gruop
                $sqlGETNameGroup = $conn->prepare("SELECT `group_name` FROM `rtr_group_menu` WHERE `group_id`=$groupIDloop");
                $sqlGETNameGroup->execute();
                $resultGETNameGRoup = $sqlGETNameGroup->fetch();
                ?>
                <div class="col-sm-3">
                    <a href="./MenuDetail.php?idcode=<?php echo $valueresultGETMenu['menu_id'] ?>">
                        <div class="card card-menu-main">
                            <?php if ($valueresultGETMenu['menu_img'] != null) { ?>
                                <img class="card-img-top img-fluid" style="height: 120px;object-fit: cover;" src="./assets/assets/images/food/<?php echo $valueresultGETMenu['menu_img'] ?>" alt="">
                            <?php } else { ?>
                                <img class="card-img-top img-fluid" style="height: 120px;object-fit: cover;" src="./assets/assets/images/food/nopic.png" alt="">
                            <?php } ?>
                            <div class="card-body">
                                <div class="float-end">
                                    <small><?php echo $resultGETNameGRoup['group_name'] ?></small>
                                </div>
                                <b><?php echo $valueresultGETMenu['menu_name'] ?></b>
                                <br>
                                <div class="float-end">
                                    <?php if ($valueresultGETMenu['menu_status'] == 'true') { ?>
                                        <span class="badge bg-success">ใช้งาน</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger">ไม่ใช้งาน</span>
                                    <?php } ?>
                                </div>
                                <h5 style="color: #F1506F;">฿<?php echo number_format($valueresultGETMenu['menu_price'], 2) ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

<?php
require_once("./LLO.php");
?>
<?php
include('./FLO.php');

// SQL GET Pay Type
$sqlGETPayType = $conn->prepare("SELECT * FROM `rtr_pay_type`");
$sqlGETPayType->execute();
$resultGETPayType = $sqlGETPayType->fetchAll();
?>

<center>
    <div class="card col-sm-6">
        <div class="card-header">
            <div class="float-end">
                <a type="button" data-bs-toggle="modal" data-bs-target="#ModalAddPayType">
                    <span class="iconify" data-icon="bx:bxs-message-square-add" data-width="30" data-height="30" data-rotate="270deg"></span>
                </a>
                <!-- Modal -->
                <div class="modal fade" id="ModalAddPayType" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="text-align: left;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">เพิ่มการชำระเงิน</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="./action.php" method="post">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="paytype" class="form-label">การชำระเงิน</label>
                                        <input type="text" name="paytype" id="paytype" class="form-control" placeholder="การชำระเงิน" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="statusgetmoney" class="form-label">สถานะการรับเงิน</label>
                                        <select class="form-select" name="statusgetmoney" id="statusgetmoney" required>
                                            <option value="">-</option>
                                            <option value="ชำระเรียบร้อย">ชำระเรียบร้อย (นับกำไร)</option>
                                            <option value="ค้างชำระ">ค้างชำระ (ไม่นับกำไร)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                    <button type="submit" class="btn btn-primary" name="addPayType">บันทึก</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ประเภท</th>
                        <th>สถานะการรับเงิน</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultGETPayType as $keyresultGETPayType => $valueresultGETPayType) { ?>
                        <tr>
                            <td><?php echo $valueresultGETPayType['pay_type_name'] ?></td>
                            <td><?php echo $valueresultGETPayType['pay_type_get_money'] ?></td>
                            <td>
                                <a type="button" class="text-warning">แก้ไข</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</center>

<?php
include('./LLO.php');
?>
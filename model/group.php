<?php
                if (isset($_GET['idgroup'])) {
                    $idgroup = $_GET['idgroup'];
                    // SQL GET Name Group
                    $sqlGETNameGroup = $conn->prepare("SELECT `group_name` FROM `rtr_group_menu` WHERE `group_id`=$idgroup");
                    $sqlGETNameGroup->execute();
                    $resultGETNameGroup = $sqlGETNameGroup->fetch();
                    $namegroup = $resultGETNameGroup['group_name'];

                    // SQL GET Menu
                    $sqlGETMenu = $conn->prepare("SELECT `menu_id`,`menu_name`,`menu_price`,`menu_img`,`menu_group` FROM `rtr_menu` WHERE `menu_status`='true' AND `menu_group`=$idgroup");
                    $sqlGETMenu->execute();
                    $resultGETMenu = $sqlGETMenu->fetchAll();
                } else {
                    $idgroup = null;
                    $namegroup = 'ทั้งหมด';

                    // SQL GET Menu
                    $sqlGETMenu = $conn->prepare("SELECT `menu_id`,`menu_name`,`menu_price`,`menu_img`,`menu_group` FROM `rtr_menu` WHERE `menu_status`='true'");
                    $sqlGETMenu->execute();
                    $resultGETMenu = $sqlGETMenu->fetchAll();
                }

                // SQL GET Group Menu
                $sqlGETGroupMenu = $conn->prepare("SELECT `group_id`, `group_name`, `group_by_add`, `group_date_add` FROM `rtr_group_menu`");
                $sqlGETGroupMenu->execute();
                $resultGETGroupMenu = $sqlGETGroupMenu->fetchAll();

                // SQL Check Count
                $sqlCheckCount = $conn->prepare("SELECT COUNT(`menu_id`) FROM `rtr_menu`");
                $sqlCheckCount->execute();
                $resultCheckCountAll = $sqlCheckCount->fetch();
?>

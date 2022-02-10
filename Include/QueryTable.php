<?php
if ($valuerssql["TableStatus"] == '1' && $valuerssql["BKStatus"] == '3' && $valuerssql["BKStatus"] == '4' && $valuerssql["BKStatus"] == '5' && $valuerssql["BKStatus"] == '6' && $valuerssql["BKStatus"] == '7') {
    echo '<a href=' . $_SESSION["ZoneName"] . '.php?TableID=' . base64url_encode($valuerssql["TableID"]) . '&TableNumber=' . base64url_encode($valuerssql["TableNumber"]) . '&Username=' . base64url_encode($_SESSION["Username"]) . '><img src="' . $PathIcon . $valuerssql["TableIcon"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["TableStatus"] == '2') {
    if ($_SESSION["Username"] == $valuerssql["UserRecord"]) {
        echo '<a href=' . $_SESSION["ZoneName"] . '.php?action=delete&id=' . base64url_encode($valuerssql["TableID"]) . '><img src="' . $PathIcon . $valuerssql["TableIconMR"] . '" style="width: 30px;"></br></a>' . $NewVar;
    } else {
        echo '<img src="' . $PathIcon . $valuerssql["TableIconMR"] . '" style="width: 30px;"></br>' . $NewVar;
    }
} elseif ($valuerssql["BKStatus"] == '3') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableIconReserved"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["BKStatus"] == '4') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableIconFood"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["BKStatus"] == '5') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableIconBlue"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["BKStatus"] == '6') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableIconYellow"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["BKStatus"] == '7') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableIconMagenta"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["BKStatus"] == '8') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableBlueVIP"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["BKStatus"] == '9') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableYellowVIP"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["BKStatus"] == '10') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableMagentaVIP"] . '" style="width: 30px;"></br></a>' . $NewVar;
} elseif ($valuerssql["BKStatus"] == '11') {
    echo '<a data-bs-toggle="modal" data-bs-target="#view-modal" id="getUser" data-id="' . $valuerssql["TableID"] . '" href=""><img src="' . $PathIcon . $valuerssql["TableRedVIP"] . '" style="width: 30px;"></br></a>' . $NewVar;
} else {
    echo '<a href=' . $_SESSION["ZoneName"] . '.php?TableID=' . base64url_encode($valuerssql["TableID"]) . '&TableNumber=' . base64url_encode($valuerssql["TableNumber"]) . '&Username=' . base64url_encode($_SESSION["Username"]) . '><img src="' . $PathIcon . $valuerssql["TableIcon"] . '" style="width: 30px;"></br></a>' . $NewVar;
}
?>
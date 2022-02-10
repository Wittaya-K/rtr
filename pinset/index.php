<?php
require_once("../database/conn.php");

if ($_COOKIE['rtr_user_id'] == null) {
  header('location:../loginPage.php');
  exit;
}

$userID = $_COOKIE['rtr_user_id'];

// SQL GET user
$sqlGETuser = $conn->prepare("SELECT `user_id`, `user_title`, `user_name`, `user_surname`, `user_phone`, `user_password`, 
`user_pin`, `user_position`, `user_by_add`, `user_date_add`, `user_status`, `user_pic` FROM `rtr_user` WHERE `user_id`=$userID");
$sqlGETuser->execute();
$resultGETuser = $sqlGETuser->fetch();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>PIN </title>
  <style>
    @font-face {
      font-family: Gilroy-Black;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-Black.ttf);
    }

    @font-face {
      font-family: Gilroy-BlackItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-BlackItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-Bold;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-Bold.ttf);
    }

    @font-face {
      font-family: Gilroy-BoldItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-BoldItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-ExtraBold;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-ExtraBold.ttf);
    }

    @font-face {
      font-family: Gilroy-ExtraBoldItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-ExtraBoldItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-Heavy;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-Heavy.ttf);
    }

    @font-face {
      font-family: Gilroy-HeavyItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-HeavyItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-Light;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-Light.ttf);
    }

    @font-face {
      font-family: Gilroy-LightItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-LightItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-Medium;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-Medium.ttf);
    }

    @font-face {
      font-family: Gilroy-MediumItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-MediumItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-Regular;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-Regular.ttf);
    }

    @font-face {
      font-family: Gilroy-RegularItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-RegularItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-SemiBold;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-SemiBold.ttf);
    }

    @font-face {
      font-family: Gilroy-SemiBoldItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-SemiBoldItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-Thin;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-Thin.ttf);
    }

    @font-face {
      font-family: Gilroy-ThinItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-ThinItalic.ttf);
    }

    @font-face {
      font-family: Gilroy-UltraLight;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-UltraLight.ttf);
    }

    @font-face {
      font-family: Gilroy-UltraLightItalic;
      src: url(https://unpkg.com/aks-fonts@1.0.0/Gilroy/Gilroy-UltraLightItalic.ttf);
    }
  </style>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./style.css">

</head>
<style>
  body {
    background-image: url('../assets/assets/images/system/BG.png');
    background-size: cover;
  }

  .card {
    -webkit-border-radius: 25px;
    border-radius: 25px;
  }

  .card-header {
    -webkit-border-radius: 10px;
    border-radius: 10px;
  }
</style>

<body>
  <div class="main">
    <div class="card" style="background-color: white;padding: 30px 30px 30px 30px;">

      <?php if (isset($_GET['PINERROR'])) { ?>
        <div class="card-header" style="padding: 20px 20px 20px 20px;background-color: #fc458a;color: white;">
          PIN ไม่ถูกต้อง
        </div>
        <br>
      <?php } ?>

      <div class="card-body">
        <div class="row">
          <div class="col-4">
            <h1>Pin ตัวเลข 4 หลัก</h1>
            <div class="aks-form">
              <label class="aks-form-label">Pin Code</label>
              <div class="aks-form-row aks-form-pin" data-pin="">
                <input type="password" onkeyup="CheckPIN()" id="pin1" class="aks-input" maxlength="1" autocomplete="off" pattern="[0-9]*" inputmode="numeric" autofocus />
                <input type="password" onkeyup="CheckPIN()" id="pin2" class="aks-input" maxlength="1" autocomplete="off" pattern="[0-9]*" inputmode="numeric" />
                <input type="password" onkeyup="CheckPIN()" id="pin3" class="aks-input" maxlength="1" autocomplete="off" pattern="[0-9]*" inputmode="numeric" />
                <input type="password" onkeyup="CheckPIN()" id="pin4" class="aks-input" maxlength="1" autocomplete="off" pattern="[0-9]*" inputmode="numeric" />
              </div>
            </div>
          </div>
        </div>
        <br>
        <hr>
        <br>
        <b>ยินดีต้อนรับ</b>
        <p><?php echo $resultGETuser['user_title'] . $resultGETuser['user_name'] . ' ' . $resultGETuser['user_surname'] ?></p>
        <p>
          <a href="../action.php?logout" style="text-decoration: none;color: red;">ออกจากระบบ</a>
        </p>
      </div>
    </div>
  </div>
  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src="./script.js"></script>

  <script>
    function CheckPIN() {
      var pin1 = document.getElementById("pin1").value;
      var pin2 = document.getElementById("pin2").value;
      var pin3 = document.getElementById("pin3").value;
      var pin4 = document.getElementById("pin4").value;

      if (pin1 != '' && pin2 != '' && pin3 != '' && pin4 != '') {
        var fullPIN = pin1 + pin2 + pin3 + pin4;
        window.location.href = "../action.php?SetPin=" + fullPIN;
      }
    }
  </script>

</body>

</html>
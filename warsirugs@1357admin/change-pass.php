<?php
session_start();
error_reporting(0);
include 'connection.php';

if (!isset($_SESSION['ADMIN_USERID']) && $_SESSION['ADMIN_USERID'] == '') {
  header('location:index.php');
}

$sucess = "";
$message = "";

if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Change Password') {
  $oldpass = $_POST['oldpass'];
  $newpass = $_POST['newpass'];
  $cnfpass = $_POST['cnfpass'];
  $sql = $conn->prepare("SELECT `password` FROM `admin` WHERE `id` = '" . $_SESSION['ADMIN_USERID'] . "'");
  $sql->execute();
  $row = $sql->fetch(PDO::FETCH_ASSOC);

  $pass = $row['password'];

  if ($pass == $oldpass) {
    if (!empty($newpass)) {
      if (!empty($cnfpass)) {

        if ($newpass == $cnfpass) {
          $query = $conn->prepare("UPDATE `admin` SET `password` = :pass WHERE `id` = '" . $_SESSION['ADMIN_USERID'] . "'");
          $query->bindParam(':pass', $newpass, PDO::PARAM_STR);
          $query->execute();
          if ($query) {
            $sucess = "<b><center>PASSWORD HAS CHANGED<center><b>";
          }
        } else {
          $message = "<b><center>CONFIRM PASSWORD AND NEW PASSWORD DOESNOT MATCH<center><b>";
        }
      } else {

        $message = "<b><center>PLEASE ENTER CONFIRM PASSWORD<center><b>";
      }
    } else {

      $message = "<b><center>PLEASE ENTER NEW PASSWORD<center><b>";
    }
  } else {
    $message = "<b><center>OLD PASSWORD DOES NOT MATCH<center><b>";
  }
}
?>
<!doctype html>
<html lang="en-US">
<link rel="shortcut icon" href="images/logo.png">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> <?php echo $adminTitle; ?></title>
  <link rel="shortcut icon" href="./images/favicon.png">
  <link href="css/bootstrap.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet" />
  <link href="css/nav-core.css" rel="stylesheet" />
  <link href="css/nav-layout.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">

  <script type="text/javascript">
    function valiDate() {

      if (document.myForm.oldpass.value == '') {

        alert('Please Provide Old Password !');
        document.myForm.oldpass.focus();
        return false;
      }

      if (document.myForm.newpass.value == '') {

        alert('Please Provide New Password !');
        document.myForm.newpass.focus();
        return false;
      }

      if (document.myForm.cnfpass.value == '') {

        alert('Please Enter Confirm Password !!');
        document.myForm.cnfpass.focus();
        return false;
      }

      if (document.myForm.newpass.value != document.myForm.cnfpass.value) {

        alert('Passwords are not Same. Please re-type Password. !!');
        document.myForm.newpass.value = '';
        document.myForm.cnfpass.value = '';
        document.myForm.newpass.focus();
        return false;
      }

      return true;

    }
  </script>

</head>

<body>

  <!-- header -->
  <?php require('header.php'); ?>

  <div class="container">
    <div class="row">
      <div class="col-md-12 margin-30">
        <div class="content-home">
          <h2>Change Passwords</h2>
          <div class="message" style='color:red'><?php if ($message != "") {
                                                    echo $message;
                                                  } ?></div>
          <div class="message" style='color:green'><?php if ($sucess != "") {
                                                      echo $sucess;
                                                    } ?></div>
          <form action="change-pass.php" name="myForm" id="myForm" method="POST" onsubmit="return valiDate();" class="form-horizontal margin-55">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label text-black">Old Password</label>
              <div class="col-sm-6">
                <input type="text" name="oldpass" class="form-control" placeholder="Old Password">
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label text-black">New Password</label>
              <div class="col-sm-6">
                <input type="text" name="newpass" class="form-control" placeholder="New Password">
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label text-black">Confirm Password</label>
              <div class="col-sm-6">
                <input type="text" name="cnfpass" class="form-control" placeholder="Confirm Password">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <div class="submit"> <span style="color:#FF0000; font-size:14px; font-style:italic;"></span>
                  <input type="submit" class="submit-btn" style="width:18%;" name="submit" type="submit" value="Change Password" />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script src="js/nav.jquery.min.js" type="text/javascript"></script>
  <script>
    $('.nav').nav();
  </script>
</body>

</html>
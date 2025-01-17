<?php
session_start();
error_reporting(0);
include 'connection.php';

if(!isset($_SESSION['ADMIN_USERID']) && $_SESSION['ADMIN_USERID']=='')
{
header('location:index.php');
}

?>
<!doctype html>
<html lang="en-US">
<link rel="shortcut icon" href="images/logo-red-300px.jpg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> <?php echo $adminTitle; ?></title>
    <link rel="shortcut icon" href="images/favicon.png">
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/nav-core.css" rel="stylesheet" />
    <link href="css/nav-layout.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
</head>
<body>

<!-- header -->
<?php require('header.php'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12 margin-60">
      <div class="content">
        <h1>Welcome To Admin</h1>
        <!-- <img src="" width="130" height="75" style="display: block; margin-left: 20px;"> -->
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

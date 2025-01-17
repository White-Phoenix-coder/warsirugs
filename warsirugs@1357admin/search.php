<?php
session_start();
error_reporting(0);
include 'connection.php';

if(isset($_POST['btn_login']) && $_POST['btn_login']=='LOGIN')
{
    $email = $_POST['email'];
    $pass = $_POST['password'];

  $st = $conn ->prepare("SELECT * FROM `admin` WHERE `email` = :em and `password` = :pass ");
  $st->bindParam(':em', $email, PDO::PARAM_STR);
  $st->bindParam(':pass', $pass, PDO::PARAM_STR);
  $st->execute();

  $check = $st->fetch(PDO::FETCH_ASSOC);

  if(!empty($check['email']))
  {
  // $_SESSION['ADMIN_USERID'] = $check['email'];
  $_SESSION['ADMIN_USERID'] = $check['id'];
  $_SESSION['ADMIN_USERNAME'] = $check['username'];
  $_SESSION['ADMIN_IMAGE'] = $check['image'];
    header('location:home.php');
  }
  else
  {
    $msg = '<div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-ban"></i> Error</h4>
              Incorrect Username or Password
            </div>'; 
  }
}
?>
<!doctype html>
<html lang="en-US">
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
    <script type="text/javascript" language="javascript">
		
		function validate(){
		
			if(document.myForm.email.value==''){
			
				alert('Please Provide Username !');
				document.myForm.email.focus();
				return false;
			
			}
			
			if(document.myForm.password.value==''){
			
				alert('Please Provide Password !');
				document.myForm.password.focus();
				return false;
			
			}
			
			return true;
		
		}

    </script>
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-md-12 margin-60">
      <div class="content-inner">
        <div class="text-center"><a href="index.php"><img src="images/logo.png" alt="" class="img-responsive center-block" style="width: 200px;"> </a> </div>
        <div class="col-md-12 margin-40">
        	<?php if(isset($msg)){echo $msg;}?>
          <form name="myForm" id="myForm" method="post" onSubmit="return validate()">
            <div class="form-group">
              <label for="UserName">Email</label>
              <input type="email" name="email" class="form-control" id="email" >
            </div>
            <div class="form-group">
              <label for="Password">Password</label>
              <input type="password" name='password' class="form-control" id="password" >
            </div>
            <div class="submit"> <span style="color:#FF0000; font-size:14px; font-style:italic;"></span>
              <input type="submit" class="submit-btn" value="LOGIN" name="btn_login" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Script here -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/bootstrap.js" type="text/javascript"></script>
<script src="js/nav.jquery.min.js" type="text/javascript"></script>
<script>
    $('.nav').nav();
</script>
</body>
</html>

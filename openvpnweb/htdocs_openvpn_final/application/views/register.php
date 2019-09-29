<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Website Name</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?=base_url('/asset/main.css?t='.date('m-s'))?>">
  <style type="text/css">
	.login-box{
		margin: 0 auto;
		width: 350px;
		/*margin-top: 10%;*/
		top: 10%;
		position: relative;
	}
  </style>
</head>
<body>
	<div class="login-box">
		<?php if(isset($message)) echo $message; ?>
		<div class="container" style="width: auto">
			<h1>ยินดีต้อนรับเข้าสู่ Website Name</h1>
			<form action="<?=base_url('/login/confirm_register')?>" method="post">
				<div class="form-group">
					<label for="">User:</label>
					<input type="text" class="form-control" name="user" required>
				</div>
				<div class="form-group">
					<label for="">Password:</label>
					<input type="password" class="form-control" name="pwd" required>
				</div>
				<div class="form-group">
					<label for="">Confirm Password:</label>
					<input type="password" class="form-control" name="pwd_re" required>
				</div>
				<div class="form-group">
					<label for="">Email:</label>
					<input type="email" class="form-control" name="email" required>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-success">สมัคร</button>
				</div>
			<form>
			<br>
			<small>มีบัญชีอยู่แล้ว <a href="<?=base_url('/login/')?>"> เข้าสู่ระบบ</a></small>
		</div>
	</div>
</body>
</html>
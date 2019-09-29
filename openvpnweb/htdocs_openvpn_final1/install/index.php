<?php
if(isset($_GET['clear'])){
	session_start();
	if(session_destroy()){
		echo "<script>window.location.replace('/install')</script>";
	}
}
if(isset($_GET['reload'])&&$_GET['reload']==1){
	echo "<script>window.location.replace('/install?install=ok')</script>";
}
session_start();
if(empty($_SESSION['reload'])){
	$_SESSION['reload'] = false;
	$_SESSION['log'] = "";
	$_SESSION['installed'] = false;
}
function delTree($dir) { 
	$files = array_diff(scandir($dir), array('.','..')); 
	foreach ($files as $file) { 
		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
	} 
	return rmdir($dir); 
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>ติดตั้ง Database</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
		<script>
			$(document).ready(function(){
				$('input').attr('autocomplete','off');
			});
		</script>
	</head>
	<body>
	<?php if($_SESSION['reload'] == false){ ?>
	<center><h1>ติดตั้ง</h1></center>
		<div class="container">
		    <div class="row">
		        <form method="post" action="">
		        <div class="col-xs-6">
		            <div class="panel panel-default">
		                <div class="panel-heading"> ตั้งค่าดาต้าเบส</div>
		                <div class="panel-body">
		                    <div class="form-group">
		                        <label for="hostname">Hostname</label>
		                        <input type="text" id="hostname" value="localhost" class="form-control" name="hostname"/>
		                    </div>
		                    <div class="form-group">
		                        <label for="username">Username</label><input type="text" id="username" class="form-control" placeholder="ชื่อผู้ใช้ดาต้าเบส" name="username" value="root" required>
		                    </div>
		                    <div class="form-group">
		                        <label for="password">Password</label><input type="text" id="password" class="form-control" placeholder ="รหัสดาต้าเบส" name="password" value="">
		                    </div>
		                    <div class="form-group">
		                        <label for="database">Database Name</label><input type="text" id="database" class="form-control" placeholder="ชื่อดาต้าเบส" name="database" value="free_ocs" required>
		                    </div>
		                </div>
		                <div class="panel-footer"></div>
		            </div>
		            
		        </div>
		        <div class="col-xs-6">
		            <div class="panel panel-default">
		                <div class="panel-heading"> ตั้งค่าแอดมิน</div>
		                <div class="panel-body">
		                    <div class="form-group">
		                        <label for="username">Admin username</label><input type="text" id="user" class="form-control" placeholder="ชื่อผู้ใช้แอดมิน" name="user" required>
		                    </div>
					        <div class="form-group">
					            <label for="password">Admin password</label><input type="text" id="pwd" class="form-control" placeholder ="รหัสแอดมิน" name="pwd" required>
					       </div>
					       <div class="form-group">
						        <label for="database">Email</label><input type="email" id="email" class="form-control" placeholder="อีเมลล์" name="email" required>
						  </div>
		                </div>
		                <div class="panel-footer"></div>
		            </div>
		        </div>
		        <div class="form-group">
		            <input type="submit" value="Install" id="submit" name="install" class="btn btn-default form-control"/>
		        </div>
		        </form>
		    </div>
		</div>
		<?php } ?>
	</body>
</html>
<?php
if(!$_SESSION['reload'] == false){
	echo "<div class=\"container\">---Log---<br>";
	echo "-กดยืนยัน อีกหนึ่งรอบ<br><br>";
	echo '<form method="post"><input type="submit" value="ยืนยัน" name="install" class="btn btn-default form-control" style="width:100px"/></form>';
	echo "</div>";
}
if(isset($_POST['install'])){
	if($_SESSION['reload'] == false){
		echo "<div class=\"container\">---Log---<br>";
		$conn = mysqli_connect($_POST['hostname'], $_POST['username'], $_POST['password']);
		if(!$conn){
			echo 'Connected failure<br>';
			exit();
		}
		$sql = "DROP DATABASE ".$_POST['database'];
		if(mysqli_query($conn, $sql)) {
			$_SESSION['log'] =$_SESSION['log']."-ลบดาต้าเบสสำเร็จแล้ว<br>";
			$_SESSION['installed'] = true;
		}else{
			$_SESSION['log'] =$_SESSION['log']."-ไม่สามารถลบดาต้าเบสได้: " . mysqli_error($conn)."<br>";
			$_SESSION['installed'] = false;
		}
		$sql = "CREATE DATABASE ".$_POST['database']." CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
		if(mysqli_query($conn, $sql)) {
			$_SESSION['log'] =$_SESSION['log']."-สร้างดาต้าเบสสำเร็จ<br>";
			$_SESSION['installed'] = true;
		}else{
			$_SESSION['log'] =$_SESSION['log']."-ไม่สามารถสร้างดาต้าเบสได้: " . mysqli_error($conn)."<br>";
			$_SESSION['installed'] = false;
			exit();
		}
		mysqli_close($conn);
		
		$sql = file_get_contents('free_ocs.sql');
		
		$mysqli = new mysqli($_POST['hostname'], $_POST['username'], $_POST['password'], $_POST['database']);
		if(mysqli_connect_errno()){
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		if($mysqli->multi_query($sql)){
			$_SESSION['log'] =$_SESSION['log']."-นำเข้าดาต้าเบสสำเร็จ<br>";
			$_SESSION['installed'] = true;
		}else{
			$_SESSION['log'] =$_SESSION['log']."-ไม่สามารถนำเข้าดาต้าเบสได้<br>";
			$_SESSION['installed'] = false;
		}
		$mysqli->close();
		$_SESSION['reload'] = true;
		echo "<script>window.location.replace('/install?reload=1')</script>";
		$_SESSION['hostname'] = $_POST['hostname'];
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['password'] = $_POST['password'];
		$_SESSION['database'] = $_POST['database'];
		$_SESSION['user'] = $_POST['user'];
		$_SESSION['pwd'] = $_POST['pwd'];
		$_SESSION['email'] = $_POST['email'];
		echo "</div>";
	}else{
		echo "<div class=\"container\">---Log---<br>";
		$conn = new mysqli($_SESSION['hostname'], $_SESSION['username'], $_SESSION['password'], $_SESSION['database']);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$user = $_SESSION['user'];
		$pass = $_SESSION['pwd'];
		$email = $_SESSION['email'];
		$sql = "INSERT INTO users VALUES (1, '$user', '$pass', 999999, '$email', 1, 1)";

		if ($conn->query($sql) === TRUE) {
			$_SESSION['log'] =$_SESSION['log']."ติดตั้งสำเร็จ<br>";
			$_SESSION['installed'] = true;
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
			$_SESSION['installed'] = false;
		}
		$conn->close();
		$file = file_get_contents("database.php");
		$file = str_replace("%HOSTNAME%",$_SESSION['hostname'],$file);
		$file = str_replace("%USERNAME%",$_SESSION['username'],$file);
		$file = str_replace("%PASSWORD%",$_SESSION['password'],$file);
		$file = str_replace("%DATABASE%",$_SESSION['database'],$file);
		file_put_contents("tmp.php",$file);
		$link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
		$file2 = file_get_contents("config.php");
		$file2 = str_replace("%IPSERVER%",$link,$file2);
		file_put_contents("tmp2.php",$file2);
		unlink('../application/config/database.php');
		unlink('../application/config/config.php');
		copy('tmp.php', '../application/config/database.php');
		copy('tmp2.php', '../application/config/config.php');
		echo $_SESSION['log'];
		echo '<br><a type="button" href="'.$link.'" class="btn btn-default">ไปหน้าแรก</a>';
		session_destroy();
		echo "</div>";
		
		if($_SESSION['installed'] == true){
			unlink('config.php');
			unlink('database.php');
			unlink('free_ocs.sql');
			unlink('index.php');
			unlink('tmp.php');
			unlink('tmp2.php');
			copy("../index_real.php","../index.php");
			$tmp = "<script>location.replace('/')</script>";
			file_put_contents("index.php", $tmp);
		}
	}
}
?>
<?php
include "ssh_lib.php";

$action = new ssh_lib();

$data = array(
	"hostname" => "",
	"rootpass" => "",
	"username" => $_POST['user'],
	"password" => $_POST['pass'],
	"expired"  => 30
);

if($action->addAccount($data)){
	echo "สมัครสำเร็จ";
}else{
	echo "ไม่สำเร็จ";
}

?>
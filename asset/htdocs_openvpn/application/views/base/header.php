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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script>
  </script>
</head>
<body>
<nav class="navbar navbar-default visible-xs">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">WebsiteName</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
		<li><a href="<?=base_url('/')?>"><i class="fa fa-home"></i> หน้าแรก</a></li>
		<li><a href="<?=base_url('/main/server')?>"><i class="fa fa-server"></i> เซิร์ฟเวอร์</a></li>
		<li><a href="<?=base_url('/main/addpoint')?>"><i class="fa fa-bank"></i> เติมเงิน</a></li>
        <li><a href="<?=base_url('/setting')?>"><i class="fa fa-cog"></i> ตั้งค่า</a></li>
        <li><a href="<?=base_url('/logout')?>"><i class="fa fa-sign-out"></i> ออกจากระบบ</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav hidden-xs">
      <h3>WebsiteName</h3>
	  <hr style="border-top: 1px solid #a7a4a4;">
	  <span class="label label-warning"><?=$_SESSION['username']?></span> | <span class="label label-success"><?php if($_SESSION['admin']) echo "Admin"; else echo "Member"; ?></span>
	  <hr style="border-top: 1px solid #a7a4a4;">
	  ยอดคงเหลือ | <span class="label label-info"><?=$_SESSION['balance']?></span>
	  <hr style="border-top: 1px solid #a7a4a4;">
      <ul class="nav nav-pills nav-stacked" id="myNavbar-ul">
        <li><a href="<?=base_url('/')?>"><i class="fa fa-home"></i> หน้าแรก</a></li>
		<li><a href="<?=base_url('/main/server')?>"><i class="fa fa-server"></i> เซิร์ฟเวอร์</a></li>
		<li><a href="<?=base_url('/main/addpoint')?>"><i class="fa fa-bank"></i> เติมเงิน</a></li>
        <li><a href="<?=base_url('/setting')?>"><i class="fa fa-cog"></i> ตั้งค่า</a></li>
        <li><a href="<?=base_url('/logout')?>"><i class="fa fa-sign-out"></i> ออกจากระบบ</a></li>
      </ul><br>
    </div>
    <div class="content-wrap">
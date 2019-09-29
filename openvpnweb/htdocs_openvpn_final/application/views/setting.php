<div class="container">
	<h1>เปลี่ยนรหัสผ่าน</h1>
	<p>คุณ <?=$_SESSION['username']?></p>
	
	<center>
		<div class="col-box text-left" style="">
			<?php if(isset($message)) echo $message; ?>
			<form action="<?=base_url('/login/confirm_setting/')?>" method="POST">
				<div class="form-group">
					<label for="usr">รหัสผ่านเดิม:</label>
					<input type="password" class="form-control" name="pwd_old">
				</div>
				<div class="form-group">
					<label for="pwd">รหัสผ่านใหม่:</label>
					<input type="password" class="form-control" name="pwd_new">
				</div>
				<div class="form-group">
					<label for="pwd">รหัสผ่านอีกครั้ง:</label>
					<input type="password" class="form-control" name="pwd_new2">
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-success">บันทึก</button>
					<a type="button" class="btn btn-danger" href="<?=base_url('/')?>">ยกเลิก</a>
				</div>
			</form>
		</div>
	</center>
</div>
<div class="container">
	<h1>เพิ่มเซิร์ฟเวอร์</h1>
	<p>SERVER</p>
	
	<center>
		<div class="col-box text-left" style="">
			<?php if(isset($message)) echo $message; ?>
			<form action="<?=base_url('/server/confirmadd/')?>" method="POST">
				<div class="form-group">
					<label for="usr">Server Name:</label>
					<input type="text" class="form-control" name="s_name" required>
				</div>
				<div class="form-group">
					<label for="usr">Server IP:</label>
					<input type="text" class="form-control" name="s_ip" required>
				</div>
				<div class="form-group">
					<label for="usr">รหัสผ่าน เซิร์ฟเวอร์:</label>
					<input type="text" class="form-control" name="s_pass" required>
				</div>
				<div class="form-group">
					<label for="usr">ราคา:</label>
					<input type="number" class="form-control" name="s_price" required>
				</div>
				<div class="form-group">
					<label for="usr">หมดอายุ:</label>
					<input type="number" class="form-control" name="s_expire" required>
				</div>
				<div class="form-group">
					<label for="usr">จำกัด:</label>
					<input type="number" class="form-control" name="s_limit" required>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-success">บันทึก</button>
					<a type="button" class="btn btn-danger" href="<?=base_url('/main/server')?>">ยกเลิก</a>
				</div>
			</form>
		</div>
	</center>
</div>
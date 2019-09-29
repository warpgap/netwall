<div class="container">
	<h1>แก้ไขเซิร์ฟเวอร์</h1>
	<p>SERVER <?=$row->s_name?></p>
	
	<center>
		<div class="col-box text-left" style="">
			<?php if(isset($message)) echo $message; ?>
			<form action="<?=base_url('/server/confirmedit/')?>" method="POST">
				<input type="hidden" value="<?=$row->s_id?>" name="s_id">
				<div class="form-group">
					<label for="usr">Server Name:</label>
					<input type="text" class="form-control" value="<?=$row->s_name?>" name="s_name" required>
				</div>
				<div class="form-group">
					<label for="usr">Server IP:</label>
					<input type="text" class="form-control" value="<?=$row->s_ip?>" name="s_ip" required>
				</div>
				<div class="form-group">
					<label for="usr">รหัสผ่าน เซิร์ฟเวอร์:</label>
					<input type="text" class="form-control" value="<?=$row->s_pass?>" name="s_pass" required>
				</div>
				<div class="form-group">
					<label for="usr">ราคา:</label>
					<input type="number" class="form-control" value="<?=$row->s_price?>" name="s_price" required>
				</div>
				<div class="form-group">
					<label for="usr">หมดอายุ:</label>
					<input type="number" class="form-control" value="<?=$row->s_expire?>" name="s_expire" required>
				</div>
				<div class="form-group">
					<label for="usr">จำกัด:</label>
					<input type="number" class="form-control" value="<?=$row->s_limit?>" name="s_limit" required>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-success">บันทึก</button>
					<a type="button" class="btn btn-danger" href="<?=base_url('/main/server')?>">ยกเลิก</a>
				</div>
			</form>
		</div>
	</center>
</div>
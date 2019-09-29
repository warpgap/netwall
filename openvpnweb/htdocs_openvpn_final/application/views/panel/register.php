<div class="container">
	<h1>เช่าบัญชี OPENVPN</h1>
	<p>SERVER <?=$row->s_name?></p>
	
	<center>
		<div class="col-box text-left" style="">
			<?php if(isset($message)) echo $message; ?>
			<form action="<?=base_url('/server/confirm/'.$sid)?>" method="POST">
				<div class="form-group">
					<label for="usr">User:</label>
					<input type="text" class="form-control" name="user_ssh" required>
				</div>
				<div class="form-group">
					<label for="pwd">Password:</label>
					<input type="password" class="form-control" name="pwd_ssh" required>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-success">ยืนยัน</button>
					<a type="button" class="btn btn-danger" href="<?=base_url('/main/server')?>">ยกเลิก</a>
				</div>
			</form>
		</div>
	</center>
</div>
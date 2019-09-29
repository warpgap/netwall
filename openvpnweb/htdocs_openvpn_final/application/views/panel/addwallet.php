<div class="container">
	<h1>เพิ่มวอเล็ท</h1>
	<p>ทรูวอเล็ท</p>
	<center>
		<?php if(!isset($edit)): ?>
		<div class="col-box text-left" style="">
			<?php if(isset($message)) echo $message; ?>
			<form action="<?=base_url('/server/confirmaddwallet/')?>" method="POST">
				<div class="form-group">
					<label for="usr">เบอร์วอเล็ท:</label>
					<input type="text" class="form-control" name="mobile" required>
				</div>
				<div class="form-group">
					<label for="usr">พิน 4 หลัก:</label>
					<input type="text" class="form-control" name="pin" required>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-success">บันทึก</button>
					<a type="button" class="btn btn-danger" href="<?=base_url('/main/addpoint')?>">ยกเลิก</a>
				</div>
			</form>
		</div>
		<?php else: ?>
		<div class="col-box text-left" style="">
			<?php if(isset($message)) echo $message; ?>
			<form action="<?=base_url('/server/confirmeditwallet/')?>" method="POST">
				<div class="form-group">
					<label for="usr">เบอร์วอเล็ท:</label>
					<input type="text" class="form-control" name="mobile" value="<?=$edit->mobile?>" required>
				</div>
				<div class="form-group">
					<label for="usr">พิน 4 หลัก:</label>
					<input type="text" class="form-control" name="pin" value="<?=$edit->pin?>" required>
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-success">บันทึก</button>
					<a type="button" class="btn btn-danger" href="<?=base_url('/main/addpoint')?>">ยกเลิก</a>
				</div>
			</form>
		</div>
		<?php endif; ?>
	</center>
</div>
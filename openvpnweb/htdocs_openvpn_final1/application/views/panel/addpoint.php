<div class="container">
	<h1>เติมเงินเข้าบัญชี</h1>
	<p>คุณ <?=$_SESSION['username']?></p>
	<?php if($_SESSION['admin']): ?>
		<p class="text-right">
			<a type="button" class="btn btn-default" href="<?=base_url('/server/addwallet')?>">
				<i class="fa fa-plus" style="font-size:30px"></i>
			</a>
		</p>
	<?php endif; ?>
	<center>
		<?php if(isset($mobile)): ?>
		<div class="col-box text-left" style="">
			<?php if(isset($message)) echo $message; ?>
			<p>โอนเงินทางทรูวอเล็ทมาที่เบอร์ <?=$mobile->mobile?></p>
			<form action="<?=base_url('/main/confirmaddpoint')?>" method="POST">
				<div class="form-group">
					<label for="">เลขอ้างอิง</label>
					<input type="text" class="form-control" name="ref_no" placeholder="50000xxxxxxxx">
				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-success">ยืนยัน</button>
					<a type="button" class="btn btn-danger" href="<?=base_url('/')?>">ยกเลิก</a>
				</div>
			</form>
		</div>
		<?php else: ?>
		<div class="col-box text-left" style="">
			<?php if(isset($message)) echo $message; ?>
			<p>ยังไม่รองรับการโอนเงิน</p>
		</div>
		<?php endif; ?>
	</center>
</div>
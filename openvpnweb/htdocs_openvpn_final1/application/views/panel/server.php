<div class="container" style="/*width:auto*/">
	<h1>เช่าบัญชี OPENVPN</h1>
	<p>ทั้งหมด <?=count($row)?> บัญชี</p>
	<?php if($_SESSION['admin']): ?>
		<p class="text-right">
			<a type="button" class="btn btn-default" href="<?=base_url('/server/addserver')?>">
				<i class="fa fa-plus" style="font-size:30px"></i>
			</a>
		</p>
	<?php endif; ?>
	<?php for($i=0;$i<count($row);$i++): ?>
	<div class="server-col">
		<div class="panel panel-default">
			<div class="panel-heading">Server <?=$i+1?></div>
			<div class="panel-body">
				<?php if($_SESSION['admin']): ?>
				<div id="tar-chi">
					<div class="alignleft">
						<label class="switch ">
							<?php
								if($row[$i]['s_status']==1)
									echo '<input type="checkbox" onclick="switch_(this)" data-serverid="'.$row[$i]['s_id'].'" checked>';
								else
									echo '<input type="checkbox" onclick="switch_(this)" data-serverid="'.$row[$i]['s_id'].'">';
							?>
							<span class="slider"></span>
						</label>
					</div>
					<div class="alignright">
						<a type="button" class="btn btn-default" href="<?=base_url('/server/edit/'.$row[$i]['s_id'])?>">แก้ไข</a>
						<a type="button" class="btn btn-danger" onclick="delserver('<?=$row[$i]['s_id']?>')">ลบ</a>
					</div>
				</div>
				<div style="clear: both"></div>
				<?php endif; ?>
				<table class="table table-hover table-responsive">
					<thead>
						<tr>
							<th>Server</th>
							<th><?=$row[$i]['s_name']?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>IP</td>
							<td><?=$row[$i]['s_ip']?></td>
						</tr>
						<tr>
							<td>ราคา</td>
							<td><?=$row[$i]['s_price']?> บาท</td>
						</tr>
						<tr>
							<td>หมดอายุ</td>
							<td><?=$row[$i]['s_expire']?> วัน</td>
						</tr>
						<tr>
							<td>จำกัด</td>
							<td><?=$row[$i]['s_limit']?> บัญชี</td>
						</tr>
						<tr>
							<td>ผู้ใช้</td>
							<td><?=$row[$i]['s_account']?> บัญชี</td>
						</tr>
						<tr>
							<td>สถานะ</td>
							<td>
								<?php
									if($row[$i]['s_status']==1)
										echo "ปกติ";
									else
										echo "ปิด";
								?>
							</td>
						</tr>
						<tr>
							<td><a type="button" class="btn btn-default" href="<?=base_url('/main/register/'.$row[$i]['s_id'])?>">สมัคร</a></td>
							<!--<td><button type="button" class="btn btn-info">โหลดไฟล์</button></td>-->
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php endfor; ?>
	<script>
		function switch_(e){
			var value = 0;
			if ($(e).is(':checked')){
				value = 1;
			}else{
				value = 0;
			}
			var url = '<?=base_url('/server/switchserver/')?>'+$(e).data('serverid')+'/'+value;
			location.replace(url);
		}
		function delserver(id){
			var txt;
			var r = confirm("ยืนยันการลบเซิร์ฟเวอร์ "+ id);
			if(r==true){
				var url = '<?=base_url('/server/del/')?>' + id;
				location.replace(url);
			}
		}
	</script>
	<div style="clear:both"></div>
</div>
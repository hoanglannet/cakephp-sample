<div class="table-caption">Danh sách tài khoản người dùng</div>
<table class="grid" cellspacing="0" cellpadding="0" style="width: 100%">
	<thead>
		<tr>
			<th style="width: 3%;">&nbsp;</th>
			<th style="width: 15%;">Tài Khoản</th>
			<th style="width: 20%;">Tên</th>
			<th style="width: 15%;">Nhóm</th>
			<th>Trạng Thái</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($users as $user): ?>
		<tr>
			<td>
				<?php 
					echo $this->Html->link(
								$this->Html->image('inline-icon-edit.png', array('alt' => 'Sửa thông tin tài khoản')),
								'javascript:editUser(' . $user['User']['id'] . ')',
								array('title' => 'Sửa thông tin tài khoản', 'escape'=>false)
							);
		?>
			</td>
			<td><?php echo $user['User']['username']; ?></td>
			<td><?php echo $user['User']['name']; ?></td>
			<td><?php echo $user['Group']['name']; ?></td>
			<td>
				<?php 
					if ($user['User']['active'] == 1) {
				echo 'Đang sử dụng';
			} else {
				echo 'Tạm khoá';
			}
		?>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<div class="table-footer-2" style="text-align: right;">
	<!-- Shows the page numbers -->
	<?php echo $ajaxPaginator->prev('Prev', 'reloadUserTable'); ?>
	<?php echo $ajaxPaginator->numbers('reloadUserTable'); ?>
	<?php echo $ajaxPaginator->next('Next', 'reloadUserTable'); ?>
	
	<span class="counter">
		<?php echo $ajaxPaginator->counter(array('format' => 'Trang %page% / %pages%. Tổng cộng %count% tài khoản.')); ?>
	</span>
</div>
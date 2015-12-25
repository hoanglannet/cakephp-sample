<div class="table-caption">Danh sách nhóm người dùng</div>
<table class="grid" cellspacing="0" cellpadding="0" style="width: 100%">
	<thead>
		<tr>
			<th style="width: 8%;">&nbsp;</th>
			<th style="width: 15%;">Tên nhóm</th>
			<th style="width: 15%;">URL chuẩn</th>
			<th style="width: 8%;">&nbsp;</th>
			<th>Phân quyền</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 0;
			foreach($groups as $group): 
			?>
		<tr>
			<td style="text-align: right;">
				<?php
					if ($group['Group']['id'] > 2) {
						echo $this->Html->link(
									$this->Html->image('inline-icon-edit.png', array('alt' => 'Sửa thông tin nhóm')),
									'javascript:editGroup(' . $group['Group']['id'] . ')',
									array('title' => 'Sửa thông tin nhóm', 'escape'=>false)
								);
						echo $this->Html->link(
									$this->Html->image('inline-icon-delete.png', array('alt' => 'Xoá nhóm')),
									'javascript:deleteGroup(' . $group['Group']['id'] . ',"' . $group['Group']['name'] . '")',
									array('title' => 'Xoá nhóm', 'escape'=>false)
								);
					} else {
						echo '<span style="color: #999;">Predifined</span>';
					}
					?>
			</td>
			<td><?php echo $group['Group']['name']; ?></td>
			<td><?php echo '/', $group['Group']['default_controller'], '/', $group['Group']['default_action']; ?></td>
			<td style="text-align: right;">
				<?php 
					if ($group['Group']['id'] > 2) {	
							echo $this->Html->link(
									$this->Html->image('inline-icon-edit.png', array('alt' => 'Sửa phân quyền')),
									'javascript:editGroupPermission(' . $group['Group']['id'] . ')',
									array('title' => 'Sửa phân quyền', 'escape'=>false)
								);
					} else {
						echo '<span style="color: #999;">Predifined</span>';
					}
					?>
			</td>
			<td>
				<?php 
					foreach ($actionsOfGroup[$i]['Aco'] as $actionItem) {
						echo $actionItem['alias'] . "&nbsp;&nbsp;&nbsp;";
					}
						
					$i++;
					?>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<div class="table-footer-2" style="text-align: right;">
	<!-- Shows the page numbers -->
	<?php echo $ajaxPaginator->prev('Prev', 'reloadGroupTable'); ?>
	<?php echo $ajaxPaginator->numbers('reloadGroupTable'); ?>
	<?php echo $ajaxPaginator->next('Next', 'reloadGroupTable'); ?>
	
	<span class="counter">
		<?php echo $ajaxPaginator->counter(array('format' => 'Trang %page% / %pages%. Tổng cộng %count% nhóm.')); ?>
	</span>
</div>
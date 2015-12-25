<script type="text/javascript"><!--
$(function() {
	$('#changePasswordId').click(function() {
		if ($('#passwordId').attr('disabled')) {
			$('#passwordId').attr('disabled', false);
		} else {
			$('#passwordId').attr('disabled', true);
		}
	});		
});
</script>
<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Admin Edit User'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username');
		echo '<div>';
		echo $this->Form->input('password', array('disabled' => true, 'div' => false, 'id' => 'passwordId'));
		echo $this->Html->link('Change password', '#', array('id' => 'changePasswordId', 'style' => 'padding-left:10px;'));
		echo '</div>';
		echo $this->Form->input('email');
		echo $this->Form->input('name');
		echo $this->Form->input('group_id');
		echo $this->Form->input('active', array('label' => 'Enable'));
		echo $this->Form->end(__('Submit', true));
	?>
	</fieldset>
<?php ?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List Users', true), array('action' => 'index'));?></li>
	</ul>
</div>
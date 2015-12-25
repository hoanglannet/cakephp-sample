<div class="users form">
<?php echo $this->Form->create('User');?>
	<fieldset>
 		<legend><?php __('Admin Add User'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('confirm', array('type' => 'password'));
		echo $this->Form->input('email');
		echo $this->Form->input('name');
		echo $this->Form->input('group_id');
		echo $this->Form->input('active', array('label' => 'Enable'));
		echo $this->Form->end(__('Submit', true));
	?>
	</fieldset>

</div>

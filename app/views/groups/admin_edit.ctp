<div class="groups form">
<?php echo $this->Form->create('Group');?>
	<fieldset>
 		<legend><?php __('Admin Edit Group'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('default_controller');
		echo $this->Form->input('default_action');
		echo $this->Form->end(__('Submit', true));
	?>
	</fieldset>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('List Groups', true), array('action' => 'index'));?></li>
	</ul>
</div>
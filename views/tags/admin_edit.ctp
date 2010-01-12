<div class="tags form">
<?php echo $this->Form->create('Tag');?>
	<fieldset>
 		<legend><?php printf(__('Edit %s', true), __('Tag', true)); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('identifier');
		echo $this->Form->input('name', array('readonly' => 'readonly'));
		echo $this->Form->input('keyname');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Tag.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Tag.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Tags', true)), array('action' => 'index'));?></li>
	</ul>
</div>
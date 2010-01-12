<div class="tags form">
<?php echo $this->Form->create('Tag');?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('Tag', true)); ?></legend>
	<?php
		echo $this->Form->input('tags', array('label' => 'Tags (list of tags separated by comma)'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Tags', true)), array('action' => 'index'));?></li>
	</ul>
</div>
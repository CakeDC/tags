<?php
/**
 * CakePHP Tags Plugin
 *
 * Copyright 2009 - 2010, Cake Development Corporation
 *                        1785 E. Sahara Avenue, Suite 490-423
 *                        Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */
?>
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
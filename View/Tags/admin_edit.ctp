<?php
/**
 * Copyright 2009-2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="tags form">
<?php echo $this->Form->create('Tag');?>
	<fieldset>
 		<legend><?php printf(__d('tags', 'Edit %s'), __d('tags', 'Tag')); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('identifier');
		echo $this->Form->input('name', array('readonly' => 'readonly'));
		echo $this->Form->input('keyname');
	?>
	</fieldset>
<?php echo $this->Form->end(__d('tags', 'Submit'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->postLink(__d('tags', 'Delete'), array('action' => 'delete', $this->Form->value('Tag.id')), null, sprintf(__d('tags', 'Are you sure you want to delete # %s?'), $this->Form->value('Tag.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__d('tags', 'List %s'), __d('tags', 'Tags')), array('action' => 'index'));?></li>
	</ul>
</div>
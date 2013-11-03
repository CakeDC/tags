<?php
/**
 * Copyright 2009-2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="tags form">
<?php echo $this->Form->create('Tag');?>
	<fieldset>
 		<legend><?php printf(__d('tags', 'Add %s'), __d('tags', 'Tag')); ?></legend>
	<?php
		echo $this->Form->input('tags', array('label' => 'Tags (list of tags separated by comma)'));
	?>
	</fieldset>
<?php echo $this->Form->end(__d('tags', 'Submit'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(sprintf(__d('tags', 'List %s'), __d('tags', 'Tags')), array('action' => 'index'));?></li>
	</ul>
</div>
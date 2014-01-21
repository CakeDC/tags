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
<div class="tags index">
<h2><?php echo __d('tags', 'Tags');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __d('tags', 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('identifier');?></th>
	<th><?php echo $this->Paginator->sort('name');?></th>
	<th><?php echo $this->Paginator->sort('keyname');?></th>
	<th><?php echo $this->Paginator->sort('weight');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th><?php echo $this->Paginator->sort('modified');?></th>
	<th class="actions"><?php echo __d('tags', 'Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($tags as $tag):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $tag['Tag']['id']; ?>
		</td>
		<td>
			<?php echo $tag['Tag']['identifier']; ?>
		</td>
		<td>
			<?php echo $tag['Tag']['name']; ?>
		</td>
		<td>
			<?php echo $tag['Tag']['keyname']; ?>
		</td>
		<td>
			<?php echo $tag['Tag']['weight']; ?>
		</td>
		<td>
			<?php echo $tag['Tag']['created']; ?>
		</td>
		<td>
			<?php echo $tag['Tag']['modified']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__d('tags', 'View'), array('action' => 'view', $tag['Tag']['keyname'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $this->Paginator->prev('<< '.__d('tags', 'previous'), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $this->Paginator->numbers();?>
	<?php echo $this->Paginator->next(__d('tags', 'next').' >>', array(), null, array('class' => 'disabled'));?>
</div>
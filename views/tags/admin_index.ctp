<div class="tags index">
<h2><?php __('Tags');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
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
	<th class="actions"><?php __('Actions');?></th>
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
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $tag['Tag']['keyname'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $tag['Tag']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $tag['Tag']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $tag['Tag']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $this->Paginator->numbers();?>
	<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Tag', true)), array('action' => 'add')); ?></li>
	</ul>
</div>
Manage tags
===========

The **Tags** plugin is shipped with a TagsController allowing the administrator to perform generic administrative tasks on Tags.

To link these actions, use a classic CakePHP array formatted url:

```php
echo $this->Html->link(__('List Tags'), array(
	'plugin' => 'tags',
	'controller' => 'tags',
	'action' => 'index'
));
```

The available actions are:

* index(),
* view($keyName = null),
* admin_index(),
* admin_view($keyName),
* admin_add(),
* admin_edit($tagId = null),
* admin_delete($id = null)

Note: rename these method names if you use a different admin prefix.
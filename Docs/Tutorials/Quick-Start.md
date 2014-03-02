Qick Start
==========

Make sure the plugin is loaded in the application.

```php
CakePlugin::load('Tags');
// or
CakePlugin::loadAlll();
```

Add the behavior to your model class.

```php
public $actsAs = array(
	'Taggable.Tags'
);
```

In your edit and add form add this input

```php
$this->Form->input('tags');
```

You can now save comma separated tags in this field.

That's all you need if you want to use the plugin out of the box without customization.
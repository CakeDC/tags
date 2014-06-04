Configuration
=============

To turn an existing model into a taggable model, you only need to add the ```Tags.TaggableBehavior``` to it's Behavior list.

```php
public $actsAs = array(
	'Tags.Taggable'
);
```

Once the Behavior is added to the Model it will:

* Create a new HABTM relationship named Tag using the Tagged join class.
* Tag the object with tags contained in the ```Model.tags``` field on each save.
* Note: The tags field can contain several tags separated with a comma.
* Write the comma separated tag list in ```Model.tags``` field on each ```find()``` result.

If you need different settings for the Behavior, check the available options below.

Several TaggableBehavior configurations are customizable. To change a setting, you must add its new value to the $actsAs attribute as detailed below. If you want further information about Behaviors please read the CakePHP Documentation related pages.

After adding the TaggableBehavior to your model, you will need to update your views so the data you save contains a tags field with the related tags (comma separated).

```php
public $actsAs = array(
	'Tags.Taggable' => array(
		'separator' => '',
		'field' => 'tags',
		'tagAlias' => 'Tag',
		'tagClass' => 'Tags.Tag',
		'taggedClass' => 'Tags.Tagged',
		'foreignKey' => 'foreign_key',
		'associationForeignKey' => 'tag_id',
		'automaticTagging' => true,
		'unsetInAfterFind' => false,
		'resetBinding' => false,
	)
);
```

The configuration above contains the default values for each setting. Here are some explanations:

* **separator:** String used to separate tags in the Model.tags value. (Default: ```,```)
* **field:** Name of the Model field containing the tag list. (Default: ```tags```)
* **tagAlias:** Alias for the HABTM relationship between your Model and Tag. (Default: ```Tag```)
* **tagClass**: Name of the model representing Tags. (Default: ```Tags.Tag```)
* **taggedClass:** Name of the HABTM join model. (Default: ```Tags.Tagged```)
* **foreignKey:** Name of the HABTM join model field containing the foreign key to the Tagable model. (Default: ```foreign_key```)
* **associationForeignKey:** Name of the HABTM join model field containing the foreign key to the Tag model. (Default: ```tag_id```)
* **automaticTagging:** Whether or not the behavior will automatically call saveTags() after each save. (Default: ```true```)
* **unsetInAfterFind:** Whether or not the related Tag entries have to be unset after a find. If this value is true, the ```$data['Tag']``` array will be unset and tags will only be available using the ```$data['Model']['tags']``` value. (Default: false)
* **resetBinding:** Value passed as the second param of to the ```bindModel()``` call when creating the HABTM association. If set to true, the binding will last only one query. (Default: false)

Note that the ```tagClass```, ```taggedClass```, ```foreignKey``` and ```associationForeignKey``` values must not be changed if you use the plugin as it is shipped. Use these settings when you want to use your own models / tables structure.

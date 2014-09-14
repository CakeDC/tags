The Tag Cloud Helper
====================

A Tag cloud is a user friendly way to display a tag list to users. The Tags plugin is shipped with a helper permitting to generate a markup easily skinnable using CSS.

Here are the necessary steps for displaying a tag cloud with some basic options.

1. Add the TagCloud helper to your controller ```public $helpers = array('Tags.TagCloud');```
2. In you controller action, get a list of tags and pass it to the view. The Tagged model contains a custom find method ```_findCloud()``` which retrieves all the tags and populates values. Here is an example code from a RecipesController, where Recipe actsAs Taggable: ```$this->set('tags', $this->Recipe->Tagged->find('cloud', array('limit' => 10)));```
3. Call the ```display()``` helper method where you want to display the cloud. For instance:

```php
<ul id="tagcloud">
<?php
	echo $this->TagCloud->display($tags, array(
		'before' => '<li size="%size%" class="tag">',
		'after' => '</li>'
	));
?>
</ul>
```

Will generate a code like:

```html
<ul id="tagcloud">
	<li size="160" class="tag"><a href="/search/index/by:cake" id="tag-4b4d9121-fe58-4fe7-b65a-2f981380bfcb">Cake</a> </li>
	<li size="80" class="tag"><a href="/search/index/by:starter" id="tag-4b4d933d-f578-4d05-b28e-34ae1380bfcb">Starter</a> </li>
	<li size="80" class="tag"><a href="/search/index/by:chocolate" id="tag-4b4d933d-b700-440d-a898-34ae1380bfcb">Chocolate</a> </li>
	<li size="80" class="tag"><a href="/search/index/by:dessert" id="tag-4b4d9121-459c-4abd-af4b-2f981380bfcb">Dessert</a> </li>
</ul>
```

The helper generated links (with an unique DOM id `tag-{id}`) to the *index action of a search controller* in this example, the tag keyname being passed as a named param. The `%size%` string from the before param was replaced with a number based on the tag weight.

Available Options of the TagCloud helper
----------------------------------------

The second param for the ```display()``` method is an array of options. The available keys are:

* **shuffle:** true to shuffle the tag list, false to display them in the same order than passed. (Default: ```true```)
* **extract:** Set::extract() compatible format string. Path to extract weight values from the $tags array passed (first param). (Default: ```{n}.Tag.weight```)
* **before:** string to be displayed before each generated link. %size% will be replaced with tag size calculated from the weight. (Default: ```empty```)
* **after:** string to be displayed after each generated link. %size% will be replaced with tag size calculated from the weight. (Default: ```empty```)
* **maxSize:** size of the heaviest tag. (Default: ```160```)
* **minSize:** size of the lightest tag. (Default: ```80```)
* **url:** an array containing the default url. (Default: ```array('controller' => 'search'```))
* **named**: the named parameter used to send the tag keyname. (Default: ```by```)
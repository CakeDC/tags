Find Tagged Objects
===================

The Tagged model has a custom ```_findTagged()``` method to find or paginate objects tagged with a given tag.

Find usage examples
-------------------

* To find all Articles having at least one Tag the call would be: ```$this->Tagged->find('tagged', array('model' => 'Article'));```
* To find all Articles tagged _cakephp_ the call would be: ```$this->Tagged->find('tagged', array('by' => 'cakephp', 'model' => 'Article'));```

Pagination usage example
------------------------

You can also use this custom find method with paginated results. It is expected that you know how CakePHPs ```Model::find()``` and costume find methods work.

Below is a complete example of using the ```_findTagged``` method with pagination to filter elements by tag:

```php
public function index() {
	if (isset($this->passedArgs['by'])) {
		$this->paginate['Tagged'] = array(
			'tagged',
			'model' => 'Recipe',
			'by' => $this->passedArgs['by']);
		$recipes = $this->paginate('Tagged');
	} else {
		$this->Recipe->recursive = 1;
		$recipes = $this->paginate();
	}
	$this->set('recipes', $recipes);
	$this->set('tags', $this->Recipe->Tagged->find('cloud', array('limit' => 10)));
}
```

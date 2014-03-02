Home
====

The tags plugin includes the Taggable Behavior that allows you to simply tag everything.

It saves all tags in a tags table and connects any kind of records to them through the tagged table.

Requirements
------------

* PHP version: PHP 5.2+
* CakePHP version: 1.3 Stable

Documentation
-------------

* [Overview](Documentation/Overview.md)
* [Installation](Documentation/Installation.md)
* [Configuration](Documentation/Configuration.md)

Tutorials
---------

* [Quick Start](Tutorials/Quick-Start.md)


public function createTeam($postData) {
	$this->set($postData);
	$this->User->set($postData);

	if ($this->validates() && $this->User->validates()) {
		$this->create();
		$this->save($postData, array('validate' => false);
		$this->User->create();
		$this->User->save($postData, array('validate' => false);
		$this->TeamsUser->create();
		$this->TeamsUser->save(array(
			'TeamsUser => array(
				'user_id' => $this->getLastInsertId()
				'team_id' => $this->User->getLastInsertId()
			)
		));
		return true;
	}
	return false;
}
# Tags Plugin for CakePHP #

Version 1.2

The tags plugin includes the Taggable Behavior that allows you to simply tag everything.

It saves all tags in a tags table and connects any kind of records to them through the tagged table.

To create tables you can use migrations plugin or schema shell. To create tables execute:

cake schema create --plugin Tags --name tags

You can specify alternate tables for both in the case you get *A LOT* records tagged.

## Usage ##

To make something taggable you just need to do two things:

* Attach the taggable behavior
* Add a 'tags' field into your view for the model you just made taggable

The taggable behavior can be configured using the following parameters

* separator             - separator used to enter a lot of tags, comma by default
* tagAlias              - model alias for Tag model
* tagClass              - class name of the model storing the tags
* taggedClass           - class name of the HABTM association table between tags and models
* field                 - the field name that contains the raw tags as string
* foreignKey            - foreignKey used in the HABTM association
* associationForeignKey - associationForeignKey used in the HABTM association
* automaticTagging      - if set to true you don't need to use saveTags() manually
* language              - only tags in a certain language, string or array
* taggedCounter         - true to update the number of times a particular tag was used for a specific record

The Tagged model contains a method _findCloud which can be used like any other find $this->Model->find('cloud', $options);

The result contains a "weight" field which has a normalized size of the tag occurrence set. The min and max size can be set by passing 'minSize" and 'maxSize' to the query. This value can be used in the view to control the size of the tag font.

Tags can contain special tokens called "identifiers" to namespace tags or classify them into categories.

A valid tags string to be saved is "foo, bar, cakephp:special".

The token "cakephp" will end up as the identifier or category for the tag "special"

## Requirements ##

* PHP version: PHP 5.2+
* CakePHP version: 1.3 Stable

## Support ##

For support and feature request, please visit the [Tags Plugin Support Site](http://cakedc.lighthouseapp.com/projects/59622-tags-plugin/).

For more information about our Professional CakePHP Services please visit the [Cake Development Corporation website](http://cakedc.com).

## License ##

Copyright 2009-2012, [Cake Development Corporation](http://cakedc.com)

Licensed under [The MIT License](http://www.opensource.org/licenses/mit-license.php)<br/>
Redistributions of files must retain the above copyright notice.

## Copyright ###

Copyright 2009-2011<br/>
[Cake Development Corporation](http://cakedc.com)<br/>
1785 E. Sahara Avenue, Suite 490-423<br/>
Las Vegas, Nevada 89104<br/>
http://cakedc.com<br/>

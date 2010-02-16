Copyright 2009 - 2010, Cake Development Corporation
                        1785 E. Sahara Avenue, Suite 490-423
                        Las Vegas, Nevada 89104
                        http://cakedc.com

Tags Plugin

The tags plugin includes the Tagable Behavior that allows you to simply tag
everything. It saves all tags in a tags table and connects any kind of records
to them through the tagged table. You can specify alternate tables for both in
the case you get *A LOT* records tagged.


To make something tagable you just need to do two things:
 * Attach the tagable behavor
 * Add a 'tags' field into your view for the model you just made tagable


The tagable behavior can be configured using the following parameters
 * separator				- sperator used to enter a lot of tags, comma by default
 * tagAlias					- model alias for Tag model
 * tagClass					- class name of the model storing the tags
 * taggedClass				- class name of the habtm association table between tags and models
 * field					- the fieldname that contains the raw tags as string
 * foreignKey				- foreignKey used in the HABTM association
 * associationForeignKey	- associationForeignKey used in the HABTM association
 * automaticTagging			- if set to true you dont need to use saveTags() manualy
 * language					- only tages in a certain language, string or array


The Tagged model contains a method _findCloud which can be used like any other
find $this->Model->find('cloud', $options);

The result contains a "weight" field which has a normalized size of the tag
occurrence set. The min and max size can be set by passing 'minSize" and
'maxSize' to the query. This value can be used in the view to control the size 
of the tag font.
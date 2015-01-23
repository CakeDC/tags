<?php
namespace Tags\Model\Entity;

use Cake\ORM\Entity;

/**
 * Tag Entity.
 */
class Tagged extends Entity
{

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
    protected $_accessible = [
        'foreign_key' => true,
        'tag_id' => true,
        'model' => true,
        'language' => true,
        'times_tagged' => true,
        'created' => true,
        'modified' => true,
    ];
}

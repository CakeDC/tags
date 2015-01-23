<?php
namespace Tags\Model\Entity;

use Cake\ORM\Entity;

/**
 * Tag Entity.
 */
class Tag extends Entity
{

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
    protected $_accessible = [
        'identifier' => true,
        'name' => true,
        'keyname' => true,
        'weight' => true,
        'occurrence' => true,
        'created' => true,
        'modified' => true,
    ];
}

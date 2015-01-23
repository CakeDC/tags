<?php
/**
 * Copyright 2009-2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace Tags\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArticleFixture
 *
 * @package tags
 * @subpackage tags.tests.fixtures
 */
class ArticleFixture extends TestFixture
{

/**
 * fields property
 *
 * @var array
 */
    public $fields = [
        'id' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 36],
        'title' => ['type' => 'string', 'null' => false],
        'user_id' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 36],
        '_constraints' => [
            'PRIMARY' => ['type' => 'primary', 'columns' => ['id']],
        ]
    ];

/**
 * records property
 *
 * @var array
 */
    public $records = [
        [
            'id' => 'article-1',
            'title' => 'First Article',
            'user_id' => 'user-1'
        ],
        [
            'id' => 'article-2',
            'title' => 'Second Article',
            'user_id' => 'user-2'
        ],
        [
            'id' => 'article-3',
            'title' => 'Third Article',
            'user_id' => 'user-3'
        ]
    ];
}

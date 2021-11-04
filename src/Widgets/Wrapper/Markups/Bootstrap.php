<?php

namespace Ht7\Html\Widgets\Wrapper\Markups;

use \Ht7\Base\Enum;

/**
 *
 */
class Bootstrap extends Enum
{

    const ROW_SIMPLE_V3_12_6 = [
        'tag' => 'div',
        'attributes' => ['class' => 'container'],
        'content' => [
            [
                'tag' => 'div',
                'attributes' => ['class' => 'col-xs-12 col-sm-6'],
                'content' => [
                    [
                        'type' => 'replacer',
                        'id' => 'left-col'
                    ],
                ],
            ],
            [
                'tag' => 'div',
                'attributes' => ['class' => 'col-xs-12 col-sm-6'],
                'content' => [
                    [
                        'type' => 'replacer',
                        'id' => 'right-col'
                    ],
                ],
            ],
        ],
    ];
    const ROW_SIMPLE_V3_12_6_4 = [
        'tag' => 'div',
        'attributes' => ['class' => 'container'],
        'content' => [
            [
                'tag' => 'div',
                'attributes' => ['class' => 'col-xs-12 col-sm-6 col-md-4'],
                'content' => [
                    [
                        'type' => 'replacer',
                        'id' => 'left-col'
                    ],
                ],
            ],
            [
                'tag' => 'div',
                'attributes' => ['class' => 'col-xs-12 col-sm-6 col-md-8'],
                'content' => [
                    [
                        'type' => 'replacer',
                        'id' => 'right-col'
                    ],
                ],
            ],
        ],
    ];

}

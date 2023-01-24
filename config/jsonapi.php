<?php

declare(strict_types=1);

return [
    'resources' => [
        'columns' => [
            'allowedSorts' => [],
            'allowedIncludes' => [
                'cards',
            ],
            'allowedFilters' => [],
            'relationships' => [
                [
                    'type' => 'cards',
                    'method' => 'cards',
                ]
            ]
        ],
        'cards' => [
            'allowedSorts' => [
                'order'
            ],
            'allowedIncludes' => [],
            'allowedFilters' => [
                'date',
                'status',
            ],
            'relationships' => [
                []
            ]
        ],
    ]
];

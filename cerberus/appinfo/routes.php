<?php

return [
    'routes' => [
        [
            'name' => 'permission#checkPermissions',
            'url' => '/check-permission/{fileName}',
            'verb' => 'GET',
            'requirements' => ['fileName' => '.+'],
            'defaults' => []
        ],
        [
            'name' => 'permission#listFiles',
            'url' => '/list-files',
            'verb' => 'GET'
        ]
    ]
];

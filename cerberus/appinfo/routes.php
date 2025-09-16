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
        ],
        [
            'name' => 'test#hello',
            'url' => '/hello',
            'verb' => 'GET'
        ],
        [
            'name' => 'user#getUsersAndGroups',
            'url' => '/users/all',
            'verb' => 'GET'
        ],
        [
            'name' => 'user#getUsers',
            'url' => '/users/user-list',
            'verb' => 'GET'
        ],
        [
            'name' => 'user#getGroups',
            'url' => '/users/group-list',
            'verb' => 'GET'
        ],
        [
            'name' => 'user#getGroupsOfASpecificUser',
            'url' => '/users/get-groups',
            'verb' => 'GET'
        ],
        [
            'name' => 'file#getFile',
            'url' => '/permissions/file',
            'verb' => 'GET'
        ],
        [
            'name' => 'file#getGroup',
            'url' => '/permissions/group',
            'verb' => 'GET'
        ], 
        [
            'name' => 'file#getFileWhenNotShared',
            'url' => '/permissions/file-unshared',
            'verb' => 'GET'
        ]
    ]
];

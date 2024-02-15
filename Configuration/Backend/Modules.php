<?php

return [
    'web_person_manager' => [
        'parent' => 'web',
        'position' => [],
        'access' => 'user',
        'iconIdentifier' => 'personmanager-icon',
        'labels' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_personmanagerback.xlf',
        'path' => '/module/web/personmanager',
        'extensionName' => 'PersonManager',
        'target' => [
            '_default' => \Personmanager\PersonManager\Controller\BackendController::class . '::listAction',
        ],
        'controllerActions' => [
            \Personmanager\PersonManager\Controller\BackendController::class =>
                'list, newImport, new, createBe, import, export, newExport, insertData, clear, loglist, blNewImport, blImport, blClear',
        ],
    ],
    'person_manager_list' => [
        'parent' => 'web_person_manager',
        'position' => [],
        'access' => 'user',
        'iconIdentifier' => 'personmanager-icon',
        'labels' => [
            'title' => 'List',
        ],
        'extensionName' => 'PersonManager',
        'path' => '/module/personmanager/list',
        'controllerActions' => [
            \Personmanager\PersonManager\Controller\BackendController::class =>
                'list',
        ],
    ],
    'person_manager_import' => [
        'parent' => 'web_person_manager',
        'position' => [],
        'access' => 'user',
        'iconIdentifier' => 'personmanager-icon',
        'labels' => [
            'title' => 'Import',
        ],
        'extensionName' => 'PersonManager',
        'path' => '/module/personmanager/import',
        'controllerActions' => [
            \Personmanager\PersonManager\Controller\BackendController::class =>
                'newImport, import, clear',
        ],
    ],
    'person_manager_export' => [
        'parent' => 'web_person_manager',
        'position' => [],
        'access' => 'user',
        'iconIdentifier' => 'personmanager-icon',
        'labels' => [
            'title' => 'Export',
        ],
        'extensionName' => 'PersonManager',
        'path' => '/module/personmanager/export',
        'controllerActions' => [
            \Personmanager\PersonManager\Controller\BackendController::class =>
                'newExport, export',
        ],
    ],
    'person_manager_log' => [
        'parent' => 'web_person_manager',
        'position' => [],
        'access' => 'user',
        'iconIdentifier' => 'personmanager-icon',
        'labels' => [
            'title' => 'Log',
        ],
        'extensionName' => 'PersonManager',
        'path' => '/module/personmanager/log',
        'controllerActions' => [
            \Personmanager\PersonManager\Controller\BackendController::class =>
                'loglist',
        ],
    ],
    'person_manager_blacklist' => [
        'parent' => 'web_person_manager',
        'position' => [],
        'access' => 'user',
        'iconIdentifier' => 'personmanager-icon',
        'labels' => [
            'title' => 'Blacklist',
        ],
        'extensionName' => 'PersonManager',
        'path' => '/module/personmanager/blacklist',
        'controllerActions' => [
            \Personmanager\PersonManager\Controller\BackendController::class =>
                'blNewImport, blImport, blClear',
        ],
    ],
];

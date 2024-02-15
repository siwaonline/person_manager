<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'PersonManager',
    'Personmanagerfront',
    [
        \Personmanager\PersonManager\Controller\PersonController::class => 'new, create, activate, unsubscribe,isunsubscribed,text',

    ],
    // non-cacheable actions
    [
        \Personmanager\PersonManager\Controller\PersonController::class => 'new, create, activate, unsubscribe,isunsubscribed,text',

    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'PersonManager',
    'Personmanagerunsub',
    [
        \Personmanager\PersonManager\Controller\PersonController::class => 'newLeave, leave, unsubscribe, text',

    ],
    // non-cacheable actions
    [
        \Personmanager\PersonManager\Controller\PersonController::class => 'newLeave, leave, unsubscribe, text',

    ]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'PersonManager',
    'Personmanagershort',
    [
        \Personmanager\PersonManager\Controller\PersonController::class => 'newShort',

    ],
    // non-cacheable actions
    [
        \Personmanager\PersonManager\Controller\PersonController::class => 'newShort',

    ]
);

// wizards
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig("@import 'EXT:person_manager/Configuration/PageTS/*.typoscript'");

// Mail API
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Layouts';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Templates';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Partials';

<?php

use Personmanager\PersonManager\Controller\PersonController;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3')) {
    die('Access denied.');
}

ExtensionUtility::configurePlugin(
    'PersonManager',
    'Personmanagerfront',
    [
        PersonController::class => 'new, create, activate, unsubscribe,isunsubscribed,text',

    ],
    // non-cacheable actions
    [
        PersonController::class => 'new, create, activate, unsubscribe,isunsubscribed,text',

    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'PersonManager',
    'Personmanagerunsub',
    [
        PersonController::class => 'newLeave, leave, unsubscribe, text',

    ],
    // non-cacheable actions
    [
        PersonController::class => 'newLeave, leave, unsubscribe, text',

    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'PersonManager',
    'Personmanagershort',
    [
        PersonController::class => 'newShort',

    ],
    // non-cacheable actions
    [
        PersonController::class => 'newShort',

    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

// Mail API
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Layouts';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Templates';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Partials';

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconRegistry->registerIcon('personmanager-icon', SvgIconProvider::class, ['source' => 'EXT:person_manager/Resources/Public/Icons/Extension.svg']);

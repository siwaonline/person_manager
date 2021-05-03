<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.person_manager',
	'Personmanagerfront',
	[
		'Person' => 'new, create, activate, unsubscribe,isunsubscribed,text',

	],
	// non-cacheable actions
	[
		'Person' => 'new, create, activate, unsubscribe,isunsubscribed,text',

	]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.person_manager',
	'Personmanagerunsub',
	[
		'Person' => 'newLeave, leave, unsubscribe, text',

	],
	// non-cacheable actions
	[
		'Person' => 'newLeave, leave, unsubscribe, text',

	]
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.person_manager',
	'Personmanagershort',
	[
		'Person' => 'newShort',

	],
	// non-cacheable actions
	[
		'Person' => 'newShort',

	]
);

// wizards
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig("@import 'EXT:person_manager/Configuration/PageTS/*.typoscript'");


// Mail API
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Layouts';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Templates';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths'][500] = 'EXT:person_manager/Resources/Private/Mail/Partials';
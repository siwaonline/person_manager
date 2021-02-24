<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.person_manager',
	'Personmanagerfront',
	array(
		'Person' => 'new, create, activate, unsubscribe,isunsubscribed,text',

	),
	// non-cacheable actions
	array(
		'Person' => 'new, create, activate, unsubscribe,isunsubscribed,text',

	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.person_manager',
	'Personmanagerunsub',
	array(
		'Person' => 'newLeave, leave, unsubscribe, text',

	),
	// non-cacheable actions
	array(
		'Person' => 'newLeave, leave, unsubscribe, text',

	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.person_manager',
	'Personmanagershort',
	array(
		'Person' => 'newShort',

	),
	// non-cacheable actions
	array(
		'Person' => 'newShort',

	)
);

	// wizards
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig("@import 'EXT:person_manager/Configuration/PageTS/*.typoscript'");

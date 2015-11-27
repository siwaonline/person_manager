<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.' . $_EXTKEY,
	'Personmanagerfront',
	array(
		'Person' => 'new, create, activate, unsubscribe,isunsubscribed,text',

	),
	// non-cacheable actions
	array(
		'Person' => 'create, activate, unsubscribe,isunsubscribed,text',

	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.' . $_EXTKEY,
	'Personmanagerunsub',
	array(
		'Person' => 'newLeave, leave, unsubscribe, text',

	),
	// non-cacheable actions
	array(
		'Person' => 'newLeave, leave, unsubscribe, text',

	)
);

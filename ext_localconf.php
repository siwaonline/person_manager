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
		'Person' => 'new, create, activate, unsubscribe,isunsubscribed,text',

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

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Personmanager.' . $_EXTKEY,
	'Personmanagershort',
	array(
		'Person' => 'newShort',

	),
	// non-cacheable actions
	array(
		'Person' => 'newShort',

	)
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]  =  \Personmanager\PersonManager\Hooks\PersonProcessDatamap::class;
<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Personmanagerfront',
	'Person Manager Registration'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Personmanagerunsub',
	'Person Manager Deregistration'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Personmanagershort',
	'Person Manager Short Registration'
);


if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Personmanager.' . $_EXTKEY,
		'web',     // Make module a submodule of 'web'
		'personmanagerback',    // Submodule key
		'',                        // Position
		array(
			'Person' => 'list, newImport, show, new, createBe, edit, update, delete, import, export, newExport, insertData, clear, loglist, blNewImport, blImport, blClear',
		),
		array(
			'access' => 'user,group',
			'icon' => 'EXT:person_manager/ext_icon.png',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_personmanagerback.xlf',
		)
	);

}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Person Manager');

$pluginSignature = str_replace('_', '', $_EXTKEY) . '_personmanagerfront';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Flexform.xml');

$pluginSignature = str_replace('_', '', $_EXTKEY) . '_personmanagerunsub';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Flexform2.xml');

$pluginSignature = str_replace('_', '', $_EXTKEY) . '_personmanagershort';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Flexform3.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_person', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_person.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_person');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_category', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_category.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_category');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_log', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_log.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_log');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_blacklist', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_blacklist.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_blacklist');

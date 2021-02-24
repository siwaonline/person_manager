<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'person_manager',
	'Personmanagerfront',
	'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager.Personmanagerfront'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'person_manager',
	'Personmanagerunsub',
	'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager.Personmanagerunsub'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'person_manager',
	'Personmanagershort',
	'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager.Personmanagershort'
);


if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Personmanager.PersonManager',
		'web',     // Make module a submodule of 'web'
		'personmanagerback',    // Submodule key
		'',                        // Position
		array(
			'Backend' => 'list, newImport, new, createBe, import, export, newExport, insertData, clear, loglist, blNewImport, blImport, blClear',
		),
		array(
			'access' => 'user,group',
			'icon' => 'EXT:person_manager/ext_icon.png',
			'labels' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_personmanagerback.xlf',
		)
	);

}

//$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Imaging\\IconRegistry');
//$iconRegistry->registerIcon('extensions-person-manager', 'TYPO3\\CMS\\Core\\Imaging\\IconProvider\\BitmapIconProvider', array("source" => 'EXT:person_manager/ext_icon.svg',));


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('person_manager', 'Configuration/TypoScript', 'Person Manager');

$pluginSignature = str_replace('_', '', 'person_manager') . '_personmanagerfront';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:person_manager/Configuration/Flexforms/Flexform.xml');

$pluginSignature = str_replace('_', '', 'person_manager') . '_personmanagerunsub';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:person_manager/Configuration/Flexforms/Flexform2.xml');

$pluginSignature = str_replace('_', '', 'person_manager') . '_personmanagershort';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:person_manager/Configuration/Flexforms/Flexform3.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_person', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_person.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_person');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_category', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_category.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_category');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_log', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_log.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_log');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_blacklist', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_blacklist.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_blacklist');

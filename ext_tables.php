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
			'icon' => 'EXT:person_manager/ext_icon.' . (\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0') ? 'png' : 'gif'),
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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_person', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_person.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_person');
if(\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0')) {
}else{
	$GLOBALS['TCA']['tx_personmanager_domain_model_person'] = array(
		'ctrl' => array(
			'title' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person',
			'label' => 'email',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'cruser_id' => 'cruser_id',
			'dividers2tabs' => TRUE,

			'versioningWS' => 2,
			'versioning_followPages' => TRUE,

			'languageField' => 'sys_language_uid',
			'transOrigPointerField' => 'l10n_parent',
			'transOrigDiffSourceField' => 'l10n_diffsource',
			'delete' => 'deleted',
			'enablecolumns' => array(
				'disabled' => 'hidden',
				'starttime' => 'starttime',
				'endtime' => 'endtime',
			),
			'searchFields' => 'firstname,lastname,email,salutation,titel,nachgtitel,geb,tel,company,active,confirmed,unsubscribed,token,frtxt0,frtxt1,frtxt2,frtxt3,frtxt4,frtxt5,frtxt6,frtxt7,frtxt8,frtxt9,category,',
			'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Person.php',
			'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('person_manager') . 'Resources/Public/Icons/tx_personmanager_domain_model_person.gif'
		),
	);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_category', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_category.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_category');
if(\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0')) {
}else{
	$GLOBALS['TCA']['tx_personmanager_domain_model_category'] = array(
		'ctrl' => array(
			'title' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_category',
			'label' => 'name',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'cruser_id' => 'cruser_id',
			'dividers2tabs' => TRUE,

			'versioningWS' => 2,
			'versioning_followPages' => TRUE,

			'languageField' => 'sys_language_uid',
			'transOrigPointerField' => 'l10n_parent',
			'transOrigDiffSourceField' => 'l10n_diffsource',
			'delete' => 'deleted',
			'enablecolumns' => array(
				'disabled' => 'hidden',
				'starttime' => 'starttime',
				'endtime' => 'endtime',
			),
			'searchFields' => 'name,',
			'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Category.php',
			'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_personmanager_domain_model_category.gif'
		),
	);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_log', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_log.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_log');
if(\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0')) {
}else{
	$GLOBALS['TCA']['tx_personmanager_domain_model_log'] = array(
		'ctrl' => array(
			'title' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log',
			'label' => 'email',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'cruser_id' => 'cruser_id',
			'dividers2tabs' => TRUE,

			'versioningWS' => 2,
			'versioning_followPages' => TRUE,

			'languageField' => 'sys_language_uid',
			'transOrigPointerField' => 'l10n_parent',
			'transOrigDiffSourceField' => 'l10n_diffsource',
			'delete' => 'deleted',
			'enablecolumns' => array(
				'disabled' => 'hidden',
				'starttime' => 'starttime',
				'endtime' => 'endtime',
			),
			'searchFields' => 'email,',
			'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Log.php',
			'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_personmanager_domain_model_log.gif'
		),
	);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_blacklist', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_blacklist.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_blacklist');
if(\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0')) {
}else{
	$GLOBALS['TCA']['tx_personmanager_domain_model_blacklist'] = array(
		'ctrl' => array(
			'title' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_blacklist',
			'label' => 'email',
			'tstamp' => 'tstamp',
			'crdate' => 'crdate',
			'cruser_id' => 'cruser_id',
			'dividers2tabs' => TRUE,

			'versioningWS' => 2,
			'versioning_followPages' => TRUE,

			'languageField' => 'sys_language_uid',
			'transOrigPointerField' => 'l10n_parent',
			'transOrigDiffSourceField' => 'l10n_diffsource',
			'delete' => 'deleted',
			'enablecolumns' => array(
				'disabled' => 'hidden',
				'starttime' => 'starttime',
				'endtime' => 'endtime',
			),
			'searchFields' => 'email,',
			'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Blacklist.php',
			'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_personmanager_domain_model_blacklist.gif'
		),
	);
}

<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


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


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_person', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_person.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_person');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_category', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_category.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_category');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_log', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_log.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_log');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_personmanager_domain_model_blacklist', 'EXT:person_manager/Resources/Private/Language/locallang_csh_tx_personmanager_domain_model_blacklist.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_personmanager_domain_model_blacklist');

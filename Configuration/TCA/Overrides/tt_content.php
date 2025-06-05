<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'PersonManager',
    'Personmanagerfront',
    'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager.Personmanagerfront',
    'personmanager-icon',
    'PersonManager'
);
ExtensionUtility::registerPlugin(
    'PersonManager',
    'Personmanagerunsub',
    'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager.Personmanagerunsub',
    'personmanager-icon',
    'PersonManager'
);
ExtensionUtility::registerPlugin(
    'PersonManager',
    'Personmanagershort',
    'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager.Personmanagershort',
    'personmanager-icon',
    'PersonManager'
);

$pluginSignature = str_replace('_', '', 'person_manager') . '_personmanagerfront';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:person_manager/Configuration/Flexforms/Flexform.xml');

$pluginSignature = str_replace('_', '', 'person_manager') . '_personmanagerunsub';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:person_manager/Configuration/Flexforms/Flexform2.xml');

$pluginSignature = str_replace('_', '', 'person_manager') . '_personmanagershort';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:person_manager/Configuration/Flexforms/Flexform3.xml');

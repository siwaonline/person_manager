<?php

return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_blacklist',
        'label' => 'email',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'email',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('person_manager') . 'Resources/Public/Icons/tx_personmanager_domain_model_blacklist.gif',
    ],
    'types' => [
        '1' => [
            'showitem' => 'crdate, hidden, email,
				--div--;LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tabs.language,
					--palette--;;language,
				--div--;LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tabs.timeRestrictions,
					--palette--;;timeRestrictions',
        ],
    ],
    'palettes' => [
        'timeRestrictions' => [ 'showitem' => 'starttime, endtime'],
        'language' => [ 'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'config' => [
                'type' => 'language'
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_personmanager_domain_model_blacklist',
                'foreign_table_where' => 'AND tx_personmanager_domain_model_blacklist.pid=###CURRENT_PID### AND tx_personmanager_domain_model_blacklist.sys_language_uid IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        'label' => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'email' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_blacklist.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim, email',
            ],
        ],
    ],
];

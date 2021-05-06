<?php
return [
	'ctrl' => [
		'title'	=> 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log',
		'label' => 'email',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		],
		'searchFields' => 'email, firstname, lastname, person, fehler',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath("person_manager") . 'Resources/Public/Icons/tx_personmanager_domain_model_log.gif'
	],
	'types' => [
		'1' => [
			'showitem' => 'crdate, hidden, action, email, firstname, lastname, person, fehler, success,
			--div--;LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tabs.language,
				--palette--;;language,
			--div--;LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tabs.timeRestrictions,
				--palette--;;timeRestrictions'
		],
	],
	'palettes' => [
		'timeRestrictions' => [ 'showitem' => 'starttime, endtime'],
		'language' => [ 'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource']
	],
	'columns' => [
		'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_personmanager_domain_model_log',
                'foreign_table_where' => 'AND tx_personmanager_domain_model_log.pid=###CURRENT_PID### AND tx_personmanager_domain_model_log.sys_language_uid IN (-1,0)',
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
                        0 => '',
                        1 => '',
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
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
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
		'action' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log.action',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'detail' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log.detail',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'email' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log.email',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim, email'
			],
		],
		'firstname' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log.firstname',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'lastname' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log.lastname',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'person' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log.person',
			'config' => [
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			],
		],
		'fehler' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log.fehler',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'success' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_log.success',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
            ],
		],
	],
];

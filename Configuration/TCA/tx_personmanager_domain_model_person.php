<?php
return [
	'ctrl' => [
		'title'	=> 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person',
		'label' => 'email',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'delete' => 'deleted',
        'languageField' => 'sys_language_uid',
        'enablecolumns' => [
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		],
		'searchFields' => 'firstname,lastname,email,salutation,titel,nachgtitel,geb,tel,company,active,confirmed,unsubscribed,token,frtxt0,frtxt1,frtxt2,frtxt3,frtxt4,frtxt5,frtxt6,frtxt7,frtxt8,frtxt9,category,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('person_manager') . 'Resources/Public/Icons/tx_personmanager_domain_model_person.gif'
	],
	'types' => [
		'1' => [
			'showitem' => 'crdate, hidden,
				--div--;LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tabs.personalData,
					--palette--;;personalData,
				--div--;LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tabs.freeText,
					--palette--;;freeText,
				--div--;LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tabs.language,
					--palette--;;language,
				--div--;LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tabs.timeRestrictions,
					--palette--;;timeRestrictions'
		]
	],
	'palettes' => [
		'personalData' => [ 'showitem' => '--div--;,firstname, lastname,
		--linebreak--,email, salutation, titel, nachgtitel,
		--linebreak--,geb, tel, company,
		--linebreak--,active, confirmed, unsubscribed,
		--linebreak--,token,
		--linebreak--,category'],
		'freeText' => [ 'showitem' => 'frtxt0,
		--linebreak--,frtxt1,
		--linebreak--,frtxt2,
		--linebreak--,frtxt3,
		--linebreak--,frtxt4,
		--linebreak--,frtxt5,
		--linebreak--,frtxt6,
		--linebreak--,frtxt7,
		--linebreak--,frtxt8,
		--linebreak--,frtxt9'],
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
                'foreign_table' => 'tx_personmanager_domain_model_person',
                'foreign_table_where' => 'AND tx_personmanager_domain_model_person.pid=###CURRENT_PID### AND tx_personmanager_domain_model_person.sys_language_uid IN (-1,0)',
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
		'firstname' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.firstname',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'lastname' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.lastname',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'email' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.email',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim, email'
			],
		],
		'salutation' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.salutation',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:person_manager/Resources/Private/Language/locallang.xlf:labels.mrmrs', 0],
					['LLL:EXT:person_manager/Resources/Private/Language/locallang.xlf:labels.mr', 1],
					['LLL:EXT:person_manager/Resources/Private/Language/locallang.xlf:labels.mrs', 2],
				],
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			],
		],
		'titel' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.titel',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'nachgtitel' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.nachgtitel',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'geb' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.geb',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'tel' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.tel',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'company' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.company',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'active' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.active',
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
		'confirmed' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.confirmed',
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
		'unsubscribed' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.unsubscribed',
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
		'token' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.token',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'category' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.category',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_personmanager_domain_model_category',
				'minitems' => 0,
				'maxitems' => 1,
			],
		],
		'frtxt0' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt0',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt1' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt1',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt2' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt2',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt3' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt3',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt4' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt4',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt5' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt5',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt6' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt6',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt7' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt7',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt8' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt8',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'frtxt9' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:person_manager/Resources/Private/Language/locallang_db.xlf:tx_personmanager_domain_model_person.frtxt9',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
	],
];

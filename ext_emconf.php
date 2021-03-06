<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "person_manager"
 *
 * Auto generated by Extension Builder 2015-02-09
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Person Manager',
	'description' => 'A TYPO3 extension to manage user/subscriber data. Works perfectly with the extension newsletter.',
	'category' => 'plugin',
	'author' => 'Philipp Parzer',
	'author_email' => 'https://forge.typo3.org/projects/extension-person_manager',
	'author_company' => 'SIWA Online GmbH',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '1',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '4.0.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '10.4.0-10.4.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'newsletter' => ''
		),
	),
);

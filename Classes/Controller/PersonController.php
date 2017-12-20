<?php
namespace Personmanager\PersonManager\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * PersonController
 */
class PersonController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * personRepository
	 *
	 * @var \Personmanager\PersonManager\Domain\Repository\PersonRepository
	 * @inject
	 */
	protected $personRepository = NULL;

	/**
	 * categoryRepository
	 *
	 * @var \Personmanager\PersonManager\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository = NULL;

	/**
	 * logRepository
	 *
	 * @var \Personmanager\PersonManager\Domain\Repository\LogRepository
	 * @inject
	 */
	protected $logRepository = NULL;

	/**
	 * blacklistRepository
	 *
	 * @var \Personmanager\PersonManager\Domain\Repository\BlacklistRepository
	 * @inject
	 */
	protected $blacklistRepository = NULL;


	public $signature = "";
	public $sitename = "";

	public $flexcheckmail = "";
	public $flexconfirm = "";
	public $flexerr = "";

	public $flexleave = "";
	public $flexisunsubscribed = "";
	public $flexcheckmailleave = "";
	public $flexunsubscribe = "";

	public $newIcons = 0;

	public function initializeAction(){
		$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.notext','person_manager');

		$this->signature = $this->configurationManager->getContentObject()->parseFunc($this->settings['flexsignature'], array(), '< lib.parseFunc_RTE');
		$this->sitename = $this->settings['flexsitename'];

		$this->flexcheckmail = $this->settings['flexcheckmail'];
		if($this->flexcheckmail == NULL || $this->flexcheckmail == "")$this->flexcheckmail = $langhelp;
		$this->flexconfirm = $this->settings['flexconfirm'];
		if($this->flexconfirm == NULL || $this->flexconfirm == "")$this->flexconfirm = $langhelp;
		$this->flexerr = $this->settings['flexerr'];
		if($this->flexerr == NULL || $this->flexerr == "")$this->flexerr = $langhelp;

		$this->flexcheckmailleave = $this->settings['flexcheckmailleave'];
		if($this->flexcheckmailleave == NULL || $this->flexcheckmailleave == "")$this->flexcheckmailleave = $langhelp;
		$this->flexisunsubscribed = $this->settings['flexisunsubscribed'];
		if($this->flexisunsubscribed == NULL || $this->flexisunsubscribed == "")$this->flexisunsubscribed = $langhelp;
		$this->flexleave = $this->settings['flexleave'];
		if($this->flexleave == NULL || $this->flexleave == "")$this->flexleave = $langhelp;
		$this->flexunsubscribe = $this->settings['flexunsubscribe'];
		if($this->flexunsubscribe == NULL || $this->flexunsubscribe == "")$this->flexunsubscribe = $langhelp;
		if(\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0')) {
			$this->newIcons = 1;
		}
	}
	public function initializeUpdateAction(){
		$propertyMappingConfiguration = $this->arguments['person']->getPropertyMappingConfiguration();
		$propertyMappingConfiguration->allowAllProperties('category');
	}

	/**
	 * action list
	 *
	 * @param integer $order
	 * @param string $getterm
	 * @return void
	 */
	public function listAction($order = 0, $getterm = "") {
		$term = $_POST["tx_personmanager_web_personmanagerpersonmanagerback"]["search"];
		if($term == NULL || $term == "") {
			$term = $getterm;
		}
		if($term == NULL || $term == "") {
			$persons = $this->personRepository->getAll($order);
		}else{
			$persons = $this->personRepository->search($term,$order);
		}
		$this->view->assign('persons', $persons);
		$this->view->assign('vars', $this->settings);
		$this->view->assign('term', $term);
		$this->view->assign('order', $order);
		$this->view->assign('countPers', count($persons));
		$this->view->assign('newIcons', $this->newIcons);
	}

	/**
	 * action show
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $person
	 * @return void
	 */
	public function showAction(\Personmanager\PersonManager\Domain\Model\Person $person) {
		$this->view->assign('person', $person);
		$this->view->assign('vars', $this->settings);
		$this->view->assign('newIcons', $this->newIcons);
	}

	/**
	 * action newShort
	 *
	 * @return void
	 */
	public function newShortAction() {
		$this->view->assign('showpage', $this->settings["flexshowpage"]);
	}
	
	/**
	 * action new
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $newPerson
	 * @param string $error
	 * @ignorevalidation $newPerson
	 * @return void
	 */
	public function newAction(\Personmanager\PersonManager\Domain\Model\Person $newPerson = NULL, $error = "") {
		$langhelp1 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrmrs','person_manager');
		$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mr','person_manager');
		$langhelp3 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrs','person_manager');
		$arr = array(0=>$langhelp1,1=>$langhelp2,2=>$langhelp3);
		$this->view->assign('anrarr', $arr);

		$vars = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["variables."];
		$varsbe = $GLOBALS['TSFE']->tmpl->setup["module."]["tx_personmanager."]["variables."];
		$this->view->assign('newPerson', $newPerson);
		$this->view->assign('vars', $vars);
		$this->view->assign('varsbe', $this->settings);
		$this->view->assign('error', $error);

		$kats = $this->categoryRepository->findAll();
		$this->view->assign('kats', $kats);
		$this->view->assign('newIcons', $this->newIcons);
	}

	/**
	 * action create
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $newPerson
	 * @return void
	 */
	public function createAction(\Personmanager\PersonManager\Domain\Model\Person $newPerson) {
		$failed = 0;
		$honey = $_POST["tx_personmanager_personmanagerfront"]["tx_personmanager_personmanagerfront"]["honeypot"];
		$anr = $_POST["tx_personmanager_personmanagerfront"]["anr"];
		$newPerson->setSalutation($anr);
		$error = "";
		$newPerson->setEmail(trim($newPerson->getEmail()));

		if($honey != "" && $honey != NULL){
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.spam','person_manager');
			$error.= "<p>$langhelp</p>";
			$failed = 1;
		}
		if($newPerson->getLastname() == "" || $newPerson->getLastname() == NULL || $newPerson->getFirstname() == "" || $newPerson->getFirstname() == NULL){
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.name','person_manager');
			$error .= "<p>$langhelp</p>";
			$failed = 1;
		}
		if (!filter_var($newPerson->getEmail(), FILTER_VALIDATE_EMAIL)) {
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.email','person_manager');
			$error .= "<p>$langhelp</p>";
			$failed = 1;
		}
		$oldMail = $this->personRepository->findOneByEmail($newPerson->getEmail());
		if($oldMail != NULL){
			if($oldMail->isUnsubscribed() == 0) {
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.emailagain','person_manager');
				$error .= "<p>$langhelp</p>";
				$failed = 1;
			}else{
				$renew=1;
			}
		}
		
		if($failed == 0) {
			$opt = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["doubleOptIn"];
			$path = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["path"];
			$sendInMail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["sendInMail"];
			$mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];
			//$site = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["site"];
			$site = $this->sitename;

			$tstmp = time();
			$hash = $newPerson->getEmail() . $tstmp;

			$newPerson->setToken(md5($hash));

			if($renew == 1){
				$this->personRepository->remove($oldMail);
			}

			$this->personRepository->add($newPerson);
			$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
			$persistenceManager->persistAll();
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.create','person_manager');
			$this->insertLog($newPerson->getUid(),$newPerson->getEmail(),$newPerson->getFirstname(),$newPerson->getLastname(),"create","$langhelp","",1);


			if ($opt == 1) {
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.confirmdata','person_manager');
				$subject = $site . ": $langhelp";
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.confirmthx','person_manager');
				$mailcontent = "$langhelp " . $site . ".<br/><br/>";
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.confirmlink','person_manager');
				$mailcontent .= "$langhelp<br/><br/>";
				//$mailcontent .= "" . $path . "tx_personmanager_personmanagerfront" . urlencode("[action]") . "=activate&tx_personmanager_personmanagerfront" . urlencode("[controller]") . "=Person&tx_personmanager_personmanagerfront" . urlencode("[token]") . "=" . $newPerson->getToken() . "&no_cache=1<br/>";
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.confirmreg','person_manager');
				if(is_numeric($path)){
					$this->uriBuilder->reset();
					$this->uriBuilder->setArguments(array(
						'tx_personmanager_personmanagerfront' => array(
							'action' => 'activate',
							'controller' => 'Person',
							'token' => $newPerson->getToken()
						),
						'id' => $path
					));
					$this->uriBuilder->setCreateAbsoluteUri(1);
					if ($_SERVER['HTTPS'] == "on") {
						$path = "https://" . $_SERVER['HTTP_HOST'] . "/" . $this->uriBuilder->buildFrontendUri();
					}else{
						$path = "http://" . $_SERVER['HTTP_HOST'] . "/" . $this->uriBuilder->buildFrontendUri();
					}
					$mailcontent .= "<a href='" . $path . "'>$langhelp</a><br/>";
				}else{
					$checkpath = substr($path, -1);
					if($checkpath != "?" && $checkpath != "&"){
						if (strpos($path,'?') !== false) {
							$path .= "&";
						}else{
							$path .= "?";
						}
					}
					$mailcontent .= "<a href='" . $path . "tx_personmanager_personmanagerfront" . urlencode("[action]") . "=activate&tx_personmanager_personmanagerfront" . urlencode("[controller]") . "=Person&tx_personmanager_personmanagerfront" . urlencode("[token]") . "=" . $newPerson->getToken() . "&no_cache=1'>$langhelp</a><br/>";
				}		
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.ifnot','person_manager');
				$mailcontent .= "<br/>$langhelp";
				$mailcontent .= "<br/><br/>".$this->getSignature();
				$empfaenger = $newPerson->getEmail();
				$this->sendMail($empfaenger, $mailcontent, $subject);
				
				//$this->redirect('checkMail');
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.createmail','person_manager');
				$this->insertLog($newPerson->getUid(),$newPerson->getEmail(),$newPerson->getFirstname(),$newPerson->getLastname(),"create","$langhelp","",1);

				$this->forward('text', null, null, array('text' => $this->flexcheckmail));
			} else {
				$newPerson->setActive(TRUE);
				$newPerson->setConfirmed(TRUE);
				$this->personRepository->update($newPerson);
				$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
				$persistenceManager->persistAll();
				
				if($sendInMail == 1){
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.registration','person_manager');
					$subject = $langhelp ." ".$newPerson->getEmail();
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.notifyRegistration','person_manager');
					$user = $newPerson->getFirstname()." ".$newPerson->getLastname()." (".$newPerson->getEmail().")";
					$mailcontent = str_replace("%s",$user,$langhelp);
					$this->sendMail($mail, $mailcontent, $subject);
				}

				//$this->redirect('confirm');
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.createsuccess','person_manager');
				$this->insertLog($newPerson->getUid(),$newPerson->getEmail(),$newPerson->getFirstname(),$newPerson->getLastname(),"create","$langhelp","",1);
				$this->forward('text', null, null, array('text' => $this->flexconfirm));
			}
		}else{
			$this->view->assign('error', $error);
			$this->view->assign('newPerson', $newPerson);
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.createfail','person_manager');
			$this->insertLog(0,$newPerson->getEmail(),$newPerson->getFirstname(),$newPerson->getLastname(),"create","$langhelp",$error,0);
			$this->forward('new', null, null, array('error' => $error, 'newPerson' => $newPerson));
		}
	}
	/**
	 * action createBe
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $newPerson
	 * @return void
	 */
	public function createBeAction(\Personmanager\PersonManager\Domain\Model\Person $newPerson) {
		$failed = 0;
		$honey = $_POST["tx_personmanager_personmanagerfront"]["tx_personmanager_personmanagerfront"]["honeypot"];
		$error = "";
		$newPerson->setEmail(trim($newPerson->getEmail()));

		if($newPerson->getLastname() == "" || $newPerson->getLastname() == NULL || $newPerson->getFirstname() == "" || $newPerson->getFirstname() == NULL){
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.name','person_manager');
			$error .= "<p>$langhelp</p>";
			$failed = 1;
		}
		if (!filter_var($newPerson->getEmail(), FILTER_VALIDATE_EMAIL)) {
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.email','person_manager');
			$error .= "<p>$langhelp</p>";
			$failed = 1;
		}
		$oldMail = $this->personRepository->findOneByEmail($newPerson->getEmail());
		if($oldMail != NULL){
			if($oldMail->isUnsubscribed() == 0) {
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.emailagain','person_manager');
				$error .= "<p>$langhelp</p>";
				$failed = 1;
			}else{
				$renew=1;
			}
		}

		if($failed == 0) {
			if($renew == 1){
				$this->personRepository->remove($oldMail);
			}

			$tstmp = time();
			$hash = $newPerson->getEmail() . $tstmp;

			$newPerson->setToken(md5($hash));
			$newPerson->setActive(TRUE);
			$newPerson->setConfirmed(TRUE);
			$this->personRepository->add($newPerson);
			$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
			$persistenceManager->persistAll();

			$this->forward('list');

		}else{
			$this->view->assign('error', $error);
			$this->view->assign('newPerson', $newPerson);
			$this->forward('new', null, null, array('error' => $error, 'newPerson' => $newPerson));
		}
	}

	/**
	 * action edit
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $person
	 * @ignorevalidation $person
	 * @return void
	 */
	public function editAction(\Personmanager\PersonManager\Domain\Model\Person $person) {
		$this->view->assign('person', $person);
		$langhelp1 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrmrs','person_manager');
		$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mr','person_manager');
		$langhelp3 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrs','person_manager');
		$arr = array(0=>$langhelp1,1=>$langhelp2,2=>$langhelp3);
		$this->view->assign('anrarr', $arr);

		$kats = $this->categoryRepository->findAll();
		$this->view->assign('kats', $kats);
		$this->view->assign('vars', $this->settings);
		$this->view->assign('newIcons', $this->newIcons);
	}

	/**
	 * action update
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $person
	 * @return void
	 */
	public function updateAction(\Personmanager\PersonManager\Domain\Model\Person $person) {
		$this->personRepository->update($person);
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $person
	 * @return void
	 */
	public function deleteAction(\Personmanager\PersonManager\Domain\Model\Person $person) {
		$this->personRepository->remove($person);
		$this->redirect('list');
	}

	/**
	 * action text
	 *
	 * @param string $text
	 * @return void
	 */
	public function textAction($text) {
		$this->view->assign('message', $text);
	}

	/**
	 * action activate
	 *
	 * @return void
	 */
	public function activateAction() {
		$sendInMail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["sendInMail"];
		$mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];
		$vars = $this->request->getArguments();
		$pers = $this->personRepository->findOneByToken($vars['token']);
		if($pers != NULL){
			$pers->setConfirmed(TRUE);
			$pers->setActive(TRUE);
			$this->personRepository->update($pers);
			$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
			$persistenceManager->persistAll();

			if($sendInMail == 1){
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.registration','person_manager');
				$subject = $langhelp ." ".$pers->getEmail();
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.notifyRegistration','person_manager');
				$user = $pers->getFirstname()." ".$pers->getLastname()." (".$pers->getEmail().")";
				$mailcontent = str_replace("%s",$user,$langhelp);
				$this->sendMail($mail, $mailcontent, $subject);
			}
			
			//$this->redirect('confirm');
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.activate','person_manager');
			$this->insertLog($pers->getUid(),$pers->getEmail(),$pers->getFirstname(),$pers->getLastname(),"activate","$langhelp","",1);
			$this->forward('text', null, null, array('text' => $this->flexconfirm));
		}else{
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.activate','person_manager');
			$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.activatefail','person_manager');
			$this->insertLog(0,"-","-","-","activate","$langhelp","$langhelp2",0);
			$this->forward('text', null, null, array('text' => $this->flexerr));
		}
	}

	/**
	 * action newLeave
	 *
	 * @return void
	 */
	public function newLeaveAction() {
		$mail = trim($_GET["mail"]);
		if($mail != NULL && $mail != ""){
			$this->forward('leave', null, null, array('mail' => $mail));
		}
	}

	/**
	 * action leave
	 *
	 * @param string $mail
	 * @return void
	 */
	public function leaveAction($mail = "") {
		if($mail == ""){
			$mail = trim($_POST["tx_personmanager_personmanagerunsub"]["tx_personmanager_personmanagerunsub"]["mail"]);
		}
		if($mail == "") {
			$mail = trim($_POST["mail"]);
		}

		$pers = $this->personRepository->findOneByEmail($mail);
		
		$opt = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["doubleOptOut"];
		$path = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["pathout"];
		$site = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["site"];
		$sendOutMail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["sendOutMail"];
		$mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];
		//$site = $this->sitename;
		
		if($pers != NULL){
			if($pers->isUnsubscribed() == 0) {
				if($opt == 1) {
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.confirmdata','person_manager');
					$subject = $site . ": $langhelp";
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.leavethx','person_manager');
					$mailcontent = sprintf("$langhelp",$site)."<br/><br/>";
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.confirmlink','person_manager');
					$mailcontent .= "$langhelp<br/><br/>";
					//$mailcontent .= "" . $path . "tx_personmanager_personmanagerunsub" . urlencode("[action]") . "=unsubscribe&tx_personmanager_personmanagerunsub" . urlencode("[controller]") . "=Person&tx_personmanager_personmanagerunsub" . urlencode("[token]") . "=" . $pers->getToken() . "&no_cache=1<br/>";
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.confirmleave','person_manager');
					if(is_numeric($path)){
						$this->uriBuilder->reset();
						$this->uriBuilder->setArguments(array(
							'tx_personmanager_personmanagerunsub' => array(
								'action' => 'unsubscribe',
								'controller' => 'Person',
								'token' => $pers->getToken()
							),
							'id' => $path
						));
						$this->uriBuilder->setCreateAbsoluteUri(1);
						if ($_SERVER['HTTPS'] == "on") {
							$path = "https://" . $_SERVER['HTTP_HOST'] . "/" . $this->uriBuilder->buildFrontendUri();
						}else{
							$path = "http://" . $_SERVER['HTTP_HOST'] . "/" . $this->uriBuilder->buildFrontendUri();
						}
						$mailcontent .= "<a href='" . $path . "'>$langhelp</a><br/>";
					}else{
						$checkpath = substr($path, -1);
						if($checkpath != "?" && $checkpath != "&"){
							if (strpos($path,'?') !== false) {
								$path .= "&";
							}else{
								$path .= "?";
							}
						}
						$mailcontent .= "<a href='" . $path . "tx_personmanager_personmanagerunsub" . urlencode("[action]") . "=unsubscribe&tx_personmanager_personmanagerunsub" . urlencode("[controller]") . "=Person&tx_personmanager_personmanagerunsub" . urlencode("[token]") . "=" . $pers->getToken() . "&no_cache=1'>$langhelp</a><br/>";
					}					
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.ifnot','person_manager');
					$mailcontent .= "<br/>$langhelp.";
					$mailcontent .= "<br/><br/>" . $this->getSignature();
					$empfaenger = $pers->getEmail();
					$this->sendMail($empfaenger, $mailcontent, $subject);

					//$this->redirect('checkMailLeave');
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.leavemail','person_manager');
					$this->insertLog($pers->getUid(),$pers->getEmail(),$pers->getFirstname(),$pers->getLastname(),"leave","$langhelp","",1);
					$this->forward('text', null, null, array('text' => $this->flexcheckmailleave));
				}else{
					$pers->setUnsubscribed(TRUE);
					$this->personRepository->update($pers);
					$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
					$persistenceManager->persistAll();

					if($sendOutMail == 1){
						$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.deregistration','person_manager');
						$subject = $langhelp ." ".$pers->getEmail();
						$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.notifyDeregistration','person_manager');
						$user = $pers->getFirstname()." ".$pers->getLastname()." (".$pers->getEmail().")";
						$mailcontent = str_replace("%s",$user,$langhelp);
						$this->sendMail($mail, $mailcontent, $subject);
					}
					
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.leavesuccess','person_manager');
					$this->insertLog($pers->getUid(),$pers->getEmail(),$pers->getFirstname(),$pers->getLastname(),"leave","$langhelp","",1);
					$this->forward('text', null, null, array('text' => $this->flexunsubscribe));
				}
			}else{
				//$this->redirect('isunsubscribed');
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.leavewant','person_manager');
				$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.leavealready','person_manager');
				$this->insertLog($pers->getUid(),$pers->getEmail(),$pers->getFirstname(),$pers->getLastname(),"leave","$langhelp","$langhelp2",0);
				$this->forward('text', null, null, array('text' => $this->flexisunsubscribed));
			}
		}
		$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.leavewant','person_manager');
		$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.leavenot','person_manager');
		$this->insertLog(0,$mail,"-","-","leave","$langhelp","$langhelp2",0);
		$this->forward('text', null, null, array('text' => $this->flexleave));
	}

	/**
	 * action unsubscribe
	 *
	 * @return void
	 */
	public function unsubscribeAction() {
		$sendOutMail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["sendOutMail"];
		$mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];
		$vars = $this->request->getArguments();
		$pers = $this->personRepository->findOneByToken($vars['token']);
		if($pers != NULL) {
			$pers->setUnsubscribed(TRUE);
			$this->personRepository->update($pers);
			$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
			$persistenceManager->persistAll();

			if($sendOutMail == 1){
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.deregistration','person_manager');
				$subject = $langhelp ." ".$pers->getEmail();
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('mail.notifyDeregistration','person_manager');
				$user = $pers->getFirstname()." ".$pers->getLastname()." (".$pers->getEmail().")";
				$mailcontent = str_replace("%s",$user,$langhelp);
				$this->sendMail($mail, $mailcontent, $subject);
			}
			
			$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('log.unsubscribe','person_manager');
			$this->insertLog($pers->getUid(),$pers->getEmail(),$pers->getFirstname(),$pers->getLastname(),"unsubscribe","$langhelp","",1);
			$this->forward('text', null, null, array('text' => $this->flexunsubscribe));
		}else {
			$this->forward('leave', null, null, array('mail' => ""));
		}
	}

	private function senadaltMail($empfaenger,$text,$subject) {
		$site = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["site"];
		$mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];

		//$header = 'From: ' . $site . '<' . $mail . '>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
		$header = 'From: ' . htmlentities($site) . '<' . $mail . '>' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
         $ret = mail($empfaenger,"=?UTF-8?Q?".quoted_printable_encode($subject)."?=\r\n",($text), $header);

    }
	private function sendMail($empfaenger, $text,$subject) {
		$site = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["site"];
		$mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];

		$message = (new \TYPO3\CMS\Core\Mail\MailMessage())
			->setFrom(array($mail => $site))
			->setTo(array($empfaenger => $empfaenger))
			->setSubject("=?utf-8?b?".base64_encode($subject)."?=")
			->setBody($text,"text/html");
		$message->send();
	}

	public function getProps($isimp){
		$vars = $this->settings;

		$pers = new \Personmanager\PersonManager\Domain\Model\Person();
		$reflect = new \ReflectionClass($pers);
		$properties   = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
		$props = array();

		foreach ($properties as $prop) {
			$desc = "";
			switch ($prop->getName()) {
				case "firstname":
					$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.firstname", "PersonManager");
					break;
				case "lastname":
					$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.lastname", "PersonManager");
					break;
				case "firstname":
					$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.firstname", "PersonManager");
					break;
				case "lastname":
					$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.lastname", "PersonManager");
					break;
				case "email":
					$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.email", "PersonManager");
					break;
				case "salutation":
					if($vars["salutation"] == 1){
						$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.salutation", "PersonManager");
						if($isimp){
							$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrmrs','person_manager');
							$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mr','person_manager');
							$langhelp3 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrs','person_manager');
							$desc .= " ($langhelp | $langhelp2 | $langhelp3) (0|1|2)";
						}
					}
					break;
				case "titel":
					if($vars["titel"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.titel", "PersonManager");}
					break;
				case "nachgtitel":
					if($vars["nachgtitel"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.nachgtitel", "PersonManager");}
					break;
				case "geb":
					if($vars["geb"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.geb", "PersonManager");}
					break;
				case "tel":
					if($vars["tel"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.tel", "PersonManager");}
					break;
				case "company":
					if($vars["company"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.company", "PersonManager");}
					break;
				case "active":
					$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.active", "PersonManager");
					if($isimp){
						$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.no','person_manager');
						$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.yes','person_manager');
						$desc .= " ($langhelp|$langhelp2) (0|1)";
					}
					break;
				case "confirmed":
					$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.confirmed", "PersonManager");
					if($isimp){
						$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.no','person_manager');
						$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.yes','person_manager');
						$desc .= " ($langhelp|$langhelp2) (0|1)";
					}
					break;
				case "unsubscribed":
					$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.unsubscribed", "PersonManager");
					if($isimp){
						$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.no','person_manager');
						$langhelp2 = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.yes','person_manager');
						$desc .= " ($langhelp|$langhelp2) (0|1)";
					}
					break;
				case "frtxt0":
					if($vars["frtxt0"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt0", "PersonManager");}
					break;
				case "frtxt1":
					if($vars["frtxt1"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt1", "PersonManager");}
					break;
				case "frtxt2":
					if($vars["frtxt2"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt2", "PersonManager");}
					break;
				case "frtxt3":
					if($vars["frtxt3"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt3", "PersonManager");}
					break;
				case "frtxt4":
					if($vars["frtxt4"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt4", "PersonManager");}
					break;
				case "frtxt5":
					if($vars["frtxt5"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt5", "PersonManager");}
					break;
				case "frtxt6":
					if($vars["frtxt6"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt6", "PersonManager");}
					break;
				case "frtxt7":
					if($vars["frtxt7"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt7", "PersonManager");}
					break;
				case "frtxt8":
					if($vars["frtxt8"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt8", "PersonManager");}
					break;
				case "frtxt9":
					if($vars["frtxt9"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.frtxt9", "PersonManager");}
					break;
				case "category":
					if($vars["category"] == 1){$desc = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_personmanager_domain_model_person.category", "PersonManager");}
					break;
			}
			if($desc != "") {
				$data = array("value" => $prop->getName(), "name" => $desc);
				array_push($props, $data);
			}
		}
		return $props;
	}

	/**
	 * action newImport
	 *
	 * @param string $error
	 * @param string $spalten
	 * @param string $trenn
	 * @param string $first
	 * @param string $impformat
	 * @return void
	 */
	public function newImportAction($error = "", $spalten = "", $trenn = "", $first = "", $impformat = "") {
		$anz = $this->personRepository->findAll()->count();
		$this->view->assign('countPers', $anz);

		if($trenn == "")$trenn = ";";
		$this->view->assign('trenn', $trenn);
		if($spalten == "")$spalten = "salutation;firstname;lastname;email";
		$this->view->assign('spalten', $spalten);
		$this->view->assign('error', $error);
		$this->view->assign('first', $first);
		if($impformat == "")$impformat = "excel";
		$this->view->assign('impformat', $impformat);

		$props = $this->getProps(1);
		$this->view->assign('props', $props);
		$this->view->assign('newIcons', $this->newIcons);
	}

	/**
	 * action import
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $person
	 * @return void
	 */
	public function importAction() {
		$failed =0;
		$vars = $_POST["tx_personmanager_web_personmanagerpersonmanagerback"];
		$spalten = $vars["spalten"];
		$trenn = $vars["trenn"];
		$first = $vars["first"];
		$impformat = $vars["impformat"];
		$check = $vars["check"];
		$filen = $vars["filen"];
		$arr = explode($trenn,$spalten);
		$error = "";
		$obj = new \ReflectionObject(new \Personmanager\PersonManager\Domain\Model\Person());

		if($first == "1"){
			$startindex=1;
		}else{
			$startindex=2;
		}

		foreach($arr as $val){
			if(!$obj->hasProperty($val)){
				$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.nocol','person_manager');
				$error.= "<p>".sprintf("$langhelp",$val)."</p>";
				$failed = 1;
			}
		}
		if($failed == 0) {
			$personen = array();

			$feler_trenner = $trenn;
			$zeilen_trenner = (string)chr(10);

			$uploaddir = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath("person_manager");
			//$uploaddir = $uploaddir . "Resources/Public/Data";
			$uploaddir = $uploaddir . "Data";
			$uploadfile = basename($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['name']['jsonobj']);
			$csv_datei = $uploaddir . "/" . $uploadfile;
			if (move_uploaded_file($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['tmp_name']['jsonobj'], $csv_datei)) {
				if (@file_exists($csv_datei) == false) {
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.nofile','person_manager');
					echo sprintf($langhelp,$csv_datei);
					exit;
				} else {
					$filename = basename($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['name']['jsonobj']);
				}
			}
			if($check == "1"){
				$csv_datei=$filen;
			}
			if ($impformat == "excel") {
				require_once 'excel/PHPExcel/IOFactory.php';
				require_once 'excel/Filter.php';
				ini_set('display_errors', '1');

				$cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
				$cacheSettings = array(' memoryCacheSize ' => '4MB');
				\PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

				$inputFileType = \PHPExcel_IOFactory::identify($csv_datei);
				$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setReadDataOnly(true);
				$objReader->setReadFilter(new \MyReadFilter());
				$objPHPExcel = $objReader->load($csv_datei);


				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle = $worksheet->getTitle();
					$highestRow = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;


					for ($row = $startindex; $row <= $highestRow; ++$row) {
						$newPerson = new \Personmanager\PersonManager\Domain\Model\Person();
						$newPerson->setActive(1);
						$newPerson->setConfirmed(1);
						foreach ($arr as $key => $value) {
							$cell = $worksheet->getCellByColumnAndRow($key, $row);
							if ($value == "category") {
							    $newKat = $this->categoryRepository->findOneByName($cell->getValue());
                                if ($newKat == NULL) {
									$newKat = new \Personmanager\PersonManager\Domain\Model\Category();
									$newKat->setName($cell->getValue());
									$this->categoryRepository->add($newKat);
                                    $persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
                                    $persistenceManager->persistAll();
								}
								$newPerson->setCategory($newKat);
							} else {
								if ($value == "salutation") {
									if (strtolower(trim($cell->getValue())) == 'herr' || strtolower(trim($cell->getValue())) == 'herrn' || strtolower(trim($cell->getValue())) == 'sir' || strtolower(trim($cell->getValue())) == 'mr') $cell->setValue(1);
									if (strtolower(trim($cell->getValue())) == 'frau' || strtolower(trim($cell->getValue())) == 'madame' ||  strtolower(trim($cell->getValue())) == 'mrs') $cell->setValue(2);
									if ($cell->getValue() != 1 && $cell->getValue() != 2) $cell->setValue(0);
								}
								if ($value == "active" || $value == "confirmed" || $value == "unsubscribed") {
									if (strtolower(trim($cell->getValue())) == 'nein' || strtolower(trim($cell->getValue())) == 'no') $cell->setValue(0);
									if (strtolower(trim($cell->getValue())) == 'ja' || strtolower(trim($cell->getValue())) == 'yes') $cell->setValue(1);
								}
								if ($value == "email"){
									$cell->setValue($this->extractEmail($cell->getValue()));
								}
								$newPerson->setProperty($value, $cell->getValue());
							}
						}
						$tstmp = time();
						$hash = $newPerson->getEmail() . $tstmp;
						$newPerson->setToken(md5($hash));

						if($newPerson->getEmail() != "" && $newPerson->getEmail() != NULL) {
							if ($check == "1") {
								$this->personRepository->add($newPerson);
								$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
								$persistenceManager->persistAll();
							} else {
								array_push($personen, $newPerson);
							}
						}
					}
				}
			}else{
				$datei_inhalt = @file_get_contents($csv_datei);
				$zeilen = explode($zeilen_trenner,$datei_inhalt);
				$anzahl_zeilen = count($zeilen);

				/*$date = date_parse_from_format('ymd', substr($filename, 0, 6));
				$timestamp = mktime(12, 0, 0, $date['month'], $date['day'], $date['year']);
				$date = new \DateTime();
				$date->setTimestamp($timestamp);*/
				//echo 'Es wurden in der CSV Datei: '.$csv_datei.' insgesamt '.($anzahl_zeilen-1).' Zeilen gefunden.<br><br>';

				if (is_array($zeilen) == true) {
					foreach($zeilen as $key => $zeile) {
						if($zeile !== null && $zeile !== ""  && $key > ($startindex-2)){
							$felder = explode($feler_trenner,$zeile);

							$newPerson = new \Personmanager\PersonManager\Domain\Model\Person();
							$newPerson->setActive(1);
							$newPerson->setConfirmed(1);
							foreach ($arr as $key => $value) {
								$cell = $felder[$key];
								if ($value == "category") {
									$newKat = $this->categoryRepository->findOneByName($cell);
									if ($newKat == NULL) {
										$newKat = new \Personmanager\PersonManager\Domain\Model\Category();
										$newKat->setName($cell);
										$this->categoryRepository->add($newKat);
										$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
										$persistenceManager->persistAll();
									}
									$newPerson->setCategory($newKat);
								} else {
									if ($value == "salutation" || $value == "salutation") {
										if (strtolower(trim($cell)) == 'herr' || strtolower(trim($cell)) == 'herrn' || strtolower(trim($cell)) == 'sir' ||  strtolower(trim($cell)) == 'mr') $cell=1;
										if (strtolower(trim($cell)) == 'frau' || strtolower(trim($cell)) == 'madame' || strtolower(trim($cell)) == 'mrs') $cell=2;
										if ($cell != 1 && $cell != 2) $cell = 0;
									}
									if ($value == "active" || $value == "confirmed" || $value == "unsubscribed") {
										if (strtolower(trim($cell)) == 'nein' || strtolower(trim($cell)) == 'no') $cell=0;
										if (strtolower(trim($cell)) == 'ja' || strtolower(trim($cell)) == 'yes') $cell=1;
									}
									if ($value == "email"){
										$cell=$this->extractEmail($cell);
									}
									$newPerson->setProperty($value, $cell);
								}
							}
							$tstmp = time();
							$hash = $newPerson->getEmail() . $tstmp;
							$newPerson->setToken(md5($hash));

							if($newPerson->getEmail() != "" && $newPerson->getEmail() != NULL) {
								if ($check == "1") {
									$this->personRepository->add($newPerson);
									$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
									$persistenceManager->persistAll();
								} else {
									array_push($personen, $newPerson);
								}
							}
						}
					}
				}
			}
			if($check == "1"){
				$this->redirect('insertData');
			}
			$this->view->assign('personen', $personen);
			$this->view->assign('arr', $arr);
			$this->view->assign('anz', count($personen));

			$this->view->assign('error', $error);
			$this->view->assign('spalten', $spalten);
			$this->view->assign('trenn', $trenn);
			$this->view->assign('first', $first);
			$this->view->assign('impformat', $impformat);
			$this->view->assign('filename', $csv_datei);
		}else{
			$this->forward('newImport', null, null, array('error' => $error, 'spalten' => $spalten, 'trenn' => $trenn, 'first' => $first, 'impformat' => $impformat));
		}
	}

	/**
	 * action insertData
	 *
	 * @return void
	 */
	public function insertDataAction() {

	}

	/**
	 * action newExport
	 *
	 * @return void
	 */
	public function newExportAction() {
		$anz = $this->personRepository->findAll()->count();
		$this->view->assign('countPers', $anz);
		$this->view->assign('newIcons', $this->newIcons);
	}

	/**
	 * action export
	 *
	 * @return void
	 */
	public function exportAction() {
		$active = $_POST["active"];
		$confirmed = $_POST["confirmed"];
		$unsubscribed = $_POST["unsubscribed"];
		$expformat = $_POST["expformat"];
		$trenn = $_POST["trenn"];

		$array = $this->personRepository->findExp($active, $confirmed, $unsubscribed);

		if($expformat == "csv") {
			$this->array_to_csv($array, $trenn);
		}else{
			$this->array_to_excel($array);
		}

		exit();
	}
	function array_to_excel($array){
		require_once 'excel/PHPExcel/IOFactory.php';
		require_once 'excel/Filter.php';
		ini_set('display_errors', '1');
		date_default_timezone_set('Europe/Vienna');

		$objPHPExcel = new \PHPExcel();

		/*$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
			->setLastModifiedBy("Maarten Balliauw")
			->setTitle("Office 2007 XLSX Test Document")
			->setSubject("Office 2007 XLSX Test Document")
			->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
			->setKeywords("office 2007 openxml php")
			->setCategory("Test result file");
		*/

		$objPHPExcel->getActiveSheet()->setTitle('Export');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->freezePane('A2');

		$props = $this->getProps(0);
		$row = 1;
		$col = 'A';
		foreach($props as $prop) {
			$objPHPExcel->getActiveSheet()->setCellValue($col.$row,$prop["name"]);
			$col++;
		}
		$row = 2;
		foreach($array as $pers) {
			$col = 'A';
			foreach($props as $prop){
				if($prop["value"] == "category"){
					$objPHPExcel->getActiveSheet()->setCellValue($col.$row,$pers->getProperty($prop["value"]));
					$help = $pers->getCategory()->getName();
				}elseif($prop["value"] == "salutation" || $prop["value"] == "salutation"){
					if($pers->getSalutation() == "0")$help = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrmrs','person_manager');
					if($pers->getSalutation() == "1")$help = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mr','person_manager');
					if($pers->getSalutation() == "2")$help = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrs','person_manager');
				}elseif($prop["value"] == "active" || $prop["value"] == "confirmed" || $prop["value"] == "unsubscribed"){
					if($pers->getProperty($prop["value"]) == "0")$help = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.no','person_manager');
					if($pers->getProperty($prop["value"]) == "1")$help = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.yes','person_manager');
				}else {
					$help = $pers->getProperty($prop["value"]);
				}
				$objPHPExcel->getActiveSheet()->setCellValue($col.$row,$help);
				$col++;
			}
			$row++;
		}

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		\header('Content-Type: application/vnd.ms-excel');
		\header('Content-Disposition: attachment;filename="export.xls"');
		\header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
	}

	function array_to_csv($array, $delimiter) {
		$filename = "export.csv";
		$props = $this->getProps(0);

		//header('Content-Type: application/csv charset=ISO-8859-1');
		header('Content-Type: application/csv charset=UTF-8');
		header('Content-Disposition: attachement; filename="'.$filename.'";');

		$f = fopen('php://output', 'w');

		foreach($props as $prop){
			echo utf8_decode($prop["name"]) . $delimiter;
		}
		echo PHP_EOL;

		foreach ($array as $pers) {
			foreach($props as $prop) {
				if($prop["value"] == "category"){
					echo utf8_decode($pers->getCategory()->getName()) . $delimiter;
				}elseif($prop["value"] == "salutation" || $prop["value"] == "salutation"){
					if($pers->getSalutation() == "0")$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrmrs','person_manager');
					if($pers->getSalutation() == "1")$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mr','person_manager');
					if($pers->getSalutation() == "2")$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.mrs','person_manager');
					echo $langhelp . $delimiter;
				}elseif($prop["value"] == "active" || $prop["value"] == "confirmed" || $prop["value"] == "unsubscribed"){
					if($pers->getProperty($prop["value"]) == "0")$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.no','person_manager');
					if($pers->getProperty($prop["value"]) == "1")$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('labels.yes','person_manager');
					echo $langhelp . $delimiter;
				}else {
					echo utf8_decode($pers->getProperty($prop["value"])) . $delimiter;
				}
			}
			echo PHP_EOL;
		}
		fclose($f);
	}

	/**
	 * action clear
	 *
	 * @return void
	 */
	public function clearAction() {
		$pid = $this->settings["storagePid"];
		//$GLOBALS["TYPO3_DB"]->exec_DELETEquery("tx_personmanager_domain_model_person","1");
		$GLOBALS["TYPO3_DB"]->exec_UPDATEquery("tx_personmanager_domain_model_person","pid=".$pid,array("deleted"=>1));
		$this->redirect('list');
	}

	public function insertLog($person = 0, $email = "", $fname = "", $lname = "", $action = "", $detail = "", $fehler = "", $success= 0) {
		$newLog = new \Personmanager\PersonManager\Domain\Model\Log();

		$newLog->setPerson($person);
		$newLog->setEmail($email);
		$newLog->setFirstname($fname);
		$newLog->setLastname($lname);
		$newLog->setAction($action);
		$newLog->setDetail($detail);
		$newLog->setFehler($fehler);
		$newLog->setSuccess($success);

		$this->logRepository->add($newLog);
		$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
		$persistenceManager->persistAll();
	}

	/**
	 * action loglist
	 *
	 * @return void
	 */
	public function loglistAction() {
		$logs = $this->logRepository->findAll();
		$this->view->assign('logs', $logs);
		$this->view->assign('newIcons', $this->newIcons);
	}

	/**
	 * action blNewImport
	 *
	 * @param string $error
	 * @param string $first
	 * @param string $impformat
	 * @return void
	 */
	public function blNewImportAction($error = "", $first = "", $impformat = "") {
		$anz = $this->blacklistRepository->findAll()->count();
		$this->view->assign('countPers', $anz);

		$this->view->assign('error', $error);
		$this->view->assign('first', $first);
		if($impformat == "")$impformat = "excel";
		$this->view->assign('impformat', $impformat);

		$props = $this->getProps(1);
		$this->view->assign('props', $props);
		$this->view->assign('newIcons', $this->newIcons);
	}

	/**
	 * action blImport
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Person $person
	 * @return void
	 */
	public function blImportAction() {
		$failed =0;
		$vars = $_POST["tx_personmanager_web_personmanagerpersonmanagerback"];
		$first = $vars["first"];
		$impformat = $vars["impformat"];
		$check = $vars["check"];
		$filen = $vars["filen"];
		$error = "";
		$obj = new \ReflectionObject(new \Personmanager\PersonManager\Domain\Model\Person());

		if($first == "1"){
			$startindex=1;
		}else{
			$startindex=2;
		}

		if($failed == 0) {
			$blacklists = array();

			$feler_trenner = ";";
			$zeilen_trenner = (string)chr(10);

			$uploaddir = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath("person_manager");
			//$uploaddir = $uploaddir . "Resources/Public/Data";
			$uploaddir = $uploaddir . "Data";
			$uploadfile = basename($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['name']['jsonobj']);
			$csv_datei = $uploaddir . "/" . $uploadfile;
			if (move_uploaded_file($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['tmp_name']['jsonobj'], $csv_datei)) {
				if (@file_exists($csv_datei) == false) {
					$langhelp = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error.nofile','person_manager');
					echo sprintf($langhelp,$csv_datei);
					exit;
				} else {
					$filename = basename($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['name']['jsonobj']);
				}
			}
			if($check == "1"){
				$csv_datei=$filen;
			}
			if ($impformat == "excel") {
				require_once 'excel/PHPExcel/IOFactory.php';
				require_once 'excel/Filter.php';
				ini_set('display_errors', '1');

				$cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
				$cacheSettings = array(' memoryCacheSize ' => '4MB');
				\PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

				$inputFileType = \PHPExcel_IOFactory::identify($csv_datei);
				$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
				$objReader->setReadDataOnly(true);
				$objReader->setReadFilter(new \MyReadFilter());
				$objPHPExcel = $objReader->load($csv_datei);


				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
					$worksheetTitle = $worksheet->getTitle();
					$highestRow = $worksheet->getHighestRow(); // e.g. 10
					$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
					$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
					$nrColumns = ord($highestColumn) - 64;


					for ($row = $startindex; $row <= $highestRow; ++$row) {
						$newBlacklist = new \Personmanager\PersonManager\Domain\Model\Blacklist();
						$cell = $worksheet->getCellByColumnAndRow(0, $row);
						$newBlacklist->setEmail(trim($cell->getValue()));

						if($newBlacklist->getEmail() != "" && $newBlacklist->getEmail() != NULL) {
							if ($check == "1") {
								$this->blacklistRepository->add($newBlacklist);
								$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
								$persistenceManager->persistAll();
							} else {
								array_push($blacklists, $newBlacklist);
							}
						}
					}					
					
				}
			}else{
				$datei_inhalt = @file_get_contents($csv_datei);
				$zeilen = explode($zeilen_trenner,$datei_inhalt);
				$anzahl_zeilen = count($zeilen);

				if (is_array($zeilen) == true) {
					foreach($zeilen as $key => $zeile) {
						if($zeile !== null && $zeile !== ""  && $key > ($startindex-2)){
							$felder = explode($feler_trenner,$zeile);

							$newBlacklist = new \Personmanager\PersonManager\Domain\Model\Blacklist();
							$cell = $felder[0];
							$help = explode(",",$cell);
							$newBlacklist->setEmail(trim($help[0]));

							if($newBlacklist->getEmail() != "" && $newBlacklist->getEmail() != NULL) {
								if ($check == "1") {
									$this->blacklistRepository->add($newBlacklist);
									$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
									$persistenceManager->persistAll();
								} else {
									array_push($blacklists, $newBlacklist);
								}
							}
						}
					}
				}
			}
			if($check == "1"){
				$this->redirect('insertData');
			}
			$this->view->assign('blacklists', $blacklists);
			$this->view->assign('anz', count($blacklists));

			$this->view->assign('error', $error);
			$this->view->assign('first', $first);
			$this->view->assign('impformat', $impformat);
			$this->view->assign('filename', $csv_datei);
		}else{
			$this->forward('blNewImport', null, null, array('error' => $error, 'first' => $first, 'impformat' => $impformat));
		}
	}

	/**
	 * action blClear
	 *
	 * @return void
	 */
	public function blClearAction() {
		$GLOBALS["TYPO3_DB"]->exec_DELETEquery("tx_personmanager_domain_model_blacklist","1");
		$this->redirect('list');
	}

	public function getSignature(){
		if ($_SERVER['HTTPS'] == "on") {
			$base = "https://" . $_SERVER['HTTP_HOST'];
		}else{
			$base = "http://" . $_SERVER['HTTP_HOST'];
		}
		return str_replace('img src="','img src="'.$base."/",$this->signature);
	}
	public function extractEmail($email){
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9_\-\+\.]+/i';
        preg_match_all($pattern, $email, $matches);
        if(is_array($matches[0])){
            if (filter_var($matches[0][0], FILTER_VALIDATE_EMAIL)) {
                return $matches[0][0];
            }
        }else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        return "";
    }

}
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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Personmanager\PersonManager\Phpexcel\MyReadFilter;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * PersonController
 */
class PersonController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

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

    protected $persistenceManager = NULL;

    protected $extKey = 'person_manager';

    public $signature = "";
    public $sitename = "";

    public $flexcheckmail = "";
    public $flexconfirm = "";
    public $flexerr = "";

    public $flexleave = "";
    public $flexisunsubscribed = "";
    public $flexcheckmailleave = "";
    public $flexunsubscribe = "";

    public function initializeAction()
    {
        $this->persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');

        $langhelp = LocalizationUtility::translate('error.notext', $this->extKey);

        $this->signature = $this->configurationManager->getContentObject()->parseFunc($this->settings['flexsignature'], array(), '< lib.parseFunc_RTE');
        $this->sitename = $this->settings['flexsitename'];
        if ($this->sitename == NULL || $this->sitename == "") {
            $this->sitename = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["site"];
        }

        $this->flexcheckmail = $this->settings['flexcheckmail'];
        if ($this->flexcheckmail == NULL || $this->flexcheckmail == "") {
            $this->flexcheckmail = "<h1>" . LocalizationUtility::translate('msg.thx', $this->extKey) . "</h1><h3>" . LocalizationUtility::translate('msg.ancheckmail', $this->extKey) . "</h3>";
        }
        $this->flexconfirm = $this->settings['flexconfirm'];
        if ($this->flexconfirm == NULL || $this->flexconfirm == "") {
            $this->flexconfirm = "<h1>" . LocalizationUtility::translate('msg.thx', $this->extKey) . "</h1><h3>" . LocalizationUtility::translate('msg.anconfirm', $this->extKey) . "</h3>";
        }
        $this->flexerr = $this->settings['flexerr'];
        if ($this->flexerr == NULL || $this->flexerr == "") {
            $this->flexerr = "<h1>" . LocalizationUtility::translate('msg.error', $this->extKey) . "</h1><h3>" . LocalizationUtility::translate('msg.anerror', $this->extKey) . "</h3>";
        }

        $this->flexcheckmailleave = $this->settings['flexcheckmailleave'];
        if ($this->flexcheckmailleave == NULL || $this->flexcheckmailleave == "") {
            $this->flexcheckmailleave = "<h1>" . LocalizationUtility::translate('msg.thx', $this->extKey) . "</h1><h3>" . LocalizationUtility::translate('msg.abcheckmail', $this->extKey) . "</h3>";
        }
        $this->flexisunsubscribed = $this->settings['flexisunsubscribed'];
        if ($this->flexisunsubscribed == NULL || $this->flexisunsubscribed == "") {
            $this->flexisunsubscribed = "<h1>" . LocalizationUtility::translate('msg.error', $this->extKey) . "</h1><h3>" . LocalizationUtility::translate('msg.abalready', $this->extKey) . "</h3>";
        }
        $this->flexleave = $this->settings['flexleave'];
        if ($this->flexleave == NULL || $this->flexleave == "") {
            $this->flexleave = "<h1>" . LocalizationUtility::translate('msg.error', $this->extKey) . "</h1><h3>" . LocalizationUtility::translate('msg.abnomail', $this->extKey) . "</h3>";
        }
        $this->flexunsubscribe = $this->settings['flexunsubscribe'];
        if ($this->flexunsubscribe == NULL || $this->flexunsubscribe == "") {
            $this->flexunsubscribe = "<h1>" . LocalizationUtility::translate('msg.thx', $this->extKey) . "</h1><h3>" . LocalizationUtility::translate('msg.abconfirm', $this->extKey) . "</h3>";
        }
    }

    public function initializeUpdateAction()
    {
        $propertyMappingConfiguration = $this->arguments['person']->getPropertyMappingConfiguration();
        $propertyMappingConfiguration->allowAllProperties('category');
    }

    /**
     * action newShort
     *
     * @return void
     */
    public function newShortAction()
    {
        $this->view->assign('showpage', $this->settings["flexshowpage"]);
    }

    protected function initializeNewAction()
    {
        $propertyMappingConfiguration = $this->arguments['newPerson']->getPropertyMappingConfiguration();
        $propertyMappingConfiguration->allowAllProperties();
        $propertyMappingConfiguration->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter', \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
    }

    /**
     * action new
     *
     * @param \Personmanager\PersonManager\Domain\Model\Person $newPerson
     * @param string $error
     * @ignorevalidation $newPerson
     * @return void
     */
    public function newAction(\Personmanager\PersonManager\Domain\Model\Person $newPerson = NULL, $error = "")
    {
        $langhelp1 = LocalizationUtility::translate('labels.mrmrs', $this->extKey);
        $langhelp2 = LocalizationUtility::translate('labels.mr', $this->extKey);
        $langhelp3 = LocalizationUtility::translate('labels.mrs', $this->extKey);
        $arr = array(0 => $langhelp1, 1 => $langhelp2, 2 => $langhelp3);
        $this->view->assign('anrarr', $arr);

        $vars = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["variables."];
        $this->view->assign('newPerson', $newPerson);
        $this->view->assign('vars', $vars);
        $this->view->assign('error', $error);

        $kats = $this->categoryRepository->findAll();
        $this->view->assign('kats', $kats);
    }

    /**
     * action create
     *
     * @param \Personmanager\PersonManager\Domain\Model\Person $newPerson
     * @return void
     */
    public function createAction(\Personmanager\PersonManager\Domain\Model\Person $newPerson)
    {
        $failed = 0;
        $honey = $this->request->getArguments()["tx_personmanager_personmanagerfront"]["honeypot"];
        $anr = $this->request->getArguments()["anr"];
        $terms = $this->request->getArguments()["terms"];

        $termVar = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["variables."]["terms"];

        $newPerson->setSalutation($anr);
        $error = "";
        $newPerson->setEmail(trim($newPerson->getEmail()));

        if ($honey != "" && $honey != NULL) {
            $langhelp = LocalizationUtility::translate('error.spam', $this->extKey);
            $error .= "<p>$langhelp</p>";
            $failed = 1;
        }
        if ($termVar) {
            if ($terms != "1" || $terms != 1) {
                $langhelp = LocalizationUtility::translate('error.terms', $this->extKey);
                $error .= "<p>$langhelp</p>";
                $failed = 1;
            }
        }
        if ($newPerson->getLastname() == "" || $newPerson->getLastname() == NULL || $newPerson->getFirstname() == "" || $newPerson->getFirstname() == NULL) {
            $langhelp = LocalizationUtility::translate('error.name', $this->extKey);
            $error .= "<p>$langhelp</p>";
            $failed = 1;
        }
        if (!filter_var(idn_to_ascii($newPerson->getEmail()), FILTER_VALIDATE_EMAIL)) {
            $langhelp = LocalizationUtility::translate('error.email', $this->extKey);
            $error .= "<p>$langhelp</p>";
            $failed = 1;
        }
        $oldMail = $this->personRepository->findOneByEmail($newPerson->getEmail());
        if ($oldMail != NULL) {
            if ($oldMail->isUnsubscribed() == 0) {
                $langhelp = LocalizationUtility::translate('error.emailagain', $this->extKey);
                $error .= "<p>$langhelp</p>";
                $failed = 1;
            } else {
                $renew = 1;
            }
        }

        if ($failed == 0) {
            $opt = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["doubleOptIn"];
            $path = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["path"];
            $sendInMail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["sendInMail"];
            $mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];
            //$site = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["site"];
            $site = $this->sitename;

            $tstmp = time();
            $hash = $newPerson->getEmail() . $tstmp;

            $newPerson->setToken(md5($hash));

            if ($renew == 1) {
                $this->personRepository->remove($oldMail);
            }

            $this->personRepository->add($newPerson);
            $this->persistenceManager->persistAll();
            $langhelp = LocalizationUtility::translate('log.create', $this->extKey);
            $this->insertLog($newPerson->getUid(), $newPerson->getEmail(), $newPerson->getFirstname(), $newPerson->getLastname(), "create", "$langhelp", "", 1);

            if ($opt == 1) {
                $this->doBuildLinkMail(TRUE, $site, $path, $newPerson);
            } else {
                $this->doActivate($newPerson, $sendInMail, $mail, "log.createsuccess", "create");
            }
        } else {
            $this->view->assign('error', $error);
            $this->view->assign('newPerson', $newPerson);
            $langhelp = LocalizationUtility::translate('log.createfail', $this->extKey);
            $this->insertLog(0, $newPerson->getEmail(), $newPerson->getFirstname(), $newPerson->getLastname(), "create", "$langhelp", $error, 0);
            $this->forward('new', null, null, array('error' => $error, 'newPerson' => $newPerson));
        }
    }

    /**
     * action activate
     *
     * @return void
     */
    public function activateAction()
    {
        $sendInMail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["sendInMail"];
        $mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];
        $vars = $this->request->getArguments();
        $pers = $this->personRepository->findOneByToken($vars['token']);
        if ($pers != NULL) {
            $this->doActivate($pers, $sendInMail, $mail, "log.activate", "activate");
        } else {
            $langhelp = LocalizationUtility::translate('log.activate', $this->extKey);
            $langhelp2 = LocalizationUtility::translate('log.activatefail', $this->extKey);
            $this->insertLog(0, "-", "-", "-", "activate", "$langhelp", "$langhelp2", 0);
            $this->forward('text', null, null, array('text' => $this->flexerr));
        }
    }

    protected function doActivate($pers, $sendInMail, $mail, $msgKey, $log)
    {
        $pers->setConfirmed(TRUE);
        $pers->setActive(TRUE);
        $this->personRepository->update($pers);
        $this->persistenceManager->persistAll();

        if ($sendInMail == 1) {
            $langhelp = LocalizationUtility::translate('mail.registration', $this->extKey);
            $subject = $langhelp . " " . $pers->getEmail();
            $langhelp = LocalizationUtility::translate('mail.notifyRegistration', $this->extKey);
            $user = $pers->getFirstname() . " " . $pers->getLastname() . " (" . $pers->getEmail() . ")";
            $mailcontent = str_replace("%s", $user, $langhelp);
            $this->sendMail($mail, $mailcontent, $subject);
        }

        $langhelp = LocalizationUtility::translate($msgKey, $this->extKey);
        $this->insertLog($pers->getUid(), $pers->getEmail(), $pers->getFirstname(), $pers->getLastname(), $log, "$langhelp", "", 1);
        $this->forward('text', null, null, array('text' => $this->flexconfirm));
    }

    /**
     * action newLeave
     *
     * @return void
     */
    public function newLeaveAction()
    {
        $mail = trim($_GET["mail"]);
        if ($mail != NULL && $mail != "") {
            $this->forward('leave', null, null, array('mail' => $mail));
        }
    }

    /**
     * action leave
     *
     * @param string $mail
     * @return void
     */
    public function leaveAction($mail = "")
    {
        if ($mail == "") {
            $mail = trim($this->request->getArguments()["mail"]);
        }
        if ($mail == "") {
            $mail = trim($_POST["mail"]);
        }

        $pers = $this->personRepository->findOneByEmail($mail);

        $opt = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["doubleOptOut"];
        $path = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["pathout"];
        $site = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["site"];
        $sendOutMail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["sendOutMail"];
        $mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];
        //$site = $this->sitename;

        if ($pers != NULL) {
            if ($pers->isUnsubscribed() == 0) {
                if ($opt == 1) {
                    $this->doBuildLinkMail(FALSE, $site, $path, $pers);
                } else {
                    $this->doUnsubscribe($pers, $sendOutMail, $mail, 'log.leavesuccess', 'leave');
                }
            } else {
                //$this->redirect('isunsubscribed');
                $langhelp = LocalizationUtility::translate('log.leavewant', $this->extKey);
                $langhelp2 = LocalizationUtility::translate('log.leavealready', $this->extKey);
                $this->insertLog($pers->getUid(), $pers->getEmail(), $pers->getFirstname(), $pers->getLastname(), "leave", "$langhelp", "$langhelp2", 0);
                $this->forward('text', null, null, array('text' => $this->flexisunsubscribed));
            }
        }
        $langhelp = LocalizationUtility::translate('log.leavewant', $this->extKey);
        $langhelp2 = LocalizationUtility::translate('log.leavenot', $this->extKey);
        $this->insertLog(0, $mail, "-", "-", "leave", "$langhelp", "$langhelp2", 0);
        $this->forward('text', null, null, array('text' => $this->flexleave));
    }

    /**
     * action unsubscribe
     *
     * @return void
     */
    public function unsubscribeAction()
    {
        $sendOutMail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["sendOutMail"];
        $mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];
        $vars = $this->request->getArguments();
        $pers = $this->personRepository->findOneByToken($vars['token']);
        if ($pers != NULL) {
            $this->doUnsubscribe($pers, $sendOutMail, $mail, 'log.unsubscribe', 'unsubscribe');
        } else {
            $this->forward('leave', null, null, array('mail' => ""));
        }
    }

    protected function doUnsubscribe($pers, $sendOutMail, $mail, $msgKey, $log)
    {
        $pers->setUnsubscribed(TRUE);
        $this->personRepository->update($pers);
        $this->persistenceManager->persistAll();

        if ($sendOutMail == 1) {
            $langhelp = LocalizationUtility::translate('mail.deregistration', $this->extKey);
            $subject = $langhelp . " " . $pers->getEmail();
            $langhelp = LocalizationUtility::translate('mail.notifyDeregistration', $this->extKey);
            $user = $pers->getFirstname() . " " . $pers->getLastname() . " (" . $pers->getEmail() . ")";
            $mailcontent = str_replace("%s", $user, $langhelp);
            $this->sendMail($mail, $mailcontent, $subject);
        }

        $langhelp = LocalizationUtility::translate($msgKey, $this->extKey);
        $this->insertLog($pers->getUid(), $pers->getEmail(), $pers->getFirstname(), $pers->getLastname(), $log, "$langhelp", "", 1);
        $this->forward('text', null, null, array('text' => $this->flexunsubscribe));
    }

    protected function doBuildLinkMail($new, $site, $path, $pers){
        $langhelp = LocalizationUtility::translate('mail.confirmdata', $this->extKey);
        $subject = $site . ": $langhelp";
        $langhelp = LocalizationUtility::translate($new ? 'mail.confirmthx' : 'mail.leavethx', $this->extKey);
        $mailcontent = sprintf("$langhelp", $site) . "<br/><br/>";
        $langhelp = LocalizationUtility::translate('mail.confirmlink', $this->extKey);
        $mailcontent .= "$langhelp<br/><br/>";

        $langhelp = LocalizationUtility::translate($new ? 'mail.confirmreg' : 'mail.confirmleave', $this->extKey);
        $mailcontent .= $this->doBuildLinkUrl($pers, $path, $new ? 'tx_personmanager_personmanagerfront' : 'tx_personmanager_personmanagerunsub', $new ? 'activate' : 'unsubscribe', $langhelp);

        $langhelp = LocalizationUtility::translate('mail.ifnot', $this->extKey);
        $mailcontent .= "<br/>$langhelp";
        $mailcontent .= "<br/><br/>" . $this->getSignature();
        $empfaenger = $pers->getEmail();
        $this->sendMail($empfaenger, $mailcontent, $subject);

        $langhelp = LocalizationUtility::translate($new ? 'log.createmail' : 'log.leavemail', $this->extKey);
        $this->insertLog($pers->getUid(), $pers->getEmail(), $pers->getFirstname(), $pers->getLastname(), $new ? "create" : "leave", "$langhelp", "", 1);

        $this->forward('text', null, null, array('text' => $new ? $this->flexcheckmail : $this->flexcheckmailleave));
    }
    protected function doBuildLinkUrl($pers, $path, $plugin, $action, $text)
    {
        if (is_numeric($path)) {
            $this->uriBuilder->reset();
            $this->uriBuilder->setArguments(array(
                $plugin => array(
                    'action' => $action,
                    'controller' => 'Person',
                    'token' => $pers->getToken()
                ),
                'id' => $path
            ));
            $this->uriBuilder->setCreateAbsoluteUri(1);
            if ($_SERVER['HTTPS'] == "on") {
                $path = "https://" . $_SERVER['HTTP_HOST'] . "/" . $this->uriBuilder->buildFrontendUri();
            } else {
                $path = "http://" . $_SERVER['HTTP_HOST'] . "/" . $this->uriBuilder->buildFrontendUri();
            }
            return "<a href='" . $path . "'>$text</a><br/>";
        } else {
            $checkpath = substr($path, -1);
            if ($checkpath != "?" && $checkpath != "&") {
                if (strpos($path, '?') !== false) {
                    $path .= "&";
                } else {
                    $path .= "?";
                }
            }
            return "<a href='" . $path . $plugin . urlencode("[action]") . "=" . $action . "&" . $plugin . urlencode("[controller]") . "=Person&" . $plugin . urlencode("[token]") . "=" . $pers->getToken() . "&no_cache=1'>$text</a><br/>";
        }
    }

    protected function sendMail($empfaenger, $text, $subject)
    {
        $site = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["site"];
        $mail = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["options."]["mail"];

        $message = (new \TYPO3\CMS\Core\Mail\MailMessage())
            ->setFrom(array($mail => $site))
            ->setTo(array($empfaenger => $empfaenger))
            ->setSubject("=?utf-8?b?" . base64_encode($subject) . "?=")
            ->setBody($text, "text/html");
        $message->send();
    }

    /**
     * action text
     *
     * @param string $text
     * @return void
     */
    public function textAction($text)
    {
        $this->view->assign('message', $text);
    }

    public function insertLog($person = 0, $email = "", $fname = "", $lname = "", $action = "", $detail = "", $fehler = "", $success = 0)
    {
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
        $this->persistenceManager->persistAll();
    }

    public function getSignature()
    {
        if ($_SERVER['HTTPS'] == "on") {
            $base = "https://" . $_SERVER['HTTP_HOST'];
        } else {
            $base = "http://" . $_SERVER['HTTP_HOST'];
        }
        return str_replace('img src="', 'img src="' . $base . "/", $this->signature);
    }
}
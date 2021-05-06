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

use Personmanager\PersonManager\Domain\Model\Person;
use Personmanager\PersonManager\Domain\Repository\BlacklistRepository;
use Personmanager\PersonManager\Domain\Repository\CategoryRepository;
use Personmanager\PersonManager\Domain\Repository\PersonRepository;
use Personmanager\PersonManager\Service\LogService;
use Personmanager\PersonManager\Service\MailService;
use Personmanager\PersonManager\Service\PersonService;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\Mailer;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * PersonController
 */
class PersonController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * categoryRepository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository = NULL;

    /**
     * personRepository
     *
     * @var PersonRepository
     */
    protected $personRepository = NULL;

    /**
     * logService
     *
     * @var LogService
     */
    protected $logService = NULL;

    /**
     * personService
     *
     * @var PersonService
     */
    protected $personService = NULL;

    /**
     * mailService
     *
     * @var MailService
     */
    protected $mailService = NULL;

    /**
     * 
     * @var PersistenceManager
     */
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

    /**
     * @param LogService $logService
     */
    public function injectLogService(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * @param PersonService $personService
     */
    public function injectPersonService(PersonService $personService)
    {
        $this->personService = $personService;
    }

    /**
     * @param MailService $mailService
     */
    public function injectMailService(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    /**
     * @param CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param PersonService $personService
     */
    public function injectPersonRepository(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    /**
     * @param PersistenceManager $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManager $persistenceManager)
    {
        $this->persistenceManager = $persistenceManager;
    }

    public function initializeAction()
    {
        $this->personService->setSettings($this->settings);

        $this->signature = $this->configurationManager->getContentObject()->parseFunc($this->settings['flexsignature'], array(), '< lib.parseFunc_RTE');
        $this->sitename = $this->settings['flexsitename'];
        if ($this->sitename == NULL || $this->sitename == "") {
            $this->sitename = $this->settings["options"]["site"];
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
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation $newPerson
     * @return void
     */
    public function newAction(Person $newPerson = NULL, $error = "")
    {
        $langhelp1 = LocalizationUtility::translate('labels.mrmrs', $this->extKey);
        $langhelp2 = LocalizationUtility::translate('labels.mr', $this->extKey);
        $langhelp3 = LocalizationUtility::translate('labels.mrs', $this->extKey);
        $arr = array(0 => $langhelp1, 1 => $langhelp2, 2 => $langhelp3);
        $this->view->assign('anrarr', $arr);

        $this->view->assign('newPerson', $newPerson);
        $this->view->assign('error', $error);

        $kats = $this->categoryRepository->findAll();
        $this->view->assign('kats', $kats);
    }

    /**
     * action create
     *
     * @param Person $newPerson
     * @Extbase\Validate(param="newPerson", validator="Personmanager\PersonManager\Domain\Validator\HoneyPotValidator")
     * @Extbase\Validate(param="newPerson", validator="Personmanager\PersonManager\Domain\Validator\TermsValidator")
     * @Extbase\Validate(param="newPerson", validator="Personmanager\PersonManager\Domain\Validator\Person\NameValidator")
     * @Extbase\Validate(param="newPerson", validator="Personmanager\PersonManager\Domain\Validator\Person\EmailValidator")
     * @Extbase\Validate(param="newPerson", validator="Personmanager\PersonManager\Domain\Validator\Person\EmailAgainValidator")
     * @return void
     */
    public function createAction(Person $newPerson)
    {
        $hash = $newPerson->getEmail() . time();
        $newPerson->setToken(md5($hash));

        $this->personRepository->add($newPerson);
        $this->persistenceManager->persistAll();
        $langhelp = LocalizationUtility::translate('log.create', $this->extKey);
        $this->logService->insertLog($newPerson->getUid(), $newPerson->getEmail(), $newPerson->getFirstname(), $newPerson->getLastname(), "create", $langhelp, "", 1);

        if ($this->settings['options']["doubleOptIn"] == 1) {
            $this->mailService->doBuildLinkMail(TRUE, $this->sitename, $this->settings['options']["path"], $newPerson);
            $this->forward('text', null, null, array('text' => $this->flexcheckmail));
        } else {
            $this->personService->doActivate($newPerson, $this->settings['options']["sendInMail"], $this->settings['options']["mail"], "log.createsuccess", "create");
            $this->forward('text', null, null, array('text' => $this->flexconfirm));
        }
    }

    /**
     * action activate
     *
     * @return void
     */
    public function activateAction()
    {
        $vars = $this->request->getArguments();
        $pers = $this->personRepository->findOneByToken($vars['token']);
        if ($pers != NULL) {
            $this->personService->doActivate($pers, $this->settings["options"]["sendInMail"], $this->settings["options"]["mail"], "log.activate", "activate");
            $this->forward('text', null, null, array('text' => $this->flexconfirm));
        } else {
            $this->logService->insertLog(
                0,
                "-",
                 "-",
                 "-",
                 "activate",
                 LocalizationUtility::translate('log.activate', $this->extKey),
                 LocalizationUtility::translate('log.activatefail', $this->extKey),
                 0
            );
            $this->forward('text', null, null, array('text' => $this->flexerr));
        }
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
        // Don't take the easy route - do it the hard way
        if ($mail == "") {
            $mail = trim($this->request->getArguments()["mail"]);
        }
        if ($mail == "") {
            $mail = trim($_POST["mail"]);
        }

        $pers = $this->personRepository->findOneByEmail($mail);

        $opt = $this->settings["options"]["doubleOptOut"];
        $path = $this->settings["options"]["pathout"];
        $site = $this->settings["options"]["site"];
        $sendOutMail = $this->settings["options"]["sendOutMail"];
        $mail = $this->settings["options"]["mail"];

        if ($pers != NULL) {
            if ($pers->isUnsubscribed() == 0) {
                if ($opt == 1) {
                    $this->mailService->doBuildLinkMail(FALSE, $site, $path, $pers);
                    $this->forward('text', null, null, array('text' => $this->flexcheckmailleave));
                } else {
                    $this->personService->doUnsubscribe($pers, $sendOutMail, $mail, 'log.leavesuccess', 'leave');
                    $this->forward('text', null, null, array('text' => $this->flexunsubscribe));
                }
            } else {
                $langhelp = LocalizationUtility::translate('log.leavewant', $this->extKey);
                $langhelp2 = LocalizationUtility::translate('log.leavealready', $this->extKey);
                $this->logService->insertLog($pers->getUid(), $pers->getEmail(), $pers->getFirstname(), $pers->getLastname(), "leave", "$langhelp", "$langhelp2", 0);
                $this->forward('text', null, null, array('text' => $this->flexisunsubscribed));
            }
        }
        $langhelp = LocalizationUtility::translate('log.leavewant', $this->extKey);
        $langhelp2 = LocalizationUtility::translate('log.leavenot', $this->extKey);
        $this->logService->insertLog(0, $mail, "-", "-", "leave", "$langhelp", "$langhelp2", 0);
        $this->forward('text', null, null, array('text' => $this->flexleave));
    }

    /**
     * action unsubscribe
     *
     * @return void
     */
    public function unsubscribeAction()
    {
        $sendOutMail = $this->settings["options"]["sendOutMail"];
        $mail = $this->settings["options"]["mail"];
        $vars = $this->request->getArguments();
        $pers = $this->personRepository->findOneByToken($vars['token']);
        if ($pers != NULL) {
            $this->personService->doUnsubscribe($pers, $sendOutMail, $mail, 'log.unsubscribe', 'unsubscribe');
            $this->forward('text', null, null, array('text' => $this->flexunsubscribe));
        } else {
            $this->forward('leave', null, null, array('mail' => ""));
        }
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
}

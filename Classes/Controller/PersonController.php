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
use Personmanager\PersonManager\Domain\Repository\CategoryRepository;
use Personmanager\PersonManager\Domain\Repository\PersonRepository;
use Personmanager\PersonManager\Domain\Validator\HoneyPotValidator;
use Personmanager\PersonManager\Domain\Validator\Person\EmailAgainValidator;
use Personmanager\PersonManager\Domain\Validator\Person\EmailValidator;
use Personmanager\PersonManager\Domain\Validator\Person\NameValidator;
use Personmanager\PersonManager\Domain\Validator\TermsValidator;
use Personmanager\PersonManager\Service\LogService;
use Personmanager\PersonManager\Service\MailService;
use Personmanager\PersonManager\Service\PersonService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * PersonController
 */
class PersonController extends ActionController
{
    /**
     * categoryRepository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * personRepository
     *
     * @var PersonRepository
     */
    protected $personRepository;

    /**
     * logService
     *
     * @var LogService
     */
    protected $logService;

    /**
     * personService
     *
     * @var PersonService
     */
    protected $personService;

    /**
     * mailService
     *
     * @var MailService
     */
    protected $mailService;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    protected $extKey = 'person_manager';

    public $signature = '';
    public $sitename = '';

    public $flexcheckmail = '';
    public $flexconfirm = '';
    public $flexerr = '';

    public $flexleave = '';
    public $flexisunsubscribed = '';
    public $flexcheckmailleave = '';
    public $flexunsubscribe = '';

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

        // @extensionScannerIgnoreLine
        $this->signature = $this->configurationManager->getContentObject()->parseFunc($this->settings['flexsignature'], [], '< lib.parseFunc_RTE');
        $this->sitename = $this->settings['flexsitename'];
        if ($this->sitename == null || $this->sitename == '') {
            $this->sitename = $this->settings['options']['site'];
        }

        $this->flexcheckmail = $this->settings['flexcheckmail'];
        if ($this->flexcheckmail == null || $this->flexcheckmail == '') {
            $this->flexcheckmail = '<h1>' . LocalizationUtility::translate('msg.thx', $this->extKey) . '</h1><h3>' . LocalizationUtility::translate('msg.ancheckmail', $this->extKey) . '</h3>';
        }
        $this->flexconfirm = $this->settings['flexconfirm'];
        if ($this->flexconfirm == null || $this->flexconfirm == '') {
            $this->flexconfirm = '<h1>' . LocalizationUtility::translate('msg.thx', $this->extKey) . '</h1><h3>' . LocalizationUtility::translate('msg.anconfirm', $this->extKey) . '</h3>';
        }
        $this->flexerr = $this->settings['flexerr'];
        if ($this->flexerr == null || $this->flexerr == '') {
            $this->flexerr = '<h1>' . LocalizationUtility::translate('msg.error', $this->extKey) . '</h1><h3>' . LocalizationUtility::translate('msg.anerror', $this->extKey) . '</h3>';
        }

        $this->flexcheckmailleave = $this->settings['flexcheckmailleave'];
        if ($this->flexcheckmailleave == null || $this->flexcheckmailleave == '') {
            $this->flexcheckmailleave = '<h1>' . LocalizationUtility::translate('msg.thx', $this->extKey) . '</h1><h3>' . LocalizationUtility::translate('msg.abcheckmail', $this->extKey) . '</h3>';
        }
        $this->flexisunsubscribed = $this->settings['flexisunsubscribed'];
        if ($this->flexisunsubscribed == null || $this->flexisunsubscribed == '') {
            $this->flexisunsubscribed = '<h1>' . LocalizationUtility::translate('msg.error', $this->extKey) . '</h1><h3>' . LocalizationUtility::translate('msg.abalready', $this->extKey) . '</h3>';
        }
        $this->flexleave = $this->settings['flexleave'];
        if ($this->flexleave == null || $this->flexleave == '') {
            $this->flexleave = '<h1>' . LocalizationUtility::translate('msg.error', $this->extKey) . '</h1><h3>' . LocalizationUtility::translate('msg.abnomail', $this->extKey) . '</h3>';
        }
        $this->flexunsubscribe = $this->settings['flexunsubscribe'];
        if ($this->flexunsubscribe == null || $this->flexunsubscribe == '') {
            $this->flexunsubscribe = '<h1>' . LocalizationUtility::translate('msg.thx', $this->extKey) . '</h1><h3>' . LocalizationUtility::translate('msg.abconfirm', $this->extKey) . '</h3>';
        }
    }

    public function initializeUpdateAction()
    {
        $propertyMappingConfiguration = $this->arguments['person']->getPropertyMappingConfiguration();
        $propertyMappingConfiguration->allowAllProperties('category');
    }

    /**
     * action newShort
     */
    public function newShortAction(): ResponseInterface
    {
        $this->view->assign('showpage', $this->settings['flexshowpage']);
        return $this->htmlResponse();
    }

    /**
     * @param Person|null $newPerson
     */
    public function newAction(Person $newPerson = null): ResponseInterface
    {
        $kats = $this->categoryRepository->findAll();
        $this->view->assign('kats', $kats);

        if (!($newPerson instanceof Person)) {
            $newPerson = new Person();
        }
        $this->view->assign('newPerson', $newPerson);
        return $this->htmlResponse();
    }

    /**
     * action create
     *
     * @param Person $newPerson
     */
    #[Extbase\Validate(['param' => 'newPerson', 'validator' => HoneyPotValidator::class])]
    #[Extbase\Validate(['param' => 'newPerson', 'validator' => TermsValidator::class])]
    #[Extbase\Validate(['param' => 'newPerson', 'validator' => NameValidator::class])]
    #[Extbase\Validate(['param' => 'newPerson', 'validator' => EmailValidator::class])]
    #[Extbase\Validate(['param' => 'newPerson', 'validator' => EmailAgainValidator::class])]
    public function createAction(Person $newPerson): ResponseInterface
    {
        $hash = $newPerson->getEmail() . time();
        $newPerson->setToken(md5($hash));

        // Set language uid
        /** @var Context $context */
        $context = GeneralUtility::makeInstance(Context::class);
        $language = $context->getPropertyFromAspect('language', 'id');
        if (isset($language) && $language !== 0) {
            $newPerson->set_languageUid($language);
        }
        $this->personRepository->add($newPerson);
        $this->persistenceManager->persistAll();

        $langhelp = LocalizationUtility::translate('log.create', $this->extKey);
        $this->logService->insertLog($newPerson->getUid(), $newPerson->getEmail(), $newPerson->getFirstname(), $newPerson->getLastname(), 'create', $langhelp, '', 1);

        if ($this->settings['options']['doubleOptIn'] == 1) {
            $this->mailService->doBuildLinkMail(true, $this->sitename, $this->settings['optinPageUid'] ? $this->settings['optinPageUid'] : $this->settings['options']['path'], $newPerson);
            return (new ForwardResponse('text'))->withArguments(['text' => $this->flexcheckmail]);
        }
        $this->personService->doActivate($newPerson, $this->settings['options']['sendInMail'], $this->settings['options']['mail'], 'log.createsuccess', 'create');
        return (new ForwardResponse('text'))->withArguments(['text' => $this->flexconfirm]);
    }

    /**
     * action activate
     */
    public function activateAction(): ResponseInterface
    {
        $vars = $this->request->getArguments();
        $pers = $this->personRepository->findOneByToken($vars['token']);
        if ($pers != null) {
            $this->personService->doActivate($pers, $this->settings['options']['sendInMail'], $this->settings['options']['mail'], 'log.activate', 'activate');
            return (new ForwardResponse('text'))->withArguments(['text' => $this->flexconfirm]);
        }
        $this->logService->insertLog(
            0,
            '-',
            '-',
            '-',
            'activate',
            LocalizationUtility::translate('log.activate', $this->extKey),
            LocalizationUtility::translate('log.activatefail', $this->extKey),
            0
        );
        return (new ForwardResponse('text'))->withArguments(['text' => $this->flexerr]);

        return $this->htmlResponse();
    }

    /**
     * action newLeave
     */
    public function newLeaveAction(): ResponseInterface
    {
        $mail = trim($_GET['mail']);
        if ($mail != null && $mail != '') {
            return (new ForwardResponse('leave'))->withArguments(['mail' => $mail]);
        }
        return $this->htmlResponse();
    }

    /**
     * action leave
     *
     * @param string $mail
     */
    public function leaveAction($mail = '')
    {
        // Don't take the easy route - do it the hard way
        if ($mail == '') {
            $mail = trim($this->request->getArguments()['mail']);
        }
        if ($mail == '') {
            $mail = trim($_POST['mail']);
        }

        $pers = $this->personRepository->findOneByEmail($mail);

        $opt = $this->settings['options']['doubleOptOut'];
        $path = $this->settings['options']['pathout'];
        $site = $this->settings['options']['site'];
        $sendOutMail = $this->settings['options']['sendOutMail'];
        $mail = $this->settings['options']['mail'];

        if ($pers != null) {
            if ($pers->isUnsubscribed() == 0) {
                if ($opt == 1) {
                    $this->mailService->doBuildLinkMail(false, $site, $path, $pers);
                    return (new ForwardResponse('text'))->withArguments(['text' => $this->flexcheckmailleave]);
                }
                $this->personService->doUnsubscribe($pers, $sendOutMail, $mail, 'log.leavesuccess', 'leave');
                return (new ForwardResponse('text'))->withArguments(['text' => $this->flexunsubscribe]);
            }
            $langhelp = LocalizationUtility::translate('log.leavewant', $this->extKey);
            $langhelp2 = LocalizationUtility::translate('log.leavealready', $this->extKey);
            $this->logService->insertLog($pers->getUid(), $pers->getEmail(), $pers->getFirstname(), $pers->getLastname(), 'leave', "$langhelp", "$langhelp2", 0);
            return (new ForwardResponse('text'))->withArguments(['text' => $this->flexisunsubscribed]);
        }
        $langhelp = LocalizationUtility::translate('log.leavewant', $this->extKey);
        $langhelp2 = LocalizationUtility::translate('log.leavenot', $this->extKey);
        $this->logService->insertLog(0, $mail, '-', '-', 'leave', "$langhelp", "$langhelp2", 0);
        return (new ForwardResponse('text'))->withArguments(['text' => $this->flexleave]);
    }

    /**
     * action unsubscribe
     */
    public function unsubscribeAction(): ResponseInterface
    {
        $sendOutMail = $this->settings['options']['sendOutMail'];
        $mail = $this->settings['options']['mail'];
        $vars = $this->request->getArguments();
        $pers = $this->personRepository->findOneByToken($vars['token']);
        if ($pers != null) {
            $this->personService->doUnsubscribe($pers, $sendOutMail, $mail, 'log.unsubscribe', 'unsubscribe');
            return (new ForwardResponse('text'))->withArguments(['text' => $this->flexunsubscribe]);
        }
        return (new ForwardResponse('leave'))->withArguments(['mail' => '']);

        return $this->htmlResponse();
    }

    /**
     * action text
     *
     * @param string $text
     */
    public function textAction($text): ResponseInterface
    {
        $this->view->assign('message', $text);
        return $this->htmlResponse();
    }
}

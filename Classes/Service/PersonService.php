<?php

namespace Personmanager\PersonManager\Service;

use InvalidArgumentException;
use Personmanager\PersonManager\Domain\Model\Person;
use Personmanager\PersonManager\Domain\Repository\PersonRepository;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class PersonService
{
    /**
     * @var string
     */
    protected $extKey = 'person_manager';

    /**
     * settings
     *
     * @var array
     */
    protected $settings = [];

    /**
     * logService
     *
     * @var LogService
     */
    protected $logService;

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

    /**
     * personRepository
     *
     * @var PersonRepository
     */
    protected $personRepository;

    /**
     * @param LogService $logService
     */
    public function injectLogService(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * @param MailService $mailService
     */
    public function injectMailService(MailService $mailService)
    {
        $this->mailService = $mailService;
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

    /**
     * Set settings
     *
     * @param  array  $settings  settings
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        $this->mailService->setSettings($settings);
    }

    /**
     * @param Person $pers
     * @param mixed $sendInMail
     * @param string $to
     * @param string $msgKey
     * @param string $log
     * @throws InvalidArgumentException
     * @throws IllegalObjectTypeException
     */
    public function doActivate(Person $pers, $sendInMail, string $to, string $msgKey, string $log)
    {
        $pers->setConfirmed(true);
        $pers->setActive(true);
        $this->personRepository->update($pers);
        $this->persistenceManager->persistAll();

        if ($sendInMail == 1) {
            $this->mailService->doBuildActivateMail($pers, $msgKey, $to);
        }

        $this->logService->insertLog(
            $pers->getUid(),
            $pers->getEmail(),
            $pers->getFirstname(),
            $pers->getLastname(),
            $log,
            LocalizationUtility::translate($msgKey, $this->extKey),
            '',
            1
        );
    }

    /**
     * @param Person $pers
     * @param mixed $sendOutMail
     * @param string $to
     * @param string $msgKey
     * @param string $log
     * @throws InvalidArgumentException
     * @throws IllegalObjectTypeException
     */
    public function doUnsubscribe(Person $pers, $sendOutMail, string $to, string $msgKey, string $log)
    {
        $pers->setUnsubscribed(true);
        $this->personRepository->update($pers);
        $this->persistenceManager->persistAll();

        if ($sendOutMail == 1) {
            $this->mailService->doBuildUnsubscribeMail($pers, $msgKey, $to);
        }

        $this->logService->insertLog(
            $pers->getUid(),
            $pers->getEmail(),
            $pers->getFirstname(),
            $pers->getLastname(),
            $log,
            LocalizationUtility::translate($msgKey, $this->extKey),
            '',
            1
        );
    }
}

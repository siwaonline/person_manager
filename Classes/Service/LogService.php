<?php

namespace Personmanager\PersonManager\Service;

use Personmanager\PersonManager\Domain\Model\Log;
use Personmanager\PersonManager\Domain\Repository\LogRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class LogService
{
    /**
     * logRepository
     *
     * @var LogRepository
     */
    protected $logRepository;

    /**
     * @var PersistenceManager
     */
    protected $persistenceManager;

    public function __construct()
    {
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
    }

    /**
     * @param CategoryRepository $logRepository
     */
    public function injectLogRepository(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * @param int $person
     * @param string $email
     * @param string $fname
     * @param string $lname
     * @param string $action
     * @param string $detail
     * @param string $fehler
     * @param int $success
     * @throws IllegalObjectTypeException
     */
    public function insertLog($person = 0, $email = '', $fname = '', $lname = '', $action = '', $detail = '', $fehler = '', $success = 0)
    {
        $newLog = new Log();

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
}

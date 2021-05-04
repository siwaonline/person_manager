<?php

namespace Personmanager\PersonManager\Service;

use Personmanager\PersonManager\Domain\Repository\LogRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class LogService
{

    /**
     * logRepository
     *
     * @var LogRepository
     */
    protected $logRepository = NULL;

    /**
     * 
     * @var PersistenceManager
     */
    protected $persistenceManager;

    function __construct(){
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
    }

    /**
     * @param CategoryRepository $logRepository
     */
    public function injectLogRepository(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
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
}
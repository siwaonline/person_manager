<?php

namespace Personmanager\PersonManager\Domain\Validator\Person;

use Personmanager\PersonManager\Domain\Repository\PersonRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class EmailAgainValidator extends AbstractValidator
{
   protected function isValid($person)
   {
    if($person instanceof \Personmanager\PersonManager\Domain\Model\Person){
        $om = GeneralUtility::makeInstance(ObjectManager::class);
        $personRepository = $om->get(PersonRepository::class);

        $oldMail = $personRepository->findOneByEmail($person->getEmail());
        if ($oldMail != NULL) {
            if ($oldMail->isUnsubscribed() == 0) {
               $langhelp = LocalizationUtility::translate('error.emailagain', 'person_manager');
               $this->addError($langhelp, time());
            }
        }
    }
   }
}
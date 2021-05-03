<?php

namespace Personmanager\PersonManager\Domain\Validator\Person;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class NameValidator extends AbstractValidator
{
   protected function isValid($person)
   {
       if($person instanceof \Personmanager\PersonManager\Domain\Model\Person){
           if ($person->getLastname() == "" || $person->getLastname() == NULL || $person->getFirstname() == "" || $person->getFirstname() == NULL) {
               $langhelp = LocalizationUtility::translate('error.name', 'person_manager');
               $this->addError($langhelp, time());
            }
       }
   }
}
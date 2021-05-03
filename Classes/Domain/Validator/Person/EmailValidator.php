<?php

namespace Personmanager\PersonManager\Domain\Validator\Person;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class EmailValidator extends AbstractValidator
{
   protected function isValid($person)
   {
       if($person instanceof \Personmanager\PersonManager\Domain\Model\Person){
           if (!GeneralUtility::validEmail($person->getEmail())) {
               $langhelp = LocalizationUtility::translate('error.email', 'person_manager');
               $this->addError($langhelp, time());
            }
       }
   }
}
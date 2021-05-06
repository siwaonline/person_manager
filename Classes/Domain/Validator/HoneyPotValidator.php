<?php

namespace Personmanager\PersonManager\Domain\Validator;

use Personmanager\PersonManager\Utility\FormUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class HoneyPotValidator extends AbstractValidator
{
   protected function isValid($value)
   {
       $honey = FormUtility::_GPmerged()['__hp'];

       if ($honey != "" && $honey != NULL) {
        $this->addError($this->translateErrorMessage('error.spam', 'person_manager'), 1620285835);
    }
   }
}
<?php

namespace Personmanager\PersonManager\Domain\Validator\Person;

use Personmanager\PersonManager\Domain\Model\Person;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class NameValidator extends AbstractValidator
{
    protected function isValid($person): void
    {
        if ($person instanceof Person) {
            if ($person->getLastname() == '' || $person->getLastname() == null || $person->getFirstname() == '' || $person->getFirstname() == null) {
                $this->addError($this->translateErrorMessage('error.name', 'person_manager'), 1620285836);
            }
        }
    }
}

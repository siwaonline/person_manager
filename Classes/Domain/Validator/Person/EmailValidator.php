<?php

namespace Personmanager\PersonManager\Domain\Validator\Person;

use Personmanager\PersonManager\Domain\Model\Person;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class EmailValidator extends AbstractValidator
{
    protected function isValid($person): void
    {
        if ($person instanceof Person) {
            if (!GeneralUtility::validEmail($person->getEmail())) {
                $this->addError($this->translateErrorMessage('error.email', 'person_manager'), 1620285837);
            }
        }
    }
}

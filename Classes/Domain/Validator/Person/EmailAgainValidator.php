<?php

namespace Personmanager\PersonManager\Domain\Validator\Person;

use Personmanager\PersonManager\Domain\Model\Person;
use Personmanager\PersonManager\Domain\Repository\PersonRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class EmailAgainValidator extends AbstractValidator
{
    protected function isValid($person): void
    {
        if ($person instanceof Person) {
            $personRepository = GeneralUtility::makeInstance(PersonRepository::class);

            $oldMail = $personRepository->findOneByEmail($person->getEmail());
            if ($oldMail != null) {
                if ($oldMail->isUnsubscribed() == 0) {
                    $this->addError($this->translateErrorMessage('error.emailagain', 'person_manager'), 1620285838);
                } else {
                    $personRepository->remove($oldMail);
                }
            }
        }
    }
}

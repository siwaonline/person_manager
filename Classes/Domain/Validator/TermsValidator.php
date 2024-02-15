<?php

namespace Personmanager\PersonManager\Domain\Validator;

use Personmanager\PersonManager\Utility\FormUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class TermsValidator extends AbstractValidator
{
    protected function isValid($value): void
    {
        /* @var $objectManager \TYPO3\CMS\Extbase\Object\ObjectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /* @var $configurationManager \TYPO3\CMS\Extbase\Configuration\ConfigurationManager */
        $configurationManager = $objectManager->get(ConfigurationManager::class);
        $settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        $terms = FormUtility::_GPmerged()['terms'];

        $termVar = $settings['variables.']['terms'];

        if ($termVar) {
            if ($terms != '1' || $terms != 1) {
                $this->addError($this->translateErrorMessage('error.terms', 'person_manager'), 1620285834);
            }
        }
    }
}

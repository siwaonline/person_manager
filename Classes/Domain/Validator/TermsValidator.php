<?php

namespace Personmanager\PersonManager\Domain\Validator;

use Personmanager\PersonManager\Utility\FormUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class TermsValidator extends AbstractValidator
{
   protected function isValid($value)
   {
       $terms = FormUtility::_GPmerged()['terms'];


       $termVar = $GLOBALS['TSFE']->tmpl->setup["plugin."]["tx_personmanager."]["variables."]["terms"];

       if ($termVar) {
            if ($terms != "1" || $terms != 1) {
                $langhelp = LocalizationUtility::translate('error.terms', 'person_manager');
                $this->addError($langhelp, time());
            }
        }
   }
}
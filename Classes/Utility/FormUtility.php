<?php

namespace Personmanager\PersonManager\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class FormUtility{


    public static function _GPmerged($parameter = 'tx_personmanager_personmanagerfront')
    {
        return GeneralUtility::_GPmerged($parameter);
    }

}
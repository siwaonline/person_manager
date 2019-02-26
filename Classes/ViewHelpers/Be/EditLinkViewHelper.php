<?php

namespace Personmanager\PersonManager\ViewHelpers\Be;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
class EditLinkViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * Initialize arguments
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();
        $this->registerTagAttribute('name', 'string', 'Specifies the name of an anchor');
        $this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
        $this->registerTagAttribute('returnAction', 'string', 'Specifies which action to return to');
        $this->registerArgument('parameters', 'string', 'Parameters to pass');
    }

    /**
     * Crafts a link to edit a database record or create a new one
     *
     * @return string The <a> tag
     * @see \TYPO3\CMS\Backend\Utility::editOnClick()
     */
    public function render()
    {
        /* @var \TYPO3\CMS\Backend\Routing\UriBuilder $uriBuilder */
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $uri = $uriBuilder->buildUriFromRoute('record_edit') . "&" . $this->arguments['parameters'];
        $returnAction = $this->arguments['returnAction'] ? $this->arguments['returnAction'] : 'list';

        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $uriBuilder = $objectManager->get('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder');
        $uriBuilder->initializeObject();
        $returnUri = $uriBuilder->reset()->setAddQueryString(true)->setArguments(array(
            'M' => 'web_PersonManagerPersonmanagerback',
            'tx_personmanager_web_personmanagerpersonmanagerback' => array(
                'action' => $returnAction
            ),
        ))->buildBackendUri();
        $returnUri = str_replace("&", "%26", $returnUri);
        $uri .= '&returnUrl=' . $returnUri;

        $this->tag->addAttribute('href', $uri);
        $this->tag->setContent($this->renderChildren());
        $this->tag->forceClosingTag(TRUE);
        return $this->tag->render();
    }
}

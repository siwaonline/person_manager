<?php

namespace Personmanager\PersonManager\Service;

use InvalidArgumentException;
use Personmanager\PersonManager\Domain\Model\Person;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\Mailer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class MailService
{

    /**
     * 
     * @var string
     */
    protected $extKey = 'person_manager';

    /**
     * settings
     *
     * @var array
     */
    protected $settings = [];

    /**
     * logService
     *
     * @var LogService
     */
    protected $logService = NULL;

    /**
     * @param LogService $logService
     */
    public function injectLogService(LogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Set settings
     *
     * @param  array  $settings  settings
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * 
     * @param bool $new 
     * @param string $site 
     * @param string $path 
     * @param Person $person 
     * @return void 
     * @throws InvalidArgumentException 
     * @throws IllegalObjectTypeException 
     */
    public function doBuildLinkMail(bool $new, string $site, string $path, Person $person) {
        $mail = GeneralUtility::makeInstance(FluidEmail::class);
        $mail
            ->subject($site . ": " . LocalizationUtility::translate('mail.confirmdata', $this->extKey))
            ->setTemplate('DoBuildLinkMail')
            ->assignMultiple([
                'sitename' => $site,
                'signature' => $this->signature,
                'new' => $new,
                'link' => [
                    'pageUid' => $path,
                    'token' => $person->getToken(),
                ]
            ])
            ->to($person->getEmail());

        $this->_sendMail($mail);


        $langhelp = LocalizationUtility::translate($new ? 'log.createmail' : 'log.leavemail', $this->extKey);
        $this->logService->insertLog($person->getUid(), $person->getEmail(), $person->getFirstname(), $person->getLastname(), $new ? "create" : "leave", "$langhelp", "", 1);
    }

    /**
     * 
     * @param Person $person 
     * @param string $msgKey 
     * @param string $to 
     * @return void 
     * @throws InvalidArgumentException 
     */
    public function doBuildUnsubscribeMail(Person $person, string $msgKey, string $to) {
        $mail = GeneralUtility::makeInstance(FluidEmail::class);
        $mail
            ->subject(LocalizationUtility::translate('mail.deregistration', $this->extKey) . " " . $person->getEmail())
            ->setTemplate('DoUnsubscribe')
            ->assign('user', $person)
            ->assign('msgKey', $msgKey)
            ->to($to);

        $mail->from(new Address($this->settings["options"]["mail"], $this->settings["options"]["site"]));
        $this->mailService->_sendMail($mail);
    }

    /**
     * 
     * @param Person $person 
     * @param string $msgKey 
     * @param string $to 
     * @return void 
     * @throws InvalidArgumentException 
     */
    public function doBuildActivateMail(Person $person, string $msgKey, string $to) {
        $mail = GeneralUtility::makeInstance(FluidEmail::class);
        $mail
            ->subject(LocalizationUtility::translate('mail.registration', $this->extKey) . " " . $person->getEmail())
            ->setTemplate('DoActivate')
            ->assign('user', $person)
            ->assign('msgKey', $msgKey)
            ->to($to);

        $mail->from(new Address($this->settings["options"]["mail"], $this->settings["options"]["site"]));

        $this->mailService->_sendMail($mail);
    }

    /**
     * 
     * @param FluidEmail $mail 
     * @return void 
     * @throws InvalidArgumentException 
     */
    private function _sendMail(FluidEmail $mail)
    {
        GeneralUtility::makeInstance(Mailer::class)->send($mail);
    }
}

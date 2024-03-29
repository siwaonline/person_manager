<?php

namespace Personmanager\PersonManager\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Log
 */
class Log extends AbstractEntity
{
    /**
     * person
     *
     * @var int
     */
    protected $person = 0;

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * firstname
     *
     * @var string
     */
    protected $firstname = '';

    /**
     * lastname
     *
     * @var string
     */
    protected $lastname = '';

    /**
     * action
     *
     * @var string
     */
    protected $action = '';

    /**
     * detail
     *
     * @var string
     */
    protected $detail = '';

    /**
     * fehler
     *
     * @var string
     */
    protected $fehler = '';

    /**
     * success
     *
     * @var bool
     */
    protected $success = false;

    /**
     * Returns the action
     *
     * @return string $action
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Returns the detail
     *
     * @return string $detail
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Sets the detail
     *
     * @param string $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Returns the person
     *
     * @return int $person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Sets the person
     *
     * @param int $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * Returns the fehler
     *
     * @return string $fehler
     */
    public function getFehler()
    {
        return $this->fehler;
    }

    /**
     * Sets the fehler
     *
     * @param string $fehler
     */
    public function setFehler($fehler)
    {
        $this->fehler = $fehler;
    }

    /**
     * Returns the success
     *
     * @return bool $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Sets the success
     *
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * crdate
     *
     * @var string
     */
    protected $crdate;

    /**
     * Returns the crdate
     *
     * @return string $crdate
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Sets the crdate
     *
     * @param string $crdate
     */
    public function setCrdate($crdate)
    {
        $this->crdate = $crdate;
    }
}

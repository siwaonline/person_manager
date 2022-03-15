<?php
namespace Personmanager\PersonManager\Domain\Model;

use DateTime;

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
 * Person
 */
class Person extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * salutation
	 *
	 * @var integer
	 */
	protected $salutation = 0;

	/**
	 * titel
	 *
	 * @var string
	 */
	protected $titel = '';

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
	 * nachgtitel
	 *
	 * @var string
	 */
	protected $nachgtitel = '';

	/**
	 * email
	 *
	 * @var string
	 */
	protected $email = '';

	/**
	 * geb
	 *
	 * @var string
	 */
	protected $geb = '';

	/**
	 * tel
	 *
	 * @var string
	 */
	protected $tel = '';

	/**
	 * company
	 *
	 * @var string
	 */
	protected $company = '';

	/**
	 * active
	 *
	 * @var boolean
	 */
	protected $active = FALSE;

	/**
	 * confirmed
	 *
	 * @var boolean
	 */
	protected $confirmed = FALSE;

	/**
	 * unsubscribed
	 *
	 * @var boolean
	 */
	protected $unsubscribed = FALSE;

	/**
	 * token
	 *
	 * @var string
	 */
	protected $token = '';

	/**
	 * frtxt0
	 *
	 * @var string
	 */
	protected $frtxt0 = '';

	/**
	 * frtxt1
	 *
	 * @var string
	 */
	protected $frtxt1 = '';

	/**
	 * frtxt2
	 *
	 * @var string
	 */
	protected $frtxt2 = '';

	/**
	 * frtxt3
	 *
	 * @var string
	 */
	protected $frtxt3 = '';

	/**
	 * frtxt4
	 *
	 * @var string
	 */
	protected $frtxt4 = '';

	/**
	 * frtxt5
	 *
	 * @var string
	 */
	protected $frtxt5 = '';

	/**
	 * frtxt6
	 *
	 * @var string
	 */
	protected $frtxt6 = '';

	/**
	 * frtxt7
	 *
	 * @var string
	 */
	protected $frtxt7 = '';

	/**
	 * frtxt8
	 *
	 * @var string
	 */
	protected $frtxt8 = '';

	/**
	 * frtxt9
	 *
	 * @var string
	 */
	protected $frtxt9 = '';

	/**
	 * category
	 *
	 * @var \Personmanager\PersonManager\Domain\Model\Category
	 */
	protected $category = NULL;


	/**
	 * Sets the crdate
	 *
	 * @param DateTime $crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Returns the firstname
	 *
	 * @return string $firstname
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * Sets the firstname
	 *
	 * @param string $firstname
	 * @return void
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * Returns the lastname
	 *
	 * @return string $lastname
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * Sets the lastname
	 *
	 * @param string $lastname
	 * @return void
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * Returns the email
	 *
	 * @return string $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Returns the salutation
	 *
	 * @return integer $salutation
	 */
	public function getSalutation() {
		return $this->salutation;
	}

	/**
	 * Sets the salutation
	 *
	 * @param integer $salutation
	 * @return void
	 */
	public function setSalutation($salutation) {
		$this->salutation = $salutation;
	}

	/**
	 * Returns the titel
	 *
	 * @return string $titel
	 */
	public function getTitel() {
		return $this->titel;
	}

	/**
	 * Sets the titel
	 *
	 * @param string $titel
	 * @return void
	 */
	public function setTitel($titel) {
		$this->titel = $titel;
	}

	/**
	 * Returns the nachgtitel
	 *
	 * @return string $nachgtitel
	 */
	public function getNachgtitel() {
		return $this->nachgtitel;
	}

	/**
	 * Sets the nachgtitel
	 *
	 * @param string $nachgtitel
	 * @return void
	 */
	public function setNachgtitel($nachgtitel) {
		$this->nachgtitel = $nachgtitel;
	}

	/**
	 * Returns the geb
	 *
	 * @return string $geb
	 */
	public function getGeb() {
		return $this->geb;
	}

	/**
	 * Sets the geb
	 *
	 * @param string $geb
	 * @return void
	 */
	public function setGeb($geb) {
		$this->geb = $geb;
	}

	/**
	 * Returns the tel
	 *
	 * @return string $tel
	 */
	public function getTel() {
		return $this->tel;
	}

	/**
	 * Sets the tel
	 *
	 * @param string $tel
	 * @return void
	 */
	public function setTel($tel) {
		$this->tel = $tel;
	}

	/**
	 * Returns the company
	 *
	 * @return string $company
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * Sets the company
	 *
	 * @param string $company
	 * @return void
	 */
	public function setCompany($company) {
		$this->company = $company;
	}

	/**
	 * Returns the active
	 *
	 * @return boolean $active
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * Sets the active
	 *
	 * @param boolean $active
	 * @return void
	 */
	public function setActive($active) {
		$this->active = $active;
	}

	/**
	 * Returns the boolean state of active
	 *
	 * @return boolean
	 */
	public function isActive() {
		return $this->active;
	}

	/**
	 * Returns the confirmed
	 *
	 * @return boolean $confirmed
	 */
	public function getConfirmed() {
		return $this->confirmed;
	}

	/**
	 * Sets the confirmed
	 *
	 * @param boolean $confirmed
	 * @return void
	 */
	public function setConfirmed($confirmed) {
		$this->confirmed = $confirmed;
	}

	/**
	 * Returns the boolean state of confirmed
	 *
	 * @return boolean
	 */
	public function isConfirmed() {
		return $this->confirmed;
	}

	/**
	 * Returns the unsubscribed
	 *
	 * @return boolean $unsubscribed
	 */
	public function getUnsubscribed() {
		return $this->unsubscribed;
	}

	/**
	 * Sets the unsubscribed
	 *
	 * @param boolean $unsubscribed
	 * @return void
	 */
	public function setUnsubscribed($unsubscribed) {
		$this->unsubscribed = $unsubscribed;
	}

	/**
	 * Returns the boolean state of unsubscribed
	 *
	 * @return boolean
	 */
	public function isUnsubscribed() {
		return $this->unsubscribed;
	}

	/**
	 * Returns the token
	 *
	 * @return string $token
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * Sets the token
	 *
	 * @param string $token
	 * @return void
	 */
	public function setToken($token) {
		$this->token = $token;
	}

	/**
	 * Returns the frtxt0
	 *
	 * @return string $frtxt0
	 */
	public function getFrtxt0() {
		return $this->frtxt0;
	}

	/**
	 * Sets the frtxt0
	 *
	 * @param string $frtxt0
	 * @return void
	 */
	public function setFrtxt0($frtxt0) {
		$this->frtxt0 = $frtxt0;
	}

	/**
	 * Returns the frtxt1
	 *
	 * @return string $frtxt1
	 */
	public function getFrtxt1() {
		return $this->frtxt1;
	}

	/**
	 * Sets the frtxt1
	 *
	 * @param string $frtxt1
	 * @return void
	 */
	public function setFrtxt1($frtxt1) {
		$this->frtxt1 = $frtxt1;
	}

	/**
	 * Returns the frtxt2
	 *
	 * @return string $frtxt2
	 */
	public function getFrtxt2() {
		return $this->frtxt2;
	}

	/**
	 * Sets the frtxt2
	 *
	 * @param string $frtxt2
	 * @return void
	 */
	public function setFrtxt2($frtxt2) {
		$this->frtxt2 = $frtxt2;
	}

	/**
	 * Returns the frtxt3
	 *
	 * @return string $frtxt3
	 */
	public function getFrtxt3() {
		return $this->frtxt3;
	}

	/**
	 * Sets the frtxt3
	 *
	 * @param string $frtxt3
	 * @return void
	 */
	public function setFrtxt3($frtxt3) {
		$this->frtxt3 = $frtxt3;
	}

	/**
	 * Returns the frtxt4
	 *
	 * @return string $frtxt4
	 */
	public function getFrtxt4() {
		return $this->frtxt4;
	}

	/**
	 * Sets the frtxt4
	 *
	 * @param string $frtxt4
	 * @return void
	 */
	public function setFrtxt4($frtxt4) {
		$this->frtxt4 = $frtxt4;
	}

	/**
	 * Returns the frtxt5
	 *
	 * @return string $frtxt5
	 */
	public function getFrtxt5() {
		return $this->frtxt5;
	}

	/**
	 * Sets the frtxt5
	 *
	 * @param string $frtxt5
	 * @return void
	 */
	public function setFrtxt5($frtxt5) {
		$this->frtxt5 = $frtxt5;
	}

	/**
	 * Returns the frtxt6
	 *
	 * @return string $frtxt6
	 */
	public function getFrtxt6() {
		return $this->frtxt6;
	}

	/**
	 * Sets the frtxt6
	 *
	 * @param string $frtxt6
	 * @return void
	 */
	public function setFrtxt6($frtxt6) {
		$this->frtxt6 = $frtxt6;
	}

	/**
	 * Returns the frtxt7
	 *
	 * @return string $frtxt7
	 */
	public function getFrtxt7() {
		return $this->frtxt7;
	}

	/**
	 * Sets the frtxt7
	 *
	 * @param string $frtxt7
	 * @return void
	 */
	public function setFrtxt7($frtxt7) {
		$this->frtxt7 = $frtxt7;
	}

	/**
	 * Returns the frtxt8
	 *
	 * @return string $frtxt8
	 */
	public function getFrtxt8() {
		return $this->frtxt8;
	}

	/**
	 * Sets the frtxt8
	 *
	 * @param string $frtxt8
	 * @return void
	 */
	public function setFrtxt8($frtxt8) {
		$this->frtxt8 = $frtxt8;
	}

	/**
	 * Returns the frtxt9
	 *
	 * @return string $frtxt9
	 */
	public function getFrtxt9() {
		return $this->frtxt9;
	}

	/**
	 * Sets the frtxt9
	 *
	 * @param string $frtxt9
	 * @return void
	 */
	public function setFrtxt9($frtxt9) {
		$this->frtxt9 = $frtxt9;
	}

	/**
	 * Returns the category
	 *
	 * @return \Personmanager\PersonManager\Domain\Model\Category $category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Sets the category
	 *
	 * @param \Personmanager\PersonManager\Domain\Model\Category $category
	 * @return void
	 */
	public function setCategory(\Personmanager\PersonManager\Domain\Model\Category $category = null) {
	    if($category)$this->category = $category;
	}

	/**
	 * crdate
	 *
	 * @var DateTime
	 */
	protected $crdate;

	/**
	 * Returns the crdate
	 *
	 * @return DateTime $crdate
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Sets the property
	 *
	 * @return void
	 */
	public function setProperty($prop, $val){
		$this->{$prop} = $val;
	}

	/**
	 * Returns the property
	 *
	 * @return mixed
	 */
	public function getProperty($prop){
		return $this->{$prop};
	}

}

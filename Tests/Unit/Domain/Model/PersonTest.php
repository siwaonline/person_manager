<?php

namespace Personmanager\PersonManager\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \Personmanager\PersonManager\Domain\Model\Person.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PersonTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \Personmanager\PersonManager\Domain\Model\Person
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \Personmanager\PersonManager\Domain\Model\Person();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getFirstnameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFirstname()
		);
	}

	/**
	 * @test
	 */
	public function setFirstnameForStringSetsFirstname() {
		$this->subject->setFirstname('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'firstname',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLastnameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getLastname()
		);
	}

	/**
	 * @test
	 */
	public function setLastnameForStringSetsLastname() {
		$this->subject->setLastname('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'lastname',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getEmailReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getEmail()
		);
	}

	/**
	 * @test
	 */
	public function setEmailForStringSetsEmail() {
		$this->subject->setEmail('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'email',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getSalutationReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getSalutation()
		);
	}

	/**
	 * @test
	 */
	public function setSalutationForIntegerSetsSalutation() {
		$this->subject->setSalutation(12);

		$this->assertAttributeEquals(
			12,
			'salutation',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTitelReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTitel()
		);
	}

	/**
	 * @test
	 */
	public function setTitelForStringSetsTitel() {
		$this->subject->setTitel('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'titel',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getNachgtitelReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getNachgtitel()
		);
	}

	/**
	 * @test
	 */
	public function setNachgtitelForStringSetsNachgtitel() {
		$this->subject->setNachgtitel('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'nachgtitel',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getGebReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getGeb()
		);
	}

	/**
	 * @test
	 */
	public function setGebForStringSetsGeb() {
		$this->subject->setGeb('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'geb',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTelReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTel()
		);
	}

	/**
	 * @test
	 */
	public function setTelForStringSetsTel() {
		$this->subject->setTel('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'tel',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCompanyReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getCompany()
		);
	}

	/**
	 * @test
	 */
	public function setCompanyForStringSetsCompany() {
		$this->subject->setCompany('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'company',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getActiveReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getActive()
		);
	}

	/**
	 * @test
	 */
	public function setActiveForBooleanSetsActive() {
		$this->subject->setActive(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'active',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getConfirmedReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getConfirmed()
		);
	}

	/**
	 * @test
	 */
	public function setConfirmedForBooleanSetsConfirmed() {
		$this->subject->setConfirmed(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'confirmed',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getUnsubscribedReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getUnsubscribed()
		);
	}

	/**
	 * @test
	 */
	public function setUnsubscribedForBooleanSetsUnsubscribed() {
		$this->subject->setUnsubscribed(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'unsubscribed',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTokenReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getToken()
		);
	}

	/**
	 * @test
	 */
	public function setTokenForStringSetsToken() {
		$this->subject->setToken('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'token',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt0ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt0()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt0ForStringSetsFrtxt0() {
		$this->subject->setFrtxt0('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt0',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt1ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt1()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt1ForStringSetsFrtxt1() {
		$this->subject->setFrtxt1('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt1',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt2ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt2()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt2ForStringSetsFrtxt2() {
		$this->subject->setFrtxt2('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt2',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt3ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt3()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt3ForStringSetsFrtxt3() {
		$this->subject->setFrtxt3('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt3',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt4ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt4()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt4ForStringSetsFrtxt4() {
		$this->subject->setFrtxt4('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt4',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt5ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt5()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt5ForStringSetsFrtxt5() {
		$this->subject->setFrtxt5('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt5',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt6ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt6()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt6ForStringSetsFrtxt6() {
		$this->subject->setFrtxt6('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt6',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt7ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt7()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt7ForStringSetsFrtxt7() {
		$this->subject->setFrtxt7('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt7',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt8ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt8()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt8ForStringSetsFrtxt8() {
		$this->subject->setFrtxt8('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt8',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getFrtxt9ReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFrtxt9()
		);
	}

	/**
	 * @test
	 */
	public function setFrtxt9ForStringSetsFrtxt9() {
		$this->subject->setFrtxt9('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'frtxt9',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCategoryReturnsInitialValueForCategory() {
		$this->assertEquals(
			NULL,
			$this->subject->getCategory()
		);
	}

	/**
	 * @test
	 */
	public function setCategoryForCategorySetsCategory() {
		$categoryFixture = new \Personmanager\PersonManager\Domain\Model\Category();
		$this->subject->setCategory($categoryFixture);

		$this->assertAttributeEquals(
			$categoryFixture,
			'category',
			$this->subject
		);
	}
}

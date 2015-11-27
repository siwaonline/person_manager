<?php
namespace Personmanager\PersonManager\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 
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
 * Test case for class Personmanager\PersonManager\Controller\PersonController.
 *
 */
class PersonControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \Personmanager\PersonManager\Controller\PersonController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('Personmanager\\PersonManager\\Controller\\PersonController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllPersonsFromRepositoryAndAssignsThemToView() {

		$allPersons = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$personRepository = $this->getMock('Personmanager\\PersonManager\\Domain\\Repository\\PersonRepository', array('findAll'), array(), '', FALSE);
		$personRepository->expects($this->once())->method('findAll')->will($this->returnValue($allPersons));
		$this->inject($this->subject, 'personRepository', $personRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('persons', $allPersons);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenPersonToView() {
		$person = new \Personmanager\PersonManager\Domain\Model\Person();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('person', $person);

		$this->subject->showAction($person);
	}

	/**
	 * @test
	 */
	public function newActionAssignsTheGivenPersonToView() {
		$person = new \Personmanager\PersonManager\Domain\Model\Person();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('newPerson', $person);
		$this->inject($this->subject, 'view', $view);

		$this->subject->newAction($person);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenPersonToPersonRepository() {
		$person = new \Personmanager\PersonManager\Domain\Model\Person();

		$personRepository = $this->getMock('Personmanager\\PersonManager\\Domain\\Repository\\PersonRepository', array('add'), array(), '', FALSE);
		$personRepository->expects($this->once())->method('add')->with($person);
		$this->inject($this->subject, 'personRepository', $personRepository);

		$this->subject->createAction($person);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenPersonToView() {
		$person = new \Personmanager\PersonManager\Domain\Model\Person();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('person', $person);

		$this->subject->editAction($person);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenPersonInPersonRepository() {
		$person = new \Personmanager\PersonManager\Domain\Model\Person();

		$personRepository = $this->getMock('Personmanager\\PersonManager\\Domain\\Repository\\PersonRepository', array('update'), array(), '', FALSE);
		$personRepository->expects($this->once())->method('update')->with($person);
		$this->inject($this->subject, 'personRepository', $personRepository);

		$this->subject->updateAction($person);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenPersonFromPersonRepository() {
		$person = new \Personmanager\PersonManager\Domain\Model\Person();

		$personRepository = $this->getMock('Personmanager\\PersonManager\\Domain\\Repository\\PersonRepository', array('remove'), array(), '', FALSE);
		$personRepository->expects($this->once())->method('remove')->with($person);
		$this->inject($this->subject, 'personRepository', $personRepository);

		$this->subject->deleteAction($person);
	}
}

<?php

namespace Personmanager\PersonManager\Domain\Repository;


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
 * The repository for Persons
 */
class PersonRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function findExp($active, $confirmed, $unsubscribed)
    {
        $query = $this->createQuery();
        $query->setOrderings(array('lastname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING, 'firstname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));

        if ($active == "*" && $confirmed == "*" && $unsubscribed == "*") return $query->execute();

        $arr = array();
        if ($active != "*") {
            array_push($arr, $query->equals('active', $active));
        }
        if ($confirmed != "*") {
            array_push($arr, $query->equals('confirmed', $confirmed));
        }
        if ($unsubscribed != "*") {
            array_push($arr, $query->equals('unsubscribed', $unsubscribed));
        }
        $query->matching(
            $query->logicalAnd(
                $arr
            )
        );

        return $query->execute();
    }

    public function search($term, $order)
    {
        $query = $this->createQuery();
        //$query->setOrderings(array('lastname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING, 'firstname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        if ($order == 0) {
            $query->setOrderings(array('uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 1) {
            $query->setOrderings(array('firstname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 2) {
            $query->setOrderings(array('firstname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } elseif ($order == 3) {
            $query->setOrderings(array('lastname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 4) {
            $query->setOrderings(array('lastname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } elseif ($order == 5) {
            $query->setOrderings(array('email' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 6) {
            $query->setOrderings(array('email' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } elseif ($order == 7) {
            $query->setOrderings(array('company' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 8) {
            $query->setOrderings(array('company' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        }

        $arr = array();
        array_push($arr, $query->like('firstname', "%" . $term . "%"));
        array_push($arr, $query->like('lastname', "%" . $term . "%"));
        array_push($arr, $query->like('email', "%" . $term . "%"));
        array_push($arr, $query->like('company', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt0', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt1', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt2', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt3', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt4', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt5', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt6', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt7', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt8', "%" . $term . "%"));
        array_push($arr, $query->like('frtxt9', "%" . $term . "%"));

        $query->matching(
            $query->logicalOr(
                $arr
            )
        );

        return $query->execute();
    }

    public function getAll($order)
    {
        $query = $this->createQuery();

        if ($order == 0) {
            $query->setOrderings(array('uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 1) {
            $query->setOrderings(array('firstname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 2) {
            $query->setOrderings(array('firstname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } elseif ($order == 3) {
            $query->setOrderings(array('lastname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 4) {
            $query->setOrderings(array('lastname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } elseif ($order == 5) {
            $query->setOrderings(array('email' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 6) {
            $query->setOrderings(array('email' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        } elseif ($order == 7) {
            $query->setOrderings(array('company' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        } elseif ($order == 8) {
            $query->setOrderings(array('company' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
        }

        return $query->execute();
    }

}
<?php

namespace Personmanager\PersonManager\Controller;


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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Personmanager\PersonManager\Phpexcel\MyReadFilter;
use TYPO3\CMS\Core\Database\ConnectionPool;


/**
 * BackendController
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * personRepository
     *
     * @var \Personmanager\PersonManager\Domain\Repository\PersonRepository
     * @inject
     */
    protected $personRepository = NULL;

    /**
     * categoryRepository
     *
     * @var \Personmanager\PersonManager\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository = NULL;

    /**
     * logRepository
     *
     * @var \Personmanager\PersonManager\Domain\Repository\LogRepository
     * @inject
     */
    protected $logRepository = NULL;

    /**
     * blacklistRepository
     *
     * @var \Personmanager\PersonManager\Domain\Repository\BlacklistRepository
     * @inject
     */
    protected $blacklistRepository = NULL;

    protected $persistenceManager = NULL;

    protected $extKey = 'person_manager';

    public $signature = "";
    public $sitename = "";

    public $flexcheckmail = "";
    public $flexconfirm = "";
    public $flexerr = "";

    public $flexleave = "";
    public $flexisunsubscribed = "";
    public $flexcheckmailleave = "";
    public $flexunsubscribe = "";

    public function initializeAction()
    {
        $this->persistenceManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager');
    }

    /**
     * action list
     *
     * @param integer $order
     * @param string $getterm
     * @return void
     */
    public function listAction($order = 0, $getterm = "")
    {
        $term = $this->request->getArguments()["search"];
        if ($term == NULL || $term == "") {
            $term = $getterm;
        }
        if ($term == NULL || $term == "") {
            $persons = $this->personRepository->getAll($order);
        } else {
            $persons = $this->personRepository->search($term, $order);
        }
        $this->view->assign('persons', $persons);
        $this->view->assign('vars', $this->settings);
        $this->view->assign('term', $term);
        $this->view->assign('order', $order);
    }

    public function getProps($isimp)
    {
        $vars = $this->settings;

        $pers = new \Personmanager\PersonManager\Domain\Model\Person();
        $reflect = new \ReflectionClass($pers);
        $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        $props = array();

        foreach ($properties as $prop) {
            $desc = "";
            if ($prop->getName() == "salutation") {
                if ($vars["salutation"] == 1) {
                    $desc = LocalizationUtility::translate("tx_personmanager_domain_model_person.salutation", "PersonManager");
                    if ($isimp) {
                        $langhelp = LocalizationUtility::translate('labels.mrmrs', $this->extKey);
                        $langhelp2 = LocalizationUtility::translate('labels.mr', $this->extKey);
                        $langhelp3 = LocalizationUtility::translate('labels.mrs', $this->extKey);
                        $desc .= " ($langhelp | $langhelp2 | $langhelp3) (0|1|2)";
                    }
                }
            } else if ($prop->getName() == "active" || $prop->getName() == "confirmed" || $prop->getName() == "unsubscribed") {
                $desc = LocalizationUtility::translate("tx_personmanager_domain_model_person." . $prop->getName(), "PersonManager");
                if ($isimp) {
                    $langhelp = LocalizationUtility::translate('labels.no', $this->extKey);
                    $langhelp2 = LocalizationUtility::translate('labels.yes', $this->extKey);
                    $desc .= " ($langhelp|$langhelp2) (0|1)";
                }
            } else if ($prop->getName() == "titel" || $prop->getName() == "nachgtitel" || $prop->getName() == "geb" || $prop->getName() == "tel" || $prop->getName() == "company" || $prop->getName() == "category" || substr($prop->getName(), 0, 5) === "frtxt") {
                if ($vars[$prop->getName()] == 1) {
                    $desc = LocalizationUtility::translate("tx_personmanager_domain_model_person." . $prop->getName(), "PersonManager");
                }
            } else if ($prop->getName() == "firstname" || $prop->getName() == "lastname" || $prop->getName() == "email") {
                $desc = LocalizationUtility::translate("tx_personmanager_domain_model_person." . $prop->getName(), "PersonManager");
            }
            if ($desc != "") {
                $data = array("value" => $prop->getName(), "name" => $desc);
                array_push($props, $data);
            }
        }
        return $props;
    }

    /**
     * action newImport
     *
     * @param string $error
     * @param string $spalten
     * @param string $trenn
     * @param string $first
     * @param string $impformat
     * @return void
     */
    public function newImportAction($error = "", $spalten = "", $trenn = "", $first = "", $impformat = "")
    {
        $anz = $this->personRepository->findAll()->count();
        $this->view->assign('countPers', $anz);

        if ($trenn == "") $trenn = ";";
        $this->view->assign('trenn', $trenn);
        if ($spalten == "") $spalten = "salutation;firstname;lastname;email";
        $this->view->assign('spalten', $spalten);
        $this->view->assign('error', $error);
        $this->view->assign('first', $first);
        if ($impformat == "") $impformat = "excel";
        $this->view->assign('impformat', $impformat);

        $props = $this->getProps(1);
        $this->view->assign('props', $props);
    }

    /**
     * action import
     *
     * @param \Personmanager\PersonManager\Domain\Model\Person $person
     * @return void
     */
    public function importAction()
    {
        $failed = 0;
        $vars = $_POST["tx_personmanager_web_personmanagerpersonmanagerback"];
        $spalten = $vars["spalten"];
        $trenn = $vars["trenn"];
        $first = $vars["first"];
        $impformat = $vars["impformat"];
        $check = $vars["check"];
        $filen = $vars["filen"];
        $arr = explode($trenn, $spalten);
        $error = "";
        $obj = new \ReflectionObject(new \Personmanager\PersonManager\Domain\Model\Person());

        if ($first == "1") {
            $startindex = 1;
        } else {
            $startindex = 2;
        }

        foreach ($arr as $val) {
            if (!$obj->hasProperty($val)) {
                $langhelp = LocalizationUtility::translate('error.nocol', $this->extKey);
                $error .= "<p>" . sprintf("$langhelp", $val) . "</p>";
                $failed = 1;
            }
        }
        if ($failed == 0) {
            $personen = array();

            $feler_trenner = $trenn;
            $zeilen_trenner = (string)chr(10);

            $csv_datei = $this->doUploadFile();
            if ($check == "1") {
                $csv_datei = $filen;
            }

            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_personmanager_domain_model_person');
            $personMails = $queryBuilder->select('email')->from('tx_personmanager_domain_model_person')->execute()->fetchAll();
            $personMails = array_map(function($a){return $a['email'];},$personMails);
            if ($impformat == "excel") {
                foreach ($this->doLoadExcel($csv_datei) as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;


                    for ($row = $startindex; $row <= $highestRow; ++$row) {
                        $emailKey = array_search('email', $arr);
                        if ($emailKey !== false) {
                            $cell = $worksheet->getCellByColumnAndRow($emailKey, $row);
                            if(in_array($this->extractEmail($cell->getValue()), $personMails)) {
                                $newPerson = $this->personRepository->findOneByEmail($this->extractEmail($cell->getValue()));
                            } else {
                                $newPerson = false;
                            }
                        }
                        if (!$newPerson) {
                            $newPerson = new \Personmanager\PersonManager\Domain\Model\Person();
                            $newPerson->setActive(1);
                            $newPerson->setConfirmed(1);
                        }
                        foreach ($arr as $key => $value) {
                            $cell = $worksheet->getCellByColumnAndRow($key, $row);
                            if ($value == "category") {
                                $newKat = $this->categoryRepository->findOneByName($cell->getValue());
                                if ($newKat == NULL) {
                                    $newKat = new \Personmanager\PersonManager\Domain\Model\Category();
                                    $newKat->setName($cell->getValue());
                                    $this->categoryRepository->add($newKat);
                                    $this->persistenceManager->persistAll();
                                }
                                $newPerson->setCategory($newKat);
                            } else {
                                if ($value == "salutation") {
                                    if (strtolower(trim($cell->getValue())) == 'herr' || strtolower(trim($cell->getValue())) == 'herrn' || strtolower(trim($cell->getValue())) == 'sir' || strtolower(trim($cell->getValue())) == 'mr') $cell->setValue(1);
                                    if (strtolower(trim($cell->getValue())) == 'frau' || strtolower(trim($cell->getValue())) == 'madame' || strtolower(trim($cell->getValue())) == 'mrs') $cell->setValue(2);
                                    if ($cell->getValue() != 1 && $cell->getValue() != 2) $cell->setValue(0);
                                }
                                if ($value == "active" || $value == "confirmed" || $value == "unsubscribed") {
                                    if (strtolower(trim($cell->getValue())) == 'nein' || strtolower(trim($cell->getValue())) == 'no') $cell->setValue(0);
                                    if (strtolower(trim($cell->getValue())) == 'ja' || strtolower(trim($cell->getValue())) == 'yes') $cell->setValue(1);
                                }
                                if ($value == "email") {
                                    $cell->setValue($this->extractEmail($cell->getValue()));
                                }
                                $newPerson->setProperty($value, $cell->getValue());
                            }
                        }
                        $tstmp = time();
                        $hash = $newPerson->getEmail() . $tstmp;
                        $newPerson->setToken(md5($hash));

                        if ($newPerson->getEmail() != "" && $newPerson->getEmail() != NULL) {
                            if ($check == "1") {
                                $this->personRepository->add($newPerson);
                            } else {
                                array_push($personen, $newPerson);
                            }
                        }
                    }
                }
                $this->persistenceManager->persistAll();
            } else {
                $datei_inhalt = @file_get_contents($csv_datei);
                $zeilen = explode($zeilen_trenner, $datei_inhalt);
                $anzahl_zeilen = count($zeilen);

                if (is_array($zeilen) == true) {
                    foreach ($zeilen as $key => $zeile) {
                        if ($zeile !== null && $zeile !== "" && $key > ($startindex - 2)) {
                            $felder = explode($feler_trenner, $zeile);

                            $emailKey = array_search('email', $arr);
                            if ($emailKey !== false) {
                                $newPerson = $this->personRepository->findOneByEmail($this->extractEmail($felder[$emailKey]));
                            }
                            if (!$newPerson) {
                                $newPerson = new \Personmanager\PersonManager\Domain\Model\Person();
                                $newPerson->setActive(1);
                                $newPerson->setConfirmed(1);
                            }
                            foreach ($arr as $key => $value) {
                                $cell = $felder[$key];
                                if ($value == "category") {
                                    $newKat = $this->categoryRepository->findOneByName($cell);
                                    if ($newKat == NULL) {
                                        $newKat = new \Personmanager\PersonManager\Domain\Model\Category();
                                        $newKat->setName($cell);
                                        $this->categoryRepository->add($newKat);
                                        $this->persistenceManager->persistAll();
                                    }
                                    $newPerson->setCategory($newKat);
                                } else {
                                    if ($value == "salutation" || $value == "salutation") {
                                        if (strtolower(trim($cell)) == 'herr' || strtolower(trim($cell)) == 'herrn' || strtolower(trim($cell)) == 'sir' || strtolower(trim($cell)) == 'mr') $cell = 1;
                                        if (strtolower(trim($cell)) == 'frau' || strtolower(trim($cell)) == 'madame' || strtolower(trim($cell)) == 'mrs') $cell = 2;
                                        if ($cell != 1 && $cell != 2) $cell = 0;
                                    }
                                    if ($value == "active" || $value == "confirmed" || $value == "unsubscribed") {
                                        if (strtolower(trim($cell)) == 'nein' || strtolower(trim($cell)) == 'no') $cell = 0;
                                        if (strtolower(trim($cell)) == 'ja' || strtolower(trim($cell)) == 'yes') $cell = 1;
                                    }
                                    if ($value == "email") {
                                        $cell = $this->extractEmail($cell);
                                    }
                                    $newPerson->setProperty($value, $cell);
                                }
                            }
                            $tstmp = time();
                            $hash = $newPerson->getEmail() . $tstmp;
                            $newPerson->setToken(md5($hash));

                            if ($newPerson->getEmail() != "" && $newPerson->getEmail() != NULL) {
                                if ($check == "1") {
                                    $this->personRepository->add($newPerson);
                                } else {
                                    array_push($personen, $newPerson);
                                }
                            }
                        }
                    }
                }
                $this->persistenceManager->persistAll();
            }
            if ($check == "1") {
                $this->redirect('insertData');
            }
            $this->view->assign('personen', $personen);
            $this->view->assign('arr', $arr);
            $this->view->assign('anz', count($personen));

            $this->view->assign('error', $error);
            $this->view->assign('spalten', $spalten);
            $this->view->assign('trenn', $trenn);
            $this->view->assign('first', $first);
            $this->view->assign('impformat', $impformat);
            $this->view->assign('filename', $csv_datei);
        } else {
            $this->forward('newImport', null, null, array('error' => $error, 'spalten' => $spalten, 'trenn' => $trenn, 'first' => $first, 'impformat' => $impformat));
        }
    }

    /**
     * action insertData
     *
     * @return void
     */
    public function insertDataAction()
    {

    }

    /**
     * action newExport
     *
     * @return void
     */
    public function newExportAction()
    {
        $anz = $this->personRepository->findAll()->count();
        $this->view->assign('countPers', $anz);
    }

    /**
     * action export
     *
     * @return void
     */
    public function exportAction()
    {
        $active = $_POST["active"];
        $confirmed = $_POST["confirmed"];
        $unsubscribed = $_POST["unsubscribed"];
        $expformat = $_POST["expformat"];
        $trenn = $_POST["trenn"];

        $array = $this->personRepository->findExp($active, $confirmed, $unsubscribed);

        if ($expformat == "csv") {
            $this->array_to_csv($array, $trenn);
        } else {
            $this->array_to_excel($array);
        }

        exit();
    }

    function array_to_excel($array)
    {
        ini_set('display_errors', '1');
        date_default_timezone_set('Europe/Vienna');

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getActiveSheet()->setTitle('Export');
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->freezePane('A2');

        $props = $this->getProps(0);
        $row = 1;
        $col = 'A';
        foreach ($props as $prop) {
            $objPHPExcel->getActiveSheet()->setCellValue($col . $row, $prop["name"]);
            $col++;
        }
        $row = 2;
        foreach ($array as $pers) {
            $col = 'A';
            foreach ($props as $prop) {
                if ($prop["value"] == "category") {
                    $objPHPExcel->getActiveSheet()->setCellValue($col . $row, $pers->getProperty($prop["value"]));
                    $help = $pers->getCategory()->getName();
                } elseif ($prop["value"] == "salutation" || $prop["value"] == "salutation") {
                    if ($pers->getSalutation() == "0") $help = LocalizationUtility::translate('labels.mrmrs', $this->extKey);
                    if ($pers->getSalutation() == "1") $help = LocalizationUtility::translate('labels.mr', $this->extKey);
                    if ($pers->getSalutation() == "2") $help = LocalizationUtility::translate('labels.mrs', $this->extKey);
                } elseif ($prop["value"] == "active" || $prop["value"] == "confirmed" || $prop["value"] == "unsubscribed") {
                    if ($pers->getProperty($prop["value"]) == "0") $help = LocalizationUtility::translate('labels.no', $this->extKey);
                    if ($pers->getProperty($prop["value"]) == "1") $help = LocalizationUtility::translate('labels.yes', $this->extKey);
                } else {
                    $help = $pers->getProperty($prop["value"]);
                }
                $objPHPExcel->getActiveSheet()->setCellValue($col . $row, $help);
                $col++;
            }
            $row++;
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        \header('Content-Type: application/vnd.ms-excel');
        \header('Content-Disposition: attachment;filename="export.xls"');
        \header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }

    function array_to_csv($array, $delimiter)
    {
        $filename = "export.csv";
        $props = $this->getProps(0);

        //header('Content-Type: application/csv charset=ISO-8859-1');
        header('Content-Type: application/csv charset=UTF-8');
        header('Content-Disposition: attachement; filename="' . $filename . '";');

        $f = fopen('php://output', 'w');

        foreach ($props as $prop) {
            echo utf8_decode($prop["name"]) . $delimiter;
        }
        echo PHP_EOL;

        foreach ($array as $pers) {
            foreach ($props as $prop) {
                if ($prop["value"] == "category") {
                    echo utf8_decode($pers->getCategory()->getName()) . $delimiter;
                } elseif ($prop["value"] == "salutation" || $prop["value"] == "salutation") {
                    if ($pers->getSalutation() == "0") $langhelp = LocalizationUtility::translate('labels.mrmrs', $this->extKey);
                    if ($pers->getSalutation() == "1") $langhelp = LocalizationUtility::translate('labels.mr', $this->extKey);
                    if ($pers->getSalutation() == "2") $langhelp = LocalizationUtility::translate('labels.mrs', $this->extKey);
                    echo $langhelp . $delimiter;
                } elseif ($prop["value"] == "active" || $prop["value"] == "confirmed" || $prop["value"] == "unsubscribed") {
                    if ($pers->getProperty($prop["value"]) == "0") $langhelp = LocalizationUtility::translate('labels.no', $this->extKey);
                    if ($pers->getProperty($prop["value"]) == "1") $langhelp = LocalizationUtility::translate('labels.yes', $this->extKey);
                    echo $langhelp . $delimiter;
                } else {
                    echo utf8_decode($pers->getProperty($prop["value"])) . $delimiter;
                }
            }
            echo PHP_EOL;
        }
        fclose($f);
    }

    /**
     * action loglist
     *
     * @return void
     */
    public function loglistAction()
    {
        $logs = $this->logRepository->findAll();
        $this->view->assign('logs', $logs);
    }

    /**
     * action blNewImport
     *
     * @param string $error
     * @param string $first
     * @param string $impformat
     * @return void
     */
    public function blNewImportAction($error = "", $first = "", $impformat = "")
    {
        $anz = $this->blacklistRepository->findAll()->count();
        $this->view->assign('countPers', $anz);

        $this->view->assign('error', $error);
        $this->view->assign('first', $first);
        if ($impformat == "") $impformat = "excel";
        $this->view->assign('impformat', $impformat);

        $props = $this->getProps(1);
        $this->view->assign('props', $props);
        $this->view->assign('vars', $this->settings);
    }

    /**
     * action blImport
     *
     * @param \Personmanager\PersonManager\Domain\Model\Person $person
     * @return void
     */
    public function blImportAction()
    {
        $failed = 0;
        $vars = $_POST["tx_personmanager_web_personmanagerpersonmanagerback"];
        $first = $vars["first"];
        $impformat = $vars["impformat"];
        $check = $vars["check"];
        $filen = $vars["filen"];
        $error = "";
        $obj = new \ReflectionObject(new \Personmanager\PersonManager\Domain\Model\Person());

        if ($first == "1") {
            $startindex = 1;
        } else {
            $startindex = 2;
        }

        if ($failed == 0) {
            $blacklists = array();

            $feler_trenner = ";";
            $zeilen_trenner = (string)chr(10);

            $csv_datei = $this->doUploadFile();
            if ($check == "1") {
                $csv_datei = $filen;
            }
            if ($impformat == "excel") {
                foreach ($this->doLoadExcel($csv_datei) as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;


                    for ($row = $startindex; $row <= $highestRow; ++$row) {
                        $newBlacklist = new \Personmanager\PersonManager\Domain\Model\Blacklist();
                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        $newBlacklist->setEmail(trim($cell->getValue()));

                        if ($newBlacklist->getEmail() != "" && $newBlacklist->getEmail() != NULL) {
                            if ($check == "1") {
                                $this->blacklistRepository->add($newBlacklist);
                                $this->persistenceManager->persistAll();
                            } else {
                                array_push($blacklists, $newBlacklist);
                            }
                        }
                    }

                }
            } else {
                $datei_inhalt = @file_get_contents($csv_datei);
                $zeilen = explode($zeilen_trenner, $datei_inhalt);
                $anzahl_zeilen = count($zeilen);

                if (is_array($zeilen) == true) {
                    foreach ($zeilen as $key => $zeile) {
                        if ($zeile !== null && $zeile !== "" && $key > ($startindex - 2)) {
                            $felder = explode($feler_trenner, $zeile);

                            $newBlacklist = new \Personmanager\PersonManager\Domain\Model\Blacklist();
                            $cell = $felder[0];
                            $help = explode(",", $cell);
                            $newBlacklist->setEmail(trim($help[0]));

                            if ($newBlacklist->getEmail() != "" && $newBlacklist->getEmail() != NULL) {
                                if ($check == "1") {
                                    $this->blacklistRepository->add($newBlacklist);
                                    $this->persistenceManager->persistAll();
                                } else {
                                    array_push($blacklists, $newBlacklist);
                                }
                            }
                        }
                    }
                }
            }
            if ($check == "1") {
                $this->redirect('insertData');
            }
            $this->view->assign('blacklists', $blacklists);
            $this->view->assign('anz', count($blacklists));

            $this->view->assign('error', $error);
            $this->view->assign('first', $first);
            $this->view->assign('impformat', $impformat);
            $this->view->assign('filename', $csv_datei);
        } else {
            $this->forward('blNewImport', null, null, array('error' => $error, 'first' => $first, 'impformat' => $impformat));
        }
    }

    protected function doUploadFile(){
        $uploaddir = GeneralUtility::getFileAbsFileName(GeneralUtility::resolveBackPath(PATH_site . "uploads/tx_personmanager"));
        $uploadfile = basename($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['name']['jsonobj']);
        $csv_datei = $uploaddir . "/" . $uploadfile;
        if (move_uploaded_file($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['tmp_name']['jsonobj'], $csv_datei)) {
            if (@file_exists($csv_datei) == false) {
                $langhelp = LocalizationUtility::translate('error.nofile', $this->extKey);
                echo sprintf($langhelp, $csv_datei);
                exit;
            } else {
                $filename = basename($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['name']['jsonobj']);
            }
        }
        return $csv_datei;
    }
    protected function doLoadExcel($csv_datei){
        ini_set('display_errors', '1');

        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array(' memoryCacheSize ' => '4MB');
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $inputFileType = \PHPExcel_IOFactory::identify($csv_datei);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objReader->setReadFilter(new MyReadFilter());
        $objPHPExcel = $objReader->load($csv_datei);

        return $objPHPExcel->getWorksheetIterator();
    }

    /**
     * action clear
     *
     * @return void
     */
    public function clearAction()
    {
        $this->doClear("tx_personmanager_domain_model_person");
    }

    /**
     * action blClear
     *
     * @return void
     */
    public function blClearAction()
    {
        $this->doClear("tx_personmanager_domain_model_blacklist");
    }

    protected function doClear($table)
    {
        $pid = $this->settings["storagePid"];
        $databaseConnection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $databaseConnection->update(
            $table,
            ['deleted' => 1],
            ['pid' => $pid]
        );
        $this->redirect('list');
    }

    public function extractEmail($email)
    {
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9_\-\+\.]+/i';
        preg_match_all($pattern, $email, $matches);
        if (is_array($matches[0])) {
            if (filter_var($matches[0][0], FILTER_VALIDATE_EMAIL)) {
                return $matches[0][0];
            }
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
        return "";
    }

}

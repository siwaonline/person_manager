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

use BadFunctionCallException;
use Exception;
use InvalidArgumentException;
use Personmanager\PersonManager\Domain\Model\Blacklist;
use Personmanager\PersonManager\Domain\Model\Category;
use Personmanager\PersonManager\Domain\Model\Person;
use Personmanager\PersonManager\Domain\Repository\BlacklistRepository;
use Personmanager\PersonManager\Domain\Repository\CategoryRepository;
use Personmanager\PersonManager\Domain\Repository\LogRepository;
use Personmanager\PersonManager\Domain\Repository\PersonRepository;
use Personmanager\PersonManager\Phpexcel\MyReadFilter;
use PHPExcel_Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Exception as ExtbaseException;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use UnexpectedValueException;

/**
 * //TODO Refactor this Class
 * Split Logic into Services and use Utilities
 *
 * BackendController
 */
class BackendController extends ActionController
{

    protected $extKey = 'person_manager';

    public $signature = '';
    public $sitename = '';

    public $flexcheckmail = '';
    public $flexconfirm = '';
    public $flexerr = '';

    public $flexleave = '';
    public $flexisunsubscribed = '';
    public $flexcheckmailleave = '';
    public $flexunsubscribe = '';


    /**
     * @param PersistenceManager $persistenceManager
     * @param PersonRepository $personRepository
     * @param CategoryRepository $categoryRepository
     * @param LogRepository $logRepository
     * @param BlacklistRepository $blacklistRepository
     * @param ModuleTemplateFactory $moduleTemplateFactory ,
     * @param IconFactory $iconFactory
     * @param UriBuilder $backendUriBuilder
     */
    public function __construct(
        protected readonly PersistenceManager    $persistenceManager,
        protected readonly PersonRepository      $personRepository,
        protected readonly CategoryRepository    $categoryRepository,
        protected readonly LogRepository         $logRepository,
        protected readonly BlacklistRepository   $blacklistRepository,
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        protected readonly IconFactory           $iconFactory,
        protected readonly UriBuilder            $backendUriBuilder,
    )
    {
    }

    private function renderModule($variables): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        foreach ($variables as $key => $value) {
            $moduleTemplate->assign($key, $value);
        }

        $buttonBar = $moduleTemplate->getDocHeaderComponent()->getButtonBar();
//        if ($backButton) {
//            $back = $buttonBar->makeLinkButton()
//                ->setHref((string)$this->backendUriBuilder->buildUriFromRoute('web_auss', [
//                    'action' => 'list',
//                ]))
//                ->setTitle('Zurück')
//                ->setIcon($this->iconFactory->getIcon('actions-arrow-left', Icon::SIZE_SMALL));
//            $buttonBar->addButton($back);
//        }
//        if ($submitButton) {
//            $submit = $buttonBar->makeInputButton()
//                ->setForm('form')->setShowLabelText(true)
//                ->setTitle('Speichern')->setName('save')->setValue('1')
//                ->setIcon($this->iconFactory->getIcon('actions-save', Icon::SIZE_SMALL));
//            $buttonBar->addButton($submit);
//        }
        $moduleTemplate->makeDocHeaderModuleMenu(['id' => (int)GeneralUtility::_GP('id')]);
        return $moduleTemplate->renderResponse(ucfirst($this->request->getControllerActionName()));
    }

    /**
     * action list
     *
     * @param int $order
     * @param string $getterm
     */
    public function listAction($order = 0, $getterm = ''): ResponseInterface
    {
        $term = $this->request->getArguments()['search'] ?? null;
        if ($term == null || $term == '') {
            $term = $getterm;
        }
        if ($term == null || $term == '') {
            $persons = $this->personRepository->getAll($order);
        } else {
            $persons = $this->personRepository->search($term, $order);
        }

        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : 1;
        $paginator = new QueryResultPaginator($persons, $currentPage, 50);
        $pagination = new SimplePagination($paginator);

        return $this->renderModule(['persons' => $persons, 'pagination' => $pagination, 'paginator' => $paginator, 'vars' => $this->settings, 'settings' => $this->settings, 'term' => $term, 'order' => $order]);
    }

    /**
     * @param mixed $isimp
     * @return array
     * @throws InvalidArgumentException
     */
    public function getProps($isimp)
    {
        $vars = $this->settings;

        $pers = new Person();
        $reflect = new \ReflectionClass($pers);
        $properties = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        $props = [];

        foreach ($properties as $prop) {
            $desc = '';
            if ($prop->getName() == 'salutation') {
                if ($vars['salutation'] == 1) {
                    $desc = LocalizationUtility::translate(
                        'tx_personmanager_domain_model_person.salutation',
                        'PersonManager'
                    );
                    if ($isimp) {
                        $langhelp = LocalizationUtility::translate('labels.mrmrs', $this->extKey);
                        $langhelp2 = LocalizationUtility::translate('labels.mr', $this->extKey);
                        $langhelp3 = LocalizationUtility::translate('labels.mrs', $this->extKey);
                        $desc .= " ($langhelp | $langhelp2 | $langhelp3) (0|1|2)";
                    }
                }
            } else {
                if ($prop->getName() == 'active' || $prop->getName() == 'confirmed' || $prop->getName() == 'unsubscribed') {
                    $desc = LocalizationUtility::translate(
                        'tx_personmanager_domain_model_person.' . $prop->getName(),
                        'PersonManager'
                    );
                    if ($isimp) {
                        $langhelp = LocalizationUtility::translate('labels.no', $this->extKey);
                        $langhelp2 = LocalizationUtility::translate('labels.yes', $this->extKey);
                        $desc .= " ($langhelp|$langhelp2) (0|1)";
                    }
                } else {
                    if ($prop->getName() == 'titel' || $prop->getName() == 'nachgtitel' || $prop->getName() == 'geb' || $prop->getName() == 'tel' || $prop->getName() == 'company' || $prop->getName() == 'category' || substr(
                            $prop->getName(),
                            0,
                            5
                        ) === 'frtxt') {
                        if ($vars[$prop->getName()] == 1) {
                            $desc = LocalizationUtility::translate(
                                'tx_personmanager_domain_model_person.' . $prop->getName(),
                                'PersonManager'
                            );
                        }
                    } else {
                        if ($prop->getName() == 'firstname' || $prop->getName() == 'lastname' || $prop->getName() == 'email') {
                            $desc = LocalizationUtility::translate(
                                'tx_personmanager_domain_model_person.' . $prop->getName(),
                                'PersonManager'
                            );
                        }
                    }
                }
            }
            if ($desc != '') {
                $data = ['value' => $prop->getName(), 'name' => $desc];
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
     */
    public function newImportAction($error = '', $spalten = '', $trenn = '', $first = '', $impformat = ''): ResponseInterface
    {
        $anz = $this->personRepository->findAll()->count();

        if ($trenn == '') {
            $trenn = ';';
        }
        if ($spalten == '') {
            $spalten = 'salutation;firstname;lastname;email';
        }
        if ($impformat == '') {
            $impformat = 'excel';
        }

        $props = $this->getProps(1);

        return $this->renderModule(['countPers' => $anz, 'trenn' => $trenn, 'spalten' => $spalten, 'error' => $error, 'settings' => $this->settings, 'first' => $first, 'impformat' => $impformat, 'props' => $props]);

    }

    /**
     * action import
     *
     * @param \Personmanager\PersonManager\Domain\Model\Person $person
     */
    /**
     * action import
     *
     * @param Person $person
     */
    public function importAction()
    {
        $failed = 0;
        $vars = $_POST['tx_personmanager_web_personmanagerpersonmanagerback'];
        $spalten = $vars['spalten'];
        $trenn = $vars['trenn'];
        $first = $vars['first'];
        $impformat = $vars['impformat'];
        $check = $vars['check'];
        $filen = $vars['filen'];
        $arr = explode($trenn, $spalten);
        $error = '';
        $obj = new \ReflectionObject(new Person());

        if ($first == '1') {
            $startindex = 1;
        } else {
            $startindex = 2;
        }

        foreach ($arr as $val) {
            if (!$obj->hasProperty($val)) {
                $langhelp = LocalizationUtility::translate('error.nocol', $this->extKey);
                $error .= '<p>' . sprintf("$langhelp", $val) . '</p>';
                $failed = 1;
            }
        }
        if ($failed == 0) {
            $personen = [];

            $feler_trenner = $trenn;
            $zeilen_trenner = (string)chr(10);

            $csv_datei = $this->doUploadFile();
            if ($check == '1') {
                $csv_datei = $filen;
            }

            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_personmanager_domain_model_person');
            $personMails = $queryBuilder->select('email')->from('tx_personmanager_domain_model_person')->executeQuery()->fetchAll();
            $personMails = array_map(function ($a) {
                return $a['email'];
            }, $personMails);
            if ($impformat == 'excel') {
                foreach ($this->doLoadExcel($csv_datei) as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;

                    $updateRecords = [];
                    $newRecords = [];
                    $querySettings = $this->personRepository->createQuery()->getQuerySettings();
                    $pid = $querySettings->getStoragePageIds()[0];
                    for ($row = $startindex; $row <= $highestRow; ++$row) {
                        $emailKey = array_search('email', $arr);
                        if ($emailKey !== false) {
                            $cell = $worksheet->getCellByColumnAndRow($emailKey, $row);
                            if (in_array($this->extractEmail($cell->getValue()), $personMails)) {
                                $newPerson = false;
                            } else {
                                $newPerson = true;
                            }
                        }
                        $person = [];
                        if ($newPerson) {
                            $person['pid'] = $pid;
                            $person['active'] = 1;
                            $person['confirmed'] = 1;
                        }
                        foreach ($arr as $key => $value) {
                            $cell = $worksheet->getCellByColumnAndRow($key, $row);
                            if ($value == 'category') {
                                $newKat = $this->categoryRepository->findOneByName($cell->getValue());
                                if ($newKat == null) {
                                    $newKat = new Category();
                                    $newKat->setName($cell->getValue());
                                    $this->categoryRepository->add($newKat);
                                    $this->persistenceManager->persistAll();
                                }
                                $person['category'] = $newKat->getUid();
                            } else {
                                if ($value == 'salutation') {
                                    if (strtolower(trim($cell->getValue())) == 'herr' || strtolower(trim($cell->getValue())) == 'herrn' || strtolower(trim($cell->getValue())) == 'sir' || strtolower(trim($cell->getValue())) == 'mr') {
                                        $cell->setValue(1);
                                    }
                                    if (strtolower(trim($cell->getValue())) == 'frau' || strtolower(trim($cell->getValue())) == 'madame' || strtolower(trim($cell->getValue())) == 'mrs') {
                                        $cell->setValue(2);
                                    }
                                    if ($cell->getValue() != 1 && $cell->getValue() != 2) {
                                        $cell->setValue(0);
                                    }
                                }
                                if ($value == 'active' || $value == 'confirmed' || $value == 'unsubscribed') {
                                    if (strtolower(trim($cell->getValue())) == 'nein' || strtolower(trim($cell->getValue())) == 'no') {
                                        $cell->setValue(0);
                                    }
                                    if (strtolower(trim($cell->getValue())) == 'ja' || strtolower(trim($cell->getValue())) == 'yes') {
                                        $cell->setValue(1);
                                    }
                                }
                                if ($value == 'email') {
                                    $cell->setValue($this->extractEmail($cell->getValue()));
                                }
                                $person[$value] = $cell->getValue();
                            }
                        }
                        $tstmp = time();
                        $hash = $person['email'] . $tstmp;
                        $person['token'] = md5($hash);

                        if ($person['email'] != '' && $person['email'] != null) {
                            if ($check == '1') {
                                if ($newPerson) {
                                    $newRecords[] = $person;
                                } else {
                                    $updateRecords[] = $person;
                                }
                                //$this->personRepository->add($newPerson);
                            } else {
                                array_push($personen, $person);
                            }
                        }
                    }
                }
                if (count($updateRecords) > 0) {
                    foreach ($updateRecords as $updateRecord) {
                        $updateQuery = $queryBuilder->update('tx_personmanager_domain_model_person')->where($queryBuilder->expr()->eq(
                            'email',
                            $queryBuilder->createNamedParameter($updateRecord['email'])
                        ));
                        foreach ($updateRecord as $key => $value) {
                            $updateQuery->set($key, $value);
                        }
                        $updateQuery->execute();
                    }
                }
                if (count($newRecords) > 0) {
                    $columns = array_keys($newRecords[0]);
                    $queryBuilder->getConnection()->bulkInsert('tx_personmanager_domain_model_person', $newRecords, $columns);
                }
                //$this->persistenceManager->persistAll();
            } else {
                $datei_inhalt = @file_get_contents($csv_datei);
                $zeilen = explode($zeilen_trenner, $datei_inhalt);
                $anzahl_zeilen = count($zeilen);

                if (is_array($zeilen) == true) {
                    foreach ($zeilen as $key => $zeile) {
                        if ($zeile !== null && $zeile !== '' && $key > ($startindex - 2)) {
                            $felder = explode($feler_trenner, $zeile);

                            $emailKey = array_search('email', $arr);
                            if ($emailKey !== false) {
                                $newPerson = $this->personRepository->findOneByEmail($this->extractEmail($felder[$emailKey]));
                            }
                            if (!$newPerson) {
                                $newPerson = new Person();
                                $newPerson->setActive(1);
                                $newPerson->setConfirmed(1);
                            }
                            foreach ($arr as $key => $value) {
                                $cell = $felder[$key];
                                if ($value == 'category') {
                                    $newKat = $this->categoryRepository->findOneByName($cell);
                                    if ($newKat == null) {
                                        $newKat = new Category();
                                        $newKat->setName($cell);
                                        $this->categoryRepository->add($newKat);
                                        $this->persistenceManager->persistAll();
                                    }
                                    $newPerson->setCategory($newKat);
                                } else {
                                    if ($value == 'salutation' || $value == 'salutation') {
                                        if (strtolower(trim($cell)) == 'herr' || strtolower(trim($cell)) == 'herrn' || strtolower(trim($cell)) == 'sir' || strtolower(trim($cell)) == 'mr') {
                                            $cell = 1;
                                        }
                                        if (strtolower(trim($cell)) == 'frau' || strtolower(trim($cell)) == 'madame' || strtolower(trim($cell)) == 'mrs') {
                                            $cell = 2;
                                        }
                                        if ($cell != 1 && $cell != 2) {
                                            $cell = 0;
                                        }
                                    }
                                    if ($value == 'active' || $value == 'confirmed' || $value == 'unsubscribed') {
                                        if (strtolower(trim($cell)) == 'nein' || strtolower(trim($cell)) == 'no') {
                                            $cell = 0;
                                        }
                                        if (strtolower(trim($cell)) == 'ja' || strtolower(trim($cell)) == 'yes') {
                                            $cell = 1;
                                        }
                                    }
                                    if ($value == 'email') {
                                        $cell = $this->extractEmail($cell);
                                    }
                                    $newPerson->setProperty($value, $cell);
                                }
                            }
                            $tstmp = time();
                            $hash = $newPerson->getEmail() . $tstmp;
                            $newPerson->setToken(md5($hash));

                            if ($newPerson->getEmail() != '' && $newPerson->getEmail() != null) {
                                if ($check == '1') {
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
            if ($check == '1') {
                $this->redirect('insertData');
            }

            return $this->renderModule(['personen' => $personen, 'arr' => $arr, 'anz' => count($personen), 'spalten' => $spalten, 'error' => $error, 'trenn' => $trenn, 'settings' => $this->settings, 'first' => $first, 'impformat' => $impformat, 'filename' => $csv_datei]);

        } else {
            return (new ForwardResponse('newImport'))->withArguments([
                'error' => $error,
                'spalten' => $spalten,
                'trenn' => $trenn,
                'first' => $first,
                'impformat' => $impformat,
            ]);
        }
    }

    /**
     * action insertData
     */
    public function insertDataAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * action newExport
     */
    public function newExportAction(): ResponseInterface
    {
        $anz = $this->personRepository->findAll()->count();
        return $this->renderModule(['countPers' => $anz]);

    }

    /**
     * action export
     */
    public function exportAction()
    {
        $active = $_POST['active'];
        $confirmed = $_POST['confirmed'];
        $unsubscribed = $_POST['unsubscribed'];
        $expformat = $_POST['expformat'];
        $trenn = $_POST['trenn'];

        $array = $this->personRepository->findExp($active, $confirmed, $unsubscribed);

        if ($expformat == 'csv') {
            $this->array_to_csv($array, $trenn);
        } else {
            $this->array_to_excel($array);
        }

        exit();
    }

    /**
     * @param mixed $array
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function array_to_excel($array)
    {
        ini_set('display_errors', '1');
        date_default_timezone_set('Europe/Vienna');

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle('Export');
        $spreadsheet->getActiveSheetIndex(0);
        $spreadsheet->getActiveSheet()->freezePane('A2');
        $worksheet = $spreadsheet->getActiveSheet();


        $props = $this->getProps(0);
        $row = 1;
        $col = 1;
        foreach ($props as $prop) {
            $worksheet->setCellValue([$col, $row], $prop['name']);
            $col++;
        }
        $row = 2;
        foreach ($array as $pers) {
            $col = 1;
            foreach ($props as $prop) {
                if ($prop['value'] == 'category') {
                    $worksheet->setCellValue([$col, $row], $pers->getProperty($prop['value']));
                    $help = $pers->getCategory()->getName();
                } elseif ($prop['value'] == 'salutation' || $prop['value'] == 'salutation') {
                    if ($pers->getSalutation() == '0') {
                        $help = LocalizationUtility::translate('labels.mrmrs', $this->extKey);
                    }
                    if ($pers->getSalutation() == '1') {
                        $help = LocalizationUtility::translate('labels.mr', $this->extKey);
                    }
                    if ($pers->getSalutation() == '2') {
                        $help = LocalizationUtility::translate('labels.mrs', $this->extKey);
                    }
                } elseif ($prop['value'] == 'active' || $prop['value'] == 'confirmed' || $prop['value'] == 'unsubscribed') {
                    if ($pers->getProperty($prop['value']) == '0') {
                        $help = LocalizationUtility::translate('labels.no', $this->extKey);
                    }
                    if ($pers->getProperty($prop['value']) == '1') {
                        $help = LocalizationUtility::translate('labels.yes', $this->extKey);
                    }
                } else {
                    $help = $pers->getProperty($prop['value']);
                }
                $worksheet->setCellValue([$col, $row], $help);
                $col++;
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="export.xlsx";');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    /**
     * @param mixed $array
     * @param mixed $delimiter
     * @throws InvalidArgumentException
     */
    private function array_to_csv($array, $delimiter)
    {
        $filename = 'export.csv';
        $props = $this->getProps(0);

        //header('Content-Type: application/csv charset=ISO-8859-1');
        header('Content-Type: application/csv charset=UTF-8');
        header('Content-Disposition: attachement; filename="' . $filename . '";');

        $f = fopen('php://output', 'w');

        foreach ($props as $prop) {
            echo utf8_decode($prop['name']) . $delimiter;
        }
        echo PHP_EOL;

        foreach ($array as $pers) {
            foreach ($props as $prop) {
                if ($prop['value'] == 'category') {
                    echo utf8_decode($pers->getCategory()->getName()) . $delimiter;
                } elseif ($prop['value'] == 'salutation' || $prop['value'] == 'salutation') {
                    if ($pers->getSalutation() == '0') {
                        $langhelp = LocalizationUtility::translate('labels.mrmrs', $this->extKey);
                    }
                    if ($pers->getSalutation() == '1') {
                        $langhelp = LocalizationUtility::translate('labels.mr', $this->extKey);
                    }
                    if ($pers->getSalutation() == '2') {
                        $langhelp = LocalizationUtility::translate('labels.mrs', $this->extKey);
                    }
                    echo $langhelp . $delimiter;
                } elseif ($prop['value'] == 'active' || $prop['value'] == 'confirmed' || $prop['value'] == 'unsubscribed') {
                    if ($pers->getProperty($prop['value']) == '0') {
                        $langhelp = LocalizationUtility::translate('labels.no', $this->extKey);
                    }
                    if ($pers->getProperty($prop['value']) == '1') {
                        $langhelp = LocalizationUtility::translate('labels.yes', $this->extKey);
                    }
                    echo $langhelp . $delimiter;
                } else {
                    echo utf8_decode($pers->getProperty($prop['value'])) . $delimiter;
                }
            }
            echo PHP_EOL;
        }
        fclose($f);
    }

    /**
     * action loglist
     */
    public function loglistAction(): ResponseInterface
    {
        $logs = $this->logRepository->findAll();
        $currentPage = $this->request->hasArgument('currentPage') ? $this->request->getArgument('currentPage') : 1;
        $paginator = new QueryResultPaginator($logs, $currentPage, 50);
        $pagination = new SimplePagination($paginator);
        return $this->renderModule(['logs' => $logs, 'pagination' => $pagination, 'paginator' => $paginator]);
    }

    /**
     * action blNewImport
     *
     * @param string $error
     * @param string $first
     * @param string $impformat
     */
    public function blNewImportAction($error = '', $first = '', $impformat = ''): ResponseInterface
    {
        $anz = $this->blacklistRepository->findAll()->count();

        if ($impformat == '') {
            $impformat = 'excel';
        }

        $props = $this->getProps(1);
        return $this->renderModule(['countPers' => $anz, 'vars' => $this->settings, 'error' => $error, 'settings' => $this->settings, 'first' => $first, 'impformat' => $impformat, 'props' => $props]);
    }

    /**
     * action blImport
     *
     * @param \Personmanager\PersonManager\Domain\Model\Person $person
     */
    /**
     * action blImport
     *
     * @param Person $person
     */
    public function blImportAction()
    {
        $failed = 0;
        $vars = $_POST['tx_personmanager_web_personmanagerpersonmanagerback'];
        $first = $vars['first'];
        $impformat = $vars['impformat'];
        $check = $vars['check'];
        $filen = $vars['filen'];
        $error = '';
        $obj = new \ReflectionObject(new Person());

        if ($first == '1') {
            $startindex = 1;
        } else {
            $startindex = 2;
        }

        if ($failed == 0) {
            $blacklists = [];

            $feler_trenner = ';';
            $zeilen_trenner = (string)chr(10);

            $csv_datei = $this->doUploadFile();
            if ($check == '1') {
                $csv_datei = $filen;
            }
            if ($impformat == 'excel') {
                foreach ($this->doLoadExcel($csv_datei) as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;

                    for ($row = $startindex; $row <= $highestRow; ++$row) {
                        $newBlacklist = new Blacklist();
                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        $newBlacklist->setEmail(trim($cell->getValue()));

                        if ($newBlacklist->getEmail() != '' && $newBlacklist->getEmail() != null) {
                            if ($check == '1') {
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
                        if ($zeile !== null && $zeile !== '' && $key > ($startindex - 2)) {
                            $felder = explode($feler_trenner, $zeile);

                            $newBlacklist = new Blacklist();
                            $cell = $felder[0];
                            $help = explode(',', $cell);
                            $newBlacklist->setEmail(trim($help[0]));

                            if ($newBlacklist->getEmail() != '' && $newBlacklist->getEmail() != null) {
                                if ($check == '1') {
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
            if ($check == '1') {
                $this->redirect('insertData');
            }
            return $this->renderModule(['blacklists' => $blacklists, 'anz' => count($blacklists), 'error' => $error, 'settings' => $this->settings, 'first' => $first, 'impformat' => $impformat, 'filename' => $csv_datei]);

        } else {
            return (new ForwardResponse('blNewImport'))->withArguments(['error' => $error, 'first' => $first, 'impformat' => $impformat]);
        }
    }

    /**
     * @return string
     * @throws BadFunctionCallException
     * @throws InvalidArgumentException
     */
    protected function doUploadFile()
    {
        $uploaddir = GeneralUtility::getFileAbsFileName(GeneralUtility::resolveBackPath(Environment::getPublicPath() . 'uploads/tx_personmanager'));
        $uploadfile = basename($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['name']['jsonobj']);
        $csv_datei = $uploaddir . '/' . $uploadfile;
        if (move_uploaded_file(
            $_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['tmp_name']['jsonobj'],
            $csv_datei
        )) {
            if (@file_exists($csv_datei) == false) {
                $langhelp = LocalizationUtility::translate('error.nofile', $this->extKey);
                echo sprintf($langhelp, $csv_datei);
                exit;
            }
            $filename = basename($_FILES['tx_personmanager_web_personmanagerpersonmanagerback']['name']['jsonobj']);
        }
        return $csv_datei;
    }

    /**
     * @param mixed $csv_datei
     * @return mixed
     * @throws Exception
     */
    protected function doLoadExcel($csv_datei)
    {
        ini_set('display_errors', '1');

        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = [' memoryCacheSize ' => '4MB'];
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
     */
    public function clearAction(): ResponseInterface
    {
        return $this->doClear('tx_personmanager_domain_model_person');
    }

    /**
     * action blClear
     */
    public function blClearAction(): ResponseInterface
    {
        return $this->doClear('tx_personmanager_domain_model_blacklist');
    }

    /**
     * @param mixed $table
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws ExtbaseException
     * @throws InvalidArgumentValueException
     * @throws StopActionException
     */
    protected function doClear($table)
    {
        $pid = $this->settings['storagePid'];
        $databaseConnection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $databaseConnection->update(
            $table,
            ['deleted' => 1],
            ['pid' => $pid]
        );
        return $this->redirect('list');
    }

    /**
     * @param mixed $email
     * @return mixed
     */
    public function extractEmail($email)
    {
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9_\-\+\.]+/i';
        preg_match_all($pattern, $email, $matches);
        if (is_array($matches[0])) {
            if (filter_var($matches[0][0], FILTER_VALIDATE_EMAIL)) {
                return $matches[0][0];
            }
        } else {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }
        return '';
    }
}

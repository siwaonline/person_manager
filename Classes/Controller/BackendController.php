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
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

/**
 * //TODO Refactor this Class
 * Split Logic into Services and use Utilities
 *
 * BackendController
 */
#[AsController]
class BackendController extends ActionController
{

    protected string $extKey = 'person_manager';

    public string $signature = '';
    public string $sitename = '';

    public string $flexcheckmail = '';
    public string $flexconfirm = '';
    public string $flexerr = '';

    public string $flexleave = '';
    public string $flexisunsubscribed = '';
    public string $flexcheckmailleave = '';
    public string $flexunsubscribe = '';


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

    public function initializeAction(): void
    {
        $pid = $this->request->getQueryParams()['id'] ?? null;

        $qS = $this->personRepository->createQuery()->getQuerySettings();
        if ($pid === null) {
            $qS->setRespectStoragePage(false);
        } else {
            $qS->setStoragePageIds([$pid]);
        }

        $this->personRepository->setDefaultQuerySettings($qS);
        $this->logRepository->setDefaultQuerySettings($qS);
        $this->blacklistRepository->setDefaultQuerySettings($qS);
    }

    private function renderModule($variables): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        foreach ($variables as $key => $value) {
            $moduleTemplate->assign($key, $value);
        }

        $id = $this->request->getParsedBody()['id'] ?? $this->request->getQueryParams()['id'] ?? 0;
        $moduleTemplate->makeDocHeaderModuleMenu(['id' => (int)$id]);
        return $moduleTemplate->renderResponse('Backend/' . ucfirst($this->request->getControllerActionName()));
    }

    /**
     * action list
     *
     * @param int $order
     * @param string $getterm
     * @return ResponseInterface
     */
    public function listAction(int $order = 0, string $getterm = ''): ResponseInterface
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
     * @param int $isimp
     * @return array
     * @throws InvalidArgumentException
     */
    public function getProps(int $isimp): array
    {
        $vars = $this->settings;

        $pers = new Person();
        $reflect = new ReflectionClass($pers);
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
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
                    if ($prop->getName() == 'titel' || $prop->getName() == 'nachgtitel' || $prop->getName() == 'geb' || $prop->getName() == 'tel' || $prop->getName() == 'company' || $prop->getName() == 'category' || str_starts_with($prop->getName(), 'frtxt')) {
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
                $props[] = $data;
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
     * @return ResponseInterface
     */
    public function newImportAction(string $error = ''): ResponseInterface
    {
        $anz = $this->personRepository->findAll()->count();
        $trenn = ';';
        $spalten = 'salutation;firstname;lastname;email';

        $props = $this->getProps(1);

        return $this->renderModule(['countPers' => $anz, 'trenn' => $trenn, 'spalten' => $spalten, 'error' => $error, 'settings' => $this->settings, 'first' => '', 'props' => $props]);

    }

    /**
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws Exception
     */
    public function importAction(): ResponseInterface
    {
        $vars = $_POST;
        $spalten = $vars['spalten'];
        $trenn = $vars['trenn'];
        $first = $vars['first'];
        $check = $vars['check'] ?? '0';
        $filen = $vars['filen'] ?? 0;
        $arr = explode($trenn, $spalten);
        $error = '';
        $obj = new ReflectionObject(new Person());

        if ($first == '1') {
            $startindex = 1;
        } else {
            $startindex = 2;
        }

        foreach ($arr as $val) {
            if (!$obj->hasProperty($val)) {
                $langhelp = LocalizationUtility::translate('error.nocol', $this->extKey);
                $error .= '<p>' . sprintf($langhelp, $val) . '</p>';
            }
        }

        $personen = [];

        $feler_trenner = $trenn;
        $zeilen_trenner = chr(10);

        $csv_datei = $this->doUploadFile();
        if ($check == '1') {
            $csv_datei = $filen;
        }

        $datei_inhalt = @file_get_contents($csv_datei);
        $zeilen = explode($zeilen_trenner, $datei_inhalt);

        if (is_array($zeilen)) {
            foreach ($zeilen as $key => $zeile) {
                if ($zeile !== null && $zeile !== '' && $key > ($startindex - 2)) {
                    $felder = explode($feler_trenner, $zeile);

                    $emailKey = array_search('email', $arr);
                    $newPerson = null;
                    if ($emailKey !== false) {
                        $newPerson = $this->personRepository->findOneBy(['email' => $this->extractEmail($felder[$emailKey])]);
                    }
                    if (!($newPerson instanceof Person)) {
                        $newPerson = new Person();
                        $newPerson->setActive(1);
                        $newPerson->setConfirmed(1);
                    }
                    foreach ($arr as $innerKey => $value) {
                        $cell = $felder[$innerKey];
                        if ($value == 'category') {
                            $newKat = $this->categoryRepository->findOneBy(['name' => $cell]);
                            if ($newKat == null) {
                                $newKat = new Category();
                                $newKat->setName($cell);
                                $this->categoryRepository->add($newKat);
                                $this->persistenceManager->persistAll();
                            }
                            $newPerson->setCategory($newKat);
                        } else {
                            if ($value == 'salutation') {
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
                            $personen[] = $newPerson;
                        }
                    }
                }
            }
        }
        $this->persistenceManager->persistAll();

        if ($check == '1') {
            $this->redirect('insertData');
        }

        return $this->renderModule(['personen' => $personen, 'arr' => $arr, 'anz' => count($personen), 'spalten' => $spalten, 'error' => $error, 'trenn' => $trenn, 'settings' => $this->settings, 'first' => $first, 'filename' => $csv_datei]);
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
     * @throws Exception
     */
    public function exportAction(): void
    {
        $active = $_POST['active'];
        $confirmed = $_POST['confirmed'];
        $unsubscribed = $_POST['unsubscribed'];
        $trenn = $_POST['trenn'];

        $array = $this->personRepository->findExp($active, $confirmed, $unsubscribed)->toArray();

        $this->array_to_csv($array, $trenn);

        exit;
    }

    /**
     * @param array $array
     * @param string $delimiter
     * @throws InvalidArgumentException
     */
    private function array_to_csv(array $array, string $delimiter): void
    {
        $filename = 'export.csv';
        $props = $this->getProps(0);

        //header('Content-Type: application/csv charset=ISO-8859-1');
        header('Content-Type: application/csv charset=UTF-8');
        header('Content-Disposition: attachement; filename="' . $filename . '";');

        $f = fopen('php://output', 'w');

        foreach ($props as $prop) {
            echo mb_convert_encoding($prop['name'], 'ISO-8859-1', 'UTF-8') . $delimiter;
        }
        echo PHP_EOL;

        foreach ($array as $pers) {
            foreach ($props as $prop) {
                $langhelp = '';
                if ($prop['value'] == 'category') {
                    echo mb_convert_encoding($pers->getCategory()->getName(), 'ISO-8859-1', 'UTF-8') . $delimiter;
                } elseif ($prop['value'] == 'salutation') {
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
                    echo mb_convert_encoding($pers->getProperty($prop['value']), 'ISO-8859-1', 'UTF-8') . $delimiter;
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
     * @return ResponseInterface
     */
    public function blNewImportAction(string $error = '', string $first = ''): ResponseInterface
    {
        $anz = $this->blacklistRepository->findAll()->count();

        $props = $this->getProps(1);
        return $this->renderModule(['countPers' => $anz, 'vars' => $this->settings, 'error' => $error, 'settings' => $this->settings, 'first' => $first, 'props' => $props]);
    }

    /**
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     * @throws Exception
     */
    public function blImportAction(): ResponseInterface
    {
        $vars = $_POST['tx_personmanager_web_personmanagerpersonmanagerback'];
        $first = $vars['first'];
        $check = $vars['check'];
        $filen = $vars['filen'];
        $error = '';

        if ($first == '1') {
            $startindex = 1;
        } else {
            $startindex = 2;
        }

        $blacklists = [];

        $feler_trenner = ';';
        $zeilen_trenner = chr(10);

        $csv_datei = $this->doUploadFile();
        if ($check == '1') {
            $csv_datei = $filen;
        }

        $datei_inhalt = @file_get_contents($csv_datei);
        $zeilen = explode($zeilen_trenner, $datei_inhalt);

        if (is_array($zeilen)) {
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
                            $blacklists[] = $newBlacklist;
                        }
                    }
                }
            }
        }
        if ($check == '1') {
            $this->redirect('insertData');
        }
        return $this->renderModule(['blacklists' => $blacklists, 'anz' => count($blacklists), 'error' => $error, 'settings' => $this->settings, 'first' => $first, 'filename' => $csv_datei]);
    }

    /**
     * @return string
     * @throws BadFunctionCallException
     * @throws InvalidArgumentException
     */
    protected function doUploadFile(): string
    {
        if (!isset($_FILES['jsonobj']['name'])) {
            return '';
        }

        $uploaddir = GeneralUtility::getFileAbsFileName(GeneralUtility::resolveBackPath(rtrim(Environment::getPublicPath(), '/') . '/uploads/tx_personmanager'));
        $uploadfile = basename($_FILES['jsonobj']['name']);
        $csv_datei = $uploaddir . '/' . $uploadfile;
        if (move_uploaded_file(
            $_FILES['jsonobj']['tmp_name'],
            $csv_datei
        )) {
            if (!@file_exists($csv_datei)) {
                $langhelp = LocalizationUtility::translate('error.nofile', $this->extKey);
                echo sprintf($langhelp, $csv_datei);
                exit;
            }
        }
        return $csv_datei;
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
     * @param string $table
     * @return ResponseInterface
     */
    protected function doClear(string $table): ResponseInterface
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
     * @param string $email
     * @return string
     */
    public function extractEmail(string $email): string
    {
        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9_\-\+\.]+/i';
        preg_match_all($pattern, $email, $matches);
        if (is_array($matches[0]) && count($matches[0])) {
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

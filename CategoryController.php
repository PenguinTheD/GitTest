<?php

namespace ImpNews\ImpNews\Controller;

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

/**
 * Controller of category records
 *
 */
class CategoryController extends \GeorgRinger\News\Controller\CategoryController
{

    const SIGNAL_CATEGORY_LIST_HAS_NEWS_ACTION = 'listHasNewsAction';

    /**
     * Output list of category records that contain pages with news records
     *
     * @param array $overwriteDemand
     * @return void
     */
    public function listHasNewsAction(array $overwriteDemand = null)
    {
        $idList = explode(',', $this->settings['categories']);
        $idListHasNews = '';
        $categoriesStoragePage = [];
        $first = true;
        foreach ($idList as $categoryId) {
            $storagePages = $this->getStoragePageDB($categoryId);
            if (!empty($storagePages)) {
                foreach ($storagePages as $potentialPageWithNewsRecords) {
                    $storagePageNewsRecordsCount = $this->getStoragePageNewsRecordsCountDB($potentialPageWithNewsRecords);
                    if ($storagePageNewsRecordsCount !== 0) {
                        $categoriesStoragePage[$categoryId] = $potentialPageWithNewsRecords;
                        if (! $first) {
                            $idListHasNews .= ','.$categoryId;
                        } else {
                            $idListHasNews .= $categoryId;
                            $first = false;
                        }
                        break;
                    }
                }
            }
        }
        if ($idListHasNews !== '') {
            $this->settings['categories'] = $idListHasNews;
        }
        $configurationManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
        $ts = $configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManager::CONFIGURATION_TYPE_FRAMEWORK, 'news');
        $this->view = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Fluid\\View\\StandaloneView');

        $templatePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($ts['view']['templateRootPaths'][1] . 'Category/List.html');
        $this->view->setTemplatePathAndFilename($templatePath);

        $layoutRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($ts['view']['layoutRootPaths'][1]);
        $this->view->setLayoutRootPaths(array($layoutRootPath));

        $partialRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($ts['view']['partialRootPaths'][1]);
        $this->view->setPartialRootPaths(array($partialRootPath));
        if (! empty($categoriesStoragePage)) {
            $this->view->assign('categoriesStoragePage', $categoriesStoragePage);
        }
        return $this->listAction($overwriteDemand);
    }
    /**
     * @param int $categoryId
     * @return array
     */
    private function getStoragePageDB($categoryId)
    {
        $queryRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid_foreign',
            'sys_category_record_mm',
            'tablenames="pages" AND uid_local=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($categoryId, 'sys_category_record_mm')
        );
        $res = [];
        while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($queryRes)) {
            $res[] = $row['uid_foreign'];
        }
        return $res;
    }
    /**
     * @param int $storagePage
     * @return int
     */
    private function getStoragePageNewsRecordsCountDB($storagePage)
    {
        return $GLOBALS['TYPO3_DB']->exec_SELECTcountRows(
            '*',
            'tx_news_domain_model_news',
            'pid=' . $storagePage . ' AND hidden=0 AND deleted=0'
        );
    }
}

<?php

/*
 * Copyright (C) 2017  Daniel Siepmann <coding@daniel-siepmann.de>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 */

Tx_Extbase_Utility_Extension::configurePlugin(
    $_EXTKEY,
    'name',
    ['Controller' => 'action']
);
t3lib_div::makeInstance(Tx_Extbase_Command_HelpCommandController::class);
$this->objectManager->get(\Tx_Extbase_Command_HelpCommandController::class);
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager)
    ->get(\Tx_Extbase_Command_HelpCommandController::class);
is_a($a, t3lib_Singleton::class);

\TYPO3\CMS\Perm\Controller\PermissionAjaxController::configurePlugin(
    $_EXTKEY,
    'name',
    ['Controller' => 'action']
);
$this->objectManager->get(\TYPO3\CMS\Perm\Controller\PermissionAjaxController::class);

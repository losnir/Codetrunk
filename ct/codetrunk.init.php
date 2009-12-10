<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: codetrunk.init.php
 * $ Changed: 07/12/2009 21:46:41
 * 
 * Codetrunk - Snippet sharing and debugging tool
 * http://www.codetrunk.com/
 * 
 * Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * 
 * Codetrunk (c) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Codetrunk (c) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Codetrunk (c).  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Codetrunk
 * @filesource codetrunk.init.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.02 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

/**
 * Initialize
 */
require "ct/codetrunk.class.php";
$Codetrunk = Codetrunk::getInstance();
$Codetrunk->addController("Default", "default");
$Codetrunk->addController("Trunks", "trunks");
$Codetrunk->addController("About", "about");
$Codetrunk->addController("Captcha", "captcha");

/**
 * Router Configuration
 */
$Codetrunk->Router->setDefaultRoute("Default", "showDefault"); /* Set default */
$Codetrunk->Router->addRule("(?:.*)([dmf][\dABCDEFabcdef]{7,8})(.txt)?(?:\/*)", "Trunks", "showTrunk"); /* Show trunk */
$Codetrunk->Router->addRule("(?:.*)([dmf][\dABCDEFabcdef]{7,8})\/download(?:\/*)", "Trunks", "downloadTrunk"); /* Download trunk */
$Codetrunk->Router->addRule("(?:.*)([dmf][\dABCDEFabcdef]{7,8})\/delete(?:\/*)", "Trunks", "deleteTrunk"); /* Delete trunk */
$Codetrunk->Router->addRule("(?:.*)([dmf][\dABCDEFabcdef]{7,8})\/correction(?:\/*)", "Trunks", "correctTrunk"); /* Correct trunk */
$Codetrunk->Router->addRule("(?:.*)([dmf][\dABCDEFabcdef]{7,8})\/revisions(?:\/*)", "Trunks", "showTrunkRevisions"); /* Revisions trunk */
$Codetrunk->Router->addRule("(?:.*)([dmf][\dABCDEFabcdef]{7,8})\/reportAbuse(?:\/*)", "Trunks", "reportAbuse"); /* Abusive trunk */
$Codetrunk->Router->addRule("(?:\/*)submitTrunk(?:\/*)", "Trunks", "submitTrunk"); /* Submit trunk */
$Codetrunk->Router->addRule("(?:\/*)submitComment(?:\/*)", "Trunks", "submitComment"); /* Submit comment trunk */
$Codetrunk->Router->addRule("(?:\/*)digTrunk(?:\/*)", "Trunks", "digTrunk"); /* Dig trunk */
$Codetrunk->Router->addRule("(?:\/*)getCaptcha(?:\/*)", "Captcha", "showCaptcha"); /* Captcha */
$Codetrunk->Router->addRule("(?:\/*)languages(?:\/*)", "Trunks", "showLanguages"); /* Languages */
$Codetrunk->Router->addRule("(?:\/*)about(?:\/*)", "About", "showAbout"); /* About page */

/**
 * Render Page
 */
if($Codetrunk->Router->followRoute(isset($_GET['q']) ? $_GET['q'] : null))
   $Codetrunk->wRenderer->renderWebsite();
?>
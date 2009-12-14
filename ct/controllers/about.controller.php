<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: about.controller.php
 * $ Changed: 07/12/2009 21:49:13
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
 * @filesource about.controller.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.05 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

if(!defined("_CT")) exit;

/**
 * Controller for 'about' page
 * 
 * aboutController
 * @package Codetrunk
 * @access public
 */
class aboutController extends Controller
{
   /**
    * Initializes About View
    * 
    * aboutController::__construct()
    */
    function __construct() {
      Codetrunk::getInstance()->addView("About", "about");
    }
    
   /**
    * Show's About
    * 
    * aboutController::showAbout()
    * @return bool
    */
   function showAbout() {
      Codetrunk::getInstance()->wRenderer->setTitlePage("About");
      $Params = func_get_args();
      Codetrunk::getInstance()->wRenderer->appendContentHook(array(Codetrunk::getInstance()->getView("About"), 'renderAbout'), $Params);
      return true;
   }
}
?>
<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: manage.controller.php
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
 * @filesource manage.controller.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.08 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

if(!defined("_CT")) exit;

/**
 * Controller for 'manage' page
 * 
 * manageController
 * @package Codetrunk
 * @access public
 */
class manageController extends Controller
{
   /**
    * Initializes Manage View
    * 
    * manageController::__construct()
    */
    function __construct() {
      Codetrunk::getInstance()->addView("Manage", "manage");
      Codetrunk::getInstance()->wRenderer->setTitlePage("Manage");
    }
    
    /**
     * Validates login
     * 
     * manageController::getLogin()
     * @return bool
     */
    function getLogin() {
      session_start();
      if(!isset($_SESSION[ROOT.'ctManage']) || base64_decode($_SESSION[ROOT.'ctManage']) != Codetrunk::getInstance()->Config['Manage']['uniqid'])
         return false;
      return true;
    }
    
    /**
     * Check login
     * 
     * manageController::checkLogin()
     * @return bool
     */
    function checkLogin() {
      if(!$this->getLogin()) {
         Codetrunk::getInstance()->wRenderer->appendContentHook(array(Codetrunk::getInstance()->getView("Manage"), 'renderLogin'), array());
         return false;
      }
      return true;
    }
    
   /**
    * Show's Manage
    * 
    * manageController::initManage()
    * @return bool
    */
   function initManage() {
      if($this->checkLogin()) {
         $Params = func_get_args();      
         Codetrunk::getInstance()->wRenderer->appendContentHook(array(Codetrunk::getInstance()->getView("Manage"), 'renderManage'), $Params);
      }
      return true;
   }
   
   /**
    * Handle's Login
    * 
    * manageController::performLogin()
    * @return bool
    */
   function performLogin() {
      if($this->getLogin()) header("Location: .");
      else {
         Codetrunk::getInstance()->wRenderer->setTitlePage("Login");
         $mgPassword = (isset($_POST['mgPassword']) ? md5($_POST['mgPassword']) : false);
         if(!$mgPassword) header("Location: .");
         else if($mgPassword == md5(Codetrunk::getInstance()->Config['Manage']['password']))
            $_SESSION[ROOT.'ctManage'] = base64_encode(Codetrunk::getInstance()->Config['Manage']['uniqid']);
         header("Location: .");
      }
      return true;
   }
   
   /**
    * Delete Spam
    * 
    * manageController::deleteSpam()
    */
   function deleteSpam() {
      if($this->checkLogin()) {
         Codetrunk::getInstance()->wRenderer->appendContentHook(array(Codetrunk::getInstance()->getView("Manage"), 'renderManage'), array());
         if(false !== ($deleteSpam = Codetrunk::getInstance()->getController("Trunks")->deleteSpam()))
            Codetrunk::getInstance()->wRenderer->prettyConfirm("Confirmed. Found  and deleted ".$deleteSpam." trunks.", "margin-top: 12px;");
         else Codetrunk::getInstance()->wRenderer->prettyError("Error while deleting spam", "margin-top: 12px;");
      }
      return true;
   }
   
   /** Logout
    * 
    * manageController::Logout()
    */
   function Logout() {
      if(!$this->getLogin()) header("Location: .");
      else {
         unset($_SESSION[ROOT.'ctManage']);
         header("Location: .");
      }
   }
}
?>
<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: router.class.php
 * $ Changed: 07/12/2009 21:46:00
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
 * @filesource router.class.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.01 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

/**
 * Routing system. This class routes the request string from the URL to the specified controller->action(params)
 * 
 * Router
 * @package Codetrunk
 * @access public
 */
class Router
{
   /**
    * Router::__construct()
    */
   function __construct() {
      $this->Routes = array();
      $this->defaultRoute = array();
   }

   /**
    * Add's a new rule using regex. Any matched elements are passed to the controller as arguments.
    * 
    * Router::addRule()
    * @param string $Url Regex to be matched with the query string. Note that "/^$/" are added automatically
    * @param string $Controller Controller name
    * @param string $Action Controller's action
    * @param array $Params Parameters
    * @return bool
    */
   function addRule($Url, $Controller, $Action, $Params = array()) {
      if(!Codetrunk::getInstance()->getController($Controller)) return false;
      $this->Routes[] = array($Url, $Controller, $Action, $Params);
      return true;
   }
   
   /**
    * Default Controller/Action/Params if no matches were found for query string
    * 
    * Router::setDefaultRoute()
    * @param string $Controller Controller name
    * @param string $Action Controller's action
    * @param array $Params Parameters
    * @return bool
    */
   function setDefaultRoute($Controller, $Action, $Params = array()) {
      if(!Codetrunk::getInstance()->addController($Controller)) return false;
      $this->defaultRoute = array(null, $Controller, $Action, $Params);
      return true;
   }
   
   /**
    * Tries to match the given query string to one of the latest added rules.
    * If a rule matches the query string then the function stops the matching and calls the Controller->Action(Params)
    * 
    * Router::followRoute()
    * @param string $Url Query String
    * @param array $Cap Controller / Action / Params to be overriden
    * @param array $Params Additional Params to be added to the Controller->Action
    * @return mixed Controller->Action(Params) returned value
    */
   function followRoute($Url, $Cap = array(), $Params = array()) {
      foreach(array_reverse($this->Routes) AS $Route)
         if(!preg_match("/^{$Route[0]}$/", $Url, $Matches)) continue; else { $Cap = $Route; break; };
      if(count($Cap) < 3) $Cap = $this->defaultRoute;
      array_shift($Matches);
      return call_user_func_array(array(Codetrunk::getInstance()->getController($Cap[1]), $Cap[2]), array_merge($Params, $Cap[3], $Matches));
   }
}
?>
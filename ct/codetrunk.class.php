<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: codetrunk.class.php
 * $ Changed: 07/12/2009 21:44:47
 * 
 * Codetrunk - Snippet sharing and debugging tool
 * http://www.codetrunk.com/
 * 
 * Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * 
 * Codetrunk (c) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Codetrunk (c) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Codetrunk (c).  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Codetrunk
 * @filesource codetrunk.class.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.05 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO General Public License v3
 */

/**
 * Main Class
 * 
 * Codetrunk
 * @package Codetrunk
 * @final
 * @access public
 */
final class Codetrunk
{
   protected static $codetrunkInstance;
   /**
    * Codetrunk::__clone()
    * Access: Protected (Singleton)
    */
   protected function __clone() {}
   /**
    * Codetrunk::getInstance()
    * @public
    * @static
    * @return Codetrunk
    */
   public static function getInstance()  {
      if(self::$codetrunkInstance === null) self::$codetrunkInstance = new self();
      return self::$codetrunkInstance;
   }
   
   var $Errors;
   var $Controllers;
   var $Views;
   var $Router;
   var $wRenderer;
   var $File;
   var $Syntax;
   var $webUrl;
   var $Domain;
   var $Config;
   
   /**
    * Performs basic initialization
    * 
    * Codetrunk::__construct()
    * Access: Protected (Singleton)
    */
   protected function __construct() {
      define("_CT", true);
      define("DS", DIRECTORY_SEPARATOR);

      /* Load default Configuration */
      $this->Config = $this->loadConfig("default", true);

      /* Development Mode */
      if($this->Config['Codetrunk']['dev']) {
         error_reporting(E_ALL);
         set_error_handler(array($this, "errorHandler"), E_ALL);
      } else {
         if(function_exists("ini_get")) $errorType = ini_get("error_reporting"); else $errorType = E_ALL & ~E_NOTICE;
         set_error_handler(array($this, "errorHandler"), $errorType);
      }

      /* Logging settings */
      if(function_exists("ini_set")) ini_set("display_errors", $this->Config['Logging']['displayErrors']);

      /* Include Required classes */
      include_once "router.class.php";
      include_once "file.class.php";
      include_once "websiterenderer.class.php";
      include_once "syntaxhighlighter.class.php";
      include_once "controllers/controller.class.php";
      include_once "views/view.class.php";
      
      /* Initialize */
      $this->Errors        = array();
      $this->Controllers   = array();
      $this->Views         = array();      
      $this->Router        = new Router();
      $this->wRenderer     = new websiteRenderer($this->Config['Codetrunk']['style'], $this->Config['Codetrunk']['title']);
      $this->File          = new File(_CP.DS.$this->Config['Storage']['storageDir']);
      $this->Syntax        = new SyntaxHighlighter($this->Config['Codetrunk']['syntax']);
      $this->webUrl        = "http://".$_SERVER['HTTP_HOST'].ROOT;
      $this->Domain        = substr_replace($_SERVER['HTTP_HOST'], null, strrpos($_SERVER['HTTP_HOST'], '.'.$this->Config['Codetrunk']['host']), strlen('.'.$this->Config['Codetrunk']['host']));
      if($this->Domain == "www")
         $this->Domain = "";
      
      /* Load subdomain configuration */
      if(strlen($this->Domain)) {
         define("SUBDOMAIN", $this->Domain);
         $this->Config = $this->configMerge($this->Config, $this->loadConfig("subdomain", true));
         $slicedArray = explode('.', $this->Domain);
         for($i = 1; $i <= count($slicedArray)+1; $i++)
            $this->Config = $this->configMerge($this->Config, $this->loadConfig(implode('.', array_slice($slicedArray, 0, $i))));
         $this->wRenderer->setTitleRaw(str_replace("%title%", $this->wRenderer->getTitle(), $this->Config['Codetrunk']['title']));
      }
   }
   
   /**
    * Adds a controller from "/controllers/XYZ.controller.php"
    * 
    * Codetrunk::addController()
    * @param string $controllerAlias Controller alias name
    * @param string $controllerName The controller natural name to add. This is XYZ.
    * @return bool
    * @public
    */
    function addController($controllerAlias, $controllerName = "") {
      if(isset($this->Controllers[$controllerAlias])) return true;
      if(strlen($controllerName)) {
         $controllerPath = _CP.strtolower("/ct/controllers/{$controllerName}.controller.php");
         if(file_exists($controllerPath)) {
            $this->Controllers[$controllerAlias] = array(array($controllerPath, $controllerName));
            return true;                  
         } else trigger_error("Cannot find controller '{$controllerAlias}' !!!",  E_USER_WARNING);
      } else trigger_error("Controller '{$controllerAlias}' is not added !!!",  E_USER_WARNING);
      return false;   
    }            
         
   /**
    * Gets a controller with an alias
    * 
    * Codetrunk::getController()
    * @param string $controllerAlias Controller alias name
    * @return Controller|bool
    * @public
    */
   public function getController($controllerAlias) {
      if(!isset($this->Controllers[$controllerAlias])) return false;
      if(isset($this->Controllers[$controllerAlias][1])) return $this->Controllers[$controllerAlias][1];
      if(include_once($this->Controllers[$controllerAlias][0][0])) {
         $className = $this->Controllers[$controllerAlias][0][1]."Controller";
         if(class_exists($className)) {
            $this->Controllers[$controllerAlias][1] = new $className();
            return $this->Controllers[$controllerAlias][1];
         } else trigger_error("Controller ".$this->Controllers[$controllerAlias][0][1]." is invalid !!!",  E_USER_WARNING);
      } else trigger_error("Cannot find controller ".$this->Controllers[$controllerAlias][0][1]." !!!",  E_USER_WARNING);
   }
   
   /**
    * Adds a view from "/views/XYZ.view.php"
    * 
    * Codetrunk::addView()
    * @param string $viewAlias View alias name
    * @param string $viewName The view natural name to add. This is XYZ.
    * @return bool
    * @public
    */
    function addView($viewAlias, $viewName = "") {
      if(isset($this->Views[$viewAlias])) return true;
      if(strlen($viewName)) {
         $viewPath = _CP.strtolower("/ct/views/{$viewName}.view.php");
         if(file_exists($viewPath)) {
            $this->Views[$viewAlias] = array(array($viewPath, $viewName));
            return true;                  
         } else trigger_error("Cannot find view '{$viewAlias}' !!!",  E_USER_WARNING);
      } else trigger_error("View '{$viewAlias}' is not added !!!",  E_USER_WARNING);
      return false;   
    }            
         
   /**
    * Gets a view with an alias
    * 
    * Codetrunk::getView()
    * @param string $viewAlias View alias name
    * @return View|bool
    * @public
    */
   public function getView($viewAlias) {
      if(!isset($this->Views[$viewAlias])) return false;
      if(isset($this->Views[$viewAlias][1])) return $this->Views[$viewAlias][1];
      if(include_once($this->Views[$viewAlias][0][0])) {
         $className = $this->Views[$viewAlias][0][1]."View";
         if(class_exists($className)) {
            $this->Views[$viewAlias][1] = new $className();
            return $this->Views[$viewAlias][1];
         } else trigger_error("View ".$this->Views[$viewAlias][0][1]." is invalid !!!",  E_USER_WARNING);
      } else trigger_error("Cannot find view ".$this->Views[$viewAlias][0][1]." !!!",  E_USER_WARNING);
   }
   
   /**
    * Loads an ini configuration file from "/config/XYZ.conf.ini"
    * 
    * Codetrunk::loadConfig()
    * @param string $configName The file name to load. This is XYZ.
    * @param bool $Essential If set to true, an error will be triggered if the file not found or it has parse errors
    * @return array
    * @public
    */
   public function loadConfig($configName, $Essential = false) {
      $configPath = _CP."/ct/config/{$configName}.conf.ini";
      if(file_exists($configPath))
         if($iniConfig = @parse_ini_file($configPath, true)) return $iniConfig;
      if(!isset($iniConfig) && $Essential)
         trigger_error("Unable to load configuration! The configuration file located at <b>'".$configPath."'</b> does not exists or is invalid.",  E_USER_ERROR);
      return array();
   }
   
   /**
    * Takes an array returned by parse_ini_file with sections, and merges it
    * 
    * @param $Source Source array to be merged and returned
    * @param $Merge An array with extended information to merge
    * @return array
    * @public
    * Codetrunk::configMerge()
    */
   public function configMerge($Source, $Merge) {
      foreach($Merge AS $cSection => $cKey)
         $Source[$cSection] = array_merge($Source[$cSection], $Merge[$cSection]);
      return $Source;
   }
   
   /**
    * Performs a regular nl2br (repalces new line caharcters \r\n with <br />) but skips <pre></pre> tags
    * 
    * Codetrunk::nl2br_pre()
    * @param string $tString Target string to nl2br
    * @return string
    * @public
    */
   public function nl2br_pre($tString) {
      return preg_replace_callback('!<pre(.*?)>(.*?)</pre>!is', "clean", nl2br($tString));
      function clean() {
         return "<pre ".stripslashes(trim($m[1])).">".stripslashes(str_replace("<br />", null, $m[2]))."</pre>";
      }
   }
   
   /**
    * A Custom error handler callback
    * 
    * Codetrunk::errorHandler()
    * @param mixed $errno
    * @param mixed $errstr
    * @param mixed $errfile
    * @param mixed $errline
    * @return bool
    * @public
    */
   public function errorHandler($errno, $errstr, $errfile, $errline) {
      if($this->Config['Logging']['enableLogging'])
         error_log(sprintf("[%s] %s (%d) --- %s line %d", date("d/m/Y H:i:s"), $errstr, $errno, $errfile, $errline).PHP_EOL, 3, _CP.DS.$this->Config['Logging']['loggingPath']);
      if($this->Config['Logging']['displayErrors']) return false;
      if($this->Config['Logging']['errorPage']) { $this->wRenderer->renderWebsite($this->Config['Logging']['errorStyle']); exit; }
      return false;
   }
}
?>
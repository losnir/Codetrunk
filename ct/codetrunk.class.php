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
 * @version 2009 1.0 Initial Release
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
         error_reporting(E_ALL | E_STRICT);
         set_error_handler(array($this, "errorHandler"), E_ALL | E_STRICT);
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
      
      /* Initialize */
      $this->Errors        = array();
      $this->Controllers   = array();
      $this->Router        = new Router();
      $this->wRenderer     = new websiteRenderer($this->Config['Codetrunk']['style']);
      $this->File          = new File(_CP.DS.$this->Config['Storage']['storageDir']);
      $this->Syntax        = new SyntaxHighlighter($this->Config['Codetrunk']['syntax']);
      $this->webUrl        = "http://".$_SERVER['HTTP_HOST'].ROOT;
      $this->Domain        = "";
   }
   
   /**
    * Loads a controller from "/controllers/XYZ.controller.php"
    * 
    * Codetrunk::loadController()
    * @param string $controllerName The controller name to load. This is XYZ.
    * @return bool|Controller Returns a controller instance, or true if it is already exists
    * @public
    */
   public function loadController($controllerName) {
      if(isset($this->Controllers[$controllerName])) return true;
      if(include_once(strtolower("controllers/{$controllerName}.controller.php"))) {
         $className = "{$controllerName}Controller";
         if(class_exists($className)) {
            $this->Controllers[$controllerName] = new $className();
            return $this->Controllers[$controllerName];
         } else trigger_error("Controller '{$controllerName}' is invalid !!!",  E_USER_WARNING);
      } else trigger_error("Cannot find controller '{$controllerName}' !!!",  E_USER_WARNING);
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
      $configPath = "config/{$configName}.conf.ini";
      $iniConfig = parse_ini_file($configPath, true);
      if($iniConfig === false && $Essential) trigger_error("Unable to load configuration! The configuration file located at <b>'".$configPath."'</b> does not exists or is invalid.",  E_USER_ERROR);
      return $iniConfig;
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
      return preg_replace_callback('!<pre(.*?)>(.*?)</pre>!is', function($m) {
         return "<pre ".stripslashes(trim($m[1])).">".stripslashes(str_replace("<br />", null, $m[2]))."</pre>";
      }, nl2br($tString));
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
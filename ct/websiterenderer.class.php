<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: websiterenderer.class.php
 * $ Changed: 07/12/2009 21:45:14
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
 * @filesource websiterenderer.class.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.07 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */
 
if(!defined("_CT")) exit;

/**
 * Takes part as the  "View" manager in "MVC",
 * This class will render the website eventually.
 * 
 * websiteRenderer
 * @package Codetrunk
 * @access public
 */
class websiteRenderer
{
   var $contentHooks;
   var $Scripts;
   var $styleName;
   var $Title;
   
   /**
    * Styles rely in "/ct/styles/XYZ.style.php" and are basic html files with parts of php, nothing special.
    * 
    * websiteRenderer::__construct()
    * @param string $styleName Style name to load & render
    * @param string $Title Default title
    */
   function __construct($styleName, $Title) {
      $this->contentHooks = array();
      $this->Scripts      = array();
      $this->styleName    = $styleName;
      $this->Title        = $Title;
   }
   
   /**
    * Appends a callback to be called when the rendering proccess reaches a content area,
    * this call is defined in the style file. 
    * 
    * websiteRenderer::appendContentHook()
    * @param callback $Callback Callback to be called when content is being drawn
    * @param array $Params Array to pass to the callback as parameters
    */
   function appendContentHook($Callback, $Params = array()) {
      $this->contentHooks[] = array($Callback, $Params);
   }
   
   /**
    * Proccesses all appended content hooks in their respective order
    * 
    * websiteRenderer::proccessContentHooks()
    */
    function processContentHooks() {
      foreach($this->contentHooks AS $rendererCallback) {
         $hookResult = call_user_func_array($rendererCallback[0], $rendererCallback[1]);
         if($hookResult === false) break;
      }
    }
    
   /**
    * Appends a script tag to be printed when the rendering proccess reaches the header script area,
    * this call is defined in the style file. 
    * 
    * websiteRenderer::appendScript()
    * @param string $Script
    */
   function appendScript($Script) {
      $this->Scripts[] = $Script;
   }
   
   /**
    * Proccesses all appended script in their respective order
    * 
    * websiteRenderer::proccessScripts()
    */
    function processScripts() {
      foreach($this->Scripts AS $Script) echo $Script;
    }

   /**
    * Prints an html-formatted error div
    * 
    * websiteRenderer::prettyError()
    * @param string $String The error string to print
    * @param string $Style Additional style (css) to add to the div
    */
   function prettyError($String, $Style = "") {
      $this->appendContentHook("error", array($String, $Style));
      function error($String, $Style) {
         echo "\r\n".'<div class="nRound3 round3" style="background: #f5938e; border: 1px solid #9a1717; padding: 4px; color: #ffffff;'.(strlen($Style) ? ' '.$Style : '').'">'.
           '<img src="'.ROOT.'/images/icons/cancel.png" alt="" width="16" height="16" class="icon pngfix" />'.$String.'</div>'; return true;
      }
   }
   
   /**
    * Prints an html-formatted confirm div
    * 
    * websiteRenderer::prettyConfirm()
    * @param string $String The error string to print
    * @param string $Style Additional style (css) to add to the div
    */
   function prettyConfirm($String, $Style = "") {
      $this->appendContentHook("confirm", array($String, $Style));
      function confirm($String, $Style) {
      echo "\r\n".'<div class="nRound3 round3" style="background: #c4ffbe; border: 1px solid #358725; padding: 4px; color: #358725;'.(strlen($Style) ? ' '.$Style : '').'">'.
           '<img src="'.ROOT.'/images/icons/accept.png" alt="" width="16" height="16" class="icon pngfix" />'.$String.'</div>';
      }
   }
   
   /**
    * Render's Website
    * 
    * websiteRenderer::renderWebsite() 
    * @param string $styleName Optional parameter that overrides the default style
    */
   function renderWebsite($styleName = "") {
      $stylePath = "styles/".($styleName != null ? $styleName : $this->styleName).".style.php";
      if(!include $stylePath) trigger_error("Couldn't load style at '$stylePath'!", E_USER_ERROR);
   }
   
   /**
    * Set's the title of the website. Overrides anything else.
    * 
    * websiteRenderer::setTitleRaw()
    * @param string $newTitle The new title
    */
   function setTitleRaw($newTitle) {
      $this->Title = $newTitle;
   }
   
   /**
    * Set's the title of the website for inner pages
    * 
    * websiteRenderer::setTitlePage()
    * @param string $pageName Page Name
    */
   function setTitlePage($pageName) {
      $this->Title = str_replace("%title%", $this->Title, str_replace("%page%", $pageName, Codetrunk::getInstance()->Config['Codetrunk']['tPage']));
   }
   
   /**
    * Set's the title of the website for viewing a trunk
    * 
    * websiteRenderer::setTitleTrunk()
    * @param string $langName Usually this will be the syntax language
    */
   function setTitleTrunk($langName) {
      $this->Title = str_replace("%title%", $this->Title, str_replace("%ct_syntax%", $langName, Codetrunk::getInstance()->Config['Codetrunk']['tTrunk']));
   }
   
   /**
    * Retunrs the current title
    * 
    * websiteRenderer::getTitle()
    * @return string
    */
   function getTitle() {
      return $this->Title;
   }
}
?>
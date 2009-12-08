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
 * @version 2009 1.0 Initial Release
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */
 
if(!defined("_CT")) exit;

/**
 * Takes part as the "View" in "MVC",
 * This class will render the website eventually.
 * 
 * websiteRenderer
 * @package Codetrunk
 * @access public
 */
class websiteRenderer
{
   /**
    * The constructor takes one parameter which is the style to render.
    * Styles rely in "/ct/styles/XYZ.style.php" and are basic html files with parts of php, nothing special.
    * 
    * websiteRenderer::websiteRenderer()
    * @param string $styleName Style name to load
    */
   function websiteRenderer($styleName) {
      $this->contentHooks = array();
      $this->styleName = $styleName;
   }
   
   /**
    * Appends a callback to be called when the rendering proccess reaches a content area,
    * this is defined in the style file. 
    * 
    * websiteRenderer::appendContentHook()
    * @param callback $Callback Callback to be called when content is being drawn
    * @param array $Params Array to pass to the callback as parameters
    */
   function appendContentHook($Callback, $Params = array()) {
      $this->contentHooks[] = array(&$Callback, &$Params);
   }

   /**
    * Prints an html-formatted error div
    * 
    * websiteRenderer::prettyError()
    * @param string $String The error string to print
    * @param string $Style Additional style (css) to add to the div
    */
   function prettyError($String, $Style = "") {
      echo "\r\n".'<div class="nRound3 round3" style="background: #f5938e; border: 1px solid #9a1717; padding: 4px; color: #ffffff;'.(strlen($Style) ? ' '.$Style : '').'">'.
           '<img src="'.ROOT.'/images/icons/cancel.png" alt="" width="16" height="16" class="icon pngfix" />'.$String.'</div>';
   }
   
   /**
    * Prints an html-formatted confirm div
    * 
    * websiteRenderer::prettyConfirm()
    * @param string $String The error string to print
    * @param string $Style Additional style (css) to add to the div
    */
   function prettyConfirm($String, $Style = "") {
      echo "\r\n".'<div class="nRound3 round3" style="background: #c4ffbe; border: 1px solid #358725; padding: 4px; color: #358725;'.(strlen($Style) ? ' '.$Style : '').'">'.
           '<img src="'.ROOT.'/images/icons/accept.png" alt="" width="16" height="16" class="icon pngfix" />'.$String.'</div>';
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
    * Set's the title of the website. Has no affect after the website has been rendered, and / or if the style does not fetch the title from the config, because
    * this function only changes the title in the configuration level.
    * 
    * websiteRenderer::setTitle()
    * @param string $newTitle The new title to be set in the config
    */
   function setTitle($newTitle) {
      Codetrunk::getInstance()->Config['Codetrunk']['title'] = $newTitle;
   }
   
   /**
    * Retunrs the title from the configuration
    * 
    * websiteRenderer::getTitle()
    * @return string
    */
   function getTitle() {
      return Codetrunk::getInstance()->Config['Codetrunk']['title'];
   }
}
?>
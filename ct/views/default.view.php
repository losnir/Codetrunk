<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: default.view.php
 * $ Changed: 07/12/2009 21:48:10
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
 * @filesource default.view.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.01 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO General Public License v3
 */

if(!defined("_CT")) exit;

/**
 * View for 'default' page
 * 
 * defaultView
 * @package Codetrunk
 * @access public
 */
class defaultView extends View
{
   /**
    * Render's Default
    * 
    * defaultController::renderDefault()
    * @param string $ctName Name
    * @param string $ctCode Code
    * @param array $pData Page Data as array(title, parent key, syntax)
    */
   function renderDefault($ctName = null, $ctCode = null, $pData = array("Submit a new Trunk", 0, false)) {
      $rememberCookie = Codetrunk::getInstance()->getController("Trunks")->getRememberCookie();
      if($rememberCookie !== false) {
         $ctName     = ($ctName !== null ? $ctName : $rememberCookie['ctName']);
         $ctExpiry   = $rememberCookie['ctExpiry'];
      }
      ?>
               <div class="title nRound3 round3"><img src="<?php echo ROOT?>/images/icons/box.png" width="16" height="16" alt="" style="margin-right: 4px;" class="icon pngfix" /> <?php echo $pData[0]?></div>
               <form action="<?php echo ROOT?>/submitTrunk" method="post">
                  <input type="hidden" name="Conroller" value="submitTrunk" />
                  <input type="hidden" name="ctPKey" value="<?php echo $pData[1]?>" />
                  <label for="ctSyntaxLanguage" style="font: bold 15px Arial; float: left; margin-top: 2px; *margin-top: 4px;"><img src="<?php echo ROOT?>/images/icons/script_code.png" alt="" width="16" height="16" class="icon pngfix" />Syntax highlighting language:</label>
                     <span class="nRound3 round3" style="border: 1px solid #90a9c8; padding: 3px; background: #fff; float: left;">
                        <select id="ctSyntaxLanguage" name="ctSyntaxLanguage" style="padding: 0; margin: 0; border: 0;">
                           <optgroup label="Popular">
                           <?php
                              $defLanguage = ($pData[2] !== false ? $pData[2] : Codetrunk::getInstance()->Syntax->defLanguage);
                              foreach(Codetrunk::getInstance()->Syntax->popularLanguages AS $i => $langKey) {
                                 if($i > 0 ) echo "                              ";
                                 $langSelected = null;
                                 if($langSelected === null && $defLanguage == $langKey) $langSelected = ' selected="selected"';
                                 echo "<option value=\"{$langKey}\"{$langSelected}>".Codetrunk::getInstance()->Syntax->getLanguage($langKey)."</option>".PHP_EOL;
                              }
                           ?>
                           </optgroup>
                           <optgroup label="All">
                           <?php
                              $i = 0;
                              foreach(Codetrunk::getInstance()->Syntax->allowedLanguages AS $langKey => $langArray) {
                                 if($i > 0 ) echo "                              ";
                                 $langSelected = null;
                                 if($langSelected === null && $defLanguage == $langKey) $langSelected = ' selected="selected"';
                                 echo "<option value=\"{$langKey}\"{$langSelected}>{$langArray[0]}</option>".PHP_EOL; $i++;
                              }
                           ?>
                           </optgroup>
                        </select>
                     </span>
                     <div class="clearfix">&nbsp;</div>
                     
                  <div class="nRound3 round3" style="margin-top: 12px; padding: 1px; border: 1px solid #90a9c8; background: #ffffff;">
                     <textarea name="ctTrunk" rows="10" cols="1" style="border: 0; width: 100%; background: #ffffff;"><?php echo htmlspecialchars($ctCode)?></textarea>
                  </div>
               
                  <div style="margin-top: 12px;">
                     <label for="ctName" style="font-size: 13px; margin: 4px 8px 0 0; width: 160px;" class="left">Your name is</label>
                        <input type="text" name="ctName" id="ctName" value="<?php echo $ctName?>" class="nRound3 nBtn" style="background: #ffffff; margin: 0; padding: 4px; width: 162px; float: left;" />
                     <input type="submit" value="Submit Trunk" class="nRound3 nBtn" style="width: 112px; padding: 3px; margin-left: 6px; height: 25px; float: left;" />
                     <div class="clearfix">&nbsp;</div>
                  </div>
                  
                  <div style="margin-top: 12px;">
                     <label style="font-size: 13px; margin: 4px 8px 0 0; width: 160px;" class="left">Trunk should be kept for</label>
                        <input type="radio" id="ctExpiryD" name="ctExpiry" value="d" /><label for="ctExpiryD" style="font-size: 12px; margin-left: 6px; width: 162px; color: #42638c;">Day</label>
                        <input type="radio" id="ctExpiryM" checked="checked" name="ctExpiry" value="m" /><label for="ctExpiryM" style="font-size: 12px; margin-left: 6px; width: 162px; color: #42638c;">Month</label>
                        <input type="radio" id="ctExpiryF" name="ctExpiry" value="f" /><label for="ctExpiryF" style="font-size: 12px; margin-left: 6px; width: 162px; color: #42638c;">Forever</label>
                  </div>
                  
                  <div style="margin-top: 18px;">
                     <label style="font-size: 13px; margin: 4px 8px 0 0; width: 160px; visibility: hidden;" class="left">&nbsp;</label>
                        <input type="checkbox" name="ctRemember" id="rememberMe" value="true" checked="checked" />
                           <label for="rememberMe" style="font-size: 13px; color: #42638c;"> Remember me so that I'll be able to delete my trunk later</label>
                  </div>
               </form>
      <?php
   }
}
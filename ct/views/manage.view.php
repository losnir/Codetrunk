<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: manage.view.php
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
 * @filesource manage.view.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.08 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO General Public License v3
 */

if(!defined("_CT")) exit;

/**
 * View for 'manage' page
 * 
 * manageView
 * @package Codetrunk
 * @access public
 */
class manageView extends View
{
   /**
    * Render's Manage
    * 
    * manageController::renderManage()
    * @return bool
    */
   function renderManage() {
      ?>
               <div class="title nRound3 round3"><img src="<?php echo ROOT?>/images/icons/information.png" width="16" height="16" alt="" style="margin-right: 4px;" class="icon pngfix" /> Manage</div>
               <div style="font: 15px Arial;"><div class="clearfix">&nbsp;</div>
                  This is a simple Manage Page to overcome the spam issue. <a href="<?php echo ROOT ?>/manage/logout">Logout</a>
                  <p style="border-bottom: 1px solid #90a9c8; color: #90a9c8; margin: 14px 0 10px 0; padding-bottom: 2px;">Delete Spam</p>
                     Codetrunk has an integrated spam filter that ingores any new trunk that meets the following:
                        <ul style="margin: 16px 0 16px 32px;">
                           <li>Contains <?php echo Codetrunk::getInstance()->Config['SpamFilter']['minLinks'] ?> links or more</li>
                           <li>The text / links character ratio is higher than <?php echo Codetrunk::getInstance()->Config['SpamFilter']['linkToTextRatio'] ?></li>
                        </ul>
                     In the case that Codetrunk integrated spam filter has been improved you can delete any previous posts that might be now considered as spam.
                     <div style="margin-top: 12px;">
                        <input type="button" value="Delete Spam" class="nRound3 nBtn" style="width: 112px; padding: 3px; margin-left: 6px; height: 25px; float: left;" onclick="javascript:document.location = '<?php echo ROOT?>/manage/deleteSpam'" />
                        <div class="clearfix">&nbsp;</div>
                     </div>
               </div>
      <?php
      return true;
   }
   
   /**
    * Render's Login
    * 
    * manageController::renderLogin()
    * @return bool
    */
   function renderLogin() {
      ?>
         <div class="title nRound3 round3"><img src="<?php echo ROOT?>/images/icons/lock.png" width="16" height="16" alt="" style="margin-right: 4px;" class="icon pngfix" /> Managing Area</div>
         <form action="<?php echo ROOT?>/manage/login" method="post">
            <div style="margin-top: 12px;">
               <label for="mgPassword" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Password</label>
                  <input type="text" name="mgPassword" id="mgPassword" value="" class="nRound3 nBtn" style="background: #ffffff; margin: 0; padding: 4px; width: 162px; float: left;" />
               <input type="submit" value="Login" class="nRound3 nBtn" style="width: 112px; padding: 3px; margin-left: 6px; height: 25px; float: left;" />
               <div class="clearfix">&nbsp;</div>
            </div>
         </form>
      <?php
      return true;
   }            
}
?>
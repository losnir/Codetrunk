<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: trunks.view.php
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
 * @filesource trunks.view.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.01 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO General Public License v3
 */

if(!defined("_CT")) exit;

/**
 * View for 'trunks' page
 * 
 * trunksView
 * @package Codetrunk
 * @access public
 */
class trunksView extends View
{
   /**
   * Render's Trunk Revisions
   * 
   * trunksController::renderTrunkRevisions()
   * @param array $trunkData
   */
   function renderTrunkRevisions($trunkData){
     ?>
     <div class="title nRound3 round3" style="overflow: hidden;"><img src="<?php echo ROOT?>/images/icons/script_lightning.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
       Showing <?php echo count($trunkData['followUps'])?> revisions for a trunk posted by <u><a href="<?php echo $trunkData['Url']?>"><?php echo $trunkData['Name']?></a></u>
     </div>
     <?php
     $i = 0;
     foreach($trunkData['followUps'] AS $followUp) {
       $fData = Codetrunk::getInstance()->File->getTrunk($followUp['Key'], Codetrunk::getInstance()->Domain);
       if(!$fData) continue;
       ?>
       <div class="nBtn nRound3 round3" style="border: 1px solid #90a9c8; margin-top: 6px;">
       <img src="<?php echo ROOT?>/images/icons/script_go.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
         Trunk posted on <?php echo $fData['timeString']?> by <u><?php echo $fData['Name']?></u> ... <a href="<?php echo $fData['Url']?>">Click here to view</a>
       </div>
       <?php
            $i++;
         }
     ?>
     <div class="nBtn nRound3 round3" style="border: 1px solid #90a9c8; background: #bed4ef; margin-top: 16px;">
     <img src="<?php echo ROOT?>/images/icons/script_delete.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
       Excluding <u><?php echo count($trunkData['followUps'])-$i?></u> expired or deleted trunks.
     </div>
     <?php
   }

   /**
   * Render's Report Abuse Form
   * 
   * trunksController::renderReportAbuse()
   * @param array $ctTrunk Trunk Data
   */
   function renderReportAbuse($ctTrunk) {
     ?>
     <div class="title nRound3 round3"><img src="<?php echo ROOT?>/images/icons/script_delete.png" width="16" height="16" alt="" style="margin-right: 4px;" class="icon pngfix" /> Report abuse for a trunk by <a href="<?php echo $ctTrunk['Url']?>"><u><?php echo $ctTrunk['Name']?></u></a></div>
     <form action="<?php echo $ctTrunk['Url']?>/reportAbuse" method="post">
       <input type="hidden" name="Conroller" value="reportAbuse" />
       <div style="margin-top: 12px;">
         <label for="ctAbuseReason" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Reason:</label>
            <span class="nRound3 round3" style="border: 1px solid #90a9c8; padding: 3px; background: #fff; float: left;">
              <select id="ctAbuseReason" name="ctAbuseReason" style="padding: 0; margin: 0; border: 0;">
                <option value="spam">Spam / Advertising / Junk</option>
                <option value="copy">Copyrighted Code</option>                     
                <option value="personal">Sensitive Personal Information</option>
                <option value="inappropriate">Inappropriate (Nudity, Violence, Racism)</option>                     
                <option value="other">Other</option>                                                                             
              </select>
            </span>
            <div class="clearfix">&nbsp;</div>
       </div>
       <div style="margin-top: 12px;">
         <label for="ctComment" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Additional Comments:</label>
            <div class="nRound3 round3" style="padding: 1px; border: 1px solid #90a9c8; background: #ffffff; width: 50%; float: left;">
              <textarea name="ctComment" id="ctComment" rows="5" cols="1" style="border: 0; width: 100%; background: #ffffff; color: #1e4b92; font: bold 12px Arial;">sadsa</textarea>
            </div>                              
            <div class="clearfix">&nbsp;</div>              
       </div>
       <div style="margin-top: 12px;">
         <label for="ctEmail" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Email:</label>
            <input type="text" name="ctEmail" value="" id="ctEmail" class="nRound3 nBtn" style="background: #ffffff; margin: 0; padding: 4px; width: 162px; float: left;" />
            <div class="clearfix">&nbsp;</div>
       </div>
       <div style="margin-top: 12px;">
         <label for="ctCaptcha" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Verification:</label>
            <input type="text" name="ctCaptcha" maxlength="7" id="ctCaptcha" class="nRound3 nBtn" style="background: #ffffff; margin-right: 6px; padding: 4px; width: 52px; float: left;" />
            <img src="<?php echo ROOT?>/getCaptcha" alt="Refresh the page to see the captcha" title="Please enter this combination of words and numbers into the text box to your left" />
            <div class="clearfix">&nbsp;</div>
       </div>
       <div style="margin-top: 12px;">
         <label for="ctCaptcha" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px; visibility: hidden;" class="left">&nbsp;</label>                           
            <input type="submit" value="Report Abuse" class="nRound3 nBtn" style="width: 112px; padding: 3px; height: 25px; float: left;" />
            <div class="clearfix">&nbsp;</div>                         
       </div>                                 
     </form>            
     <?php
   }
   
   /**
   * Render's Trunk
   * 
   * trunksController::renderTrunk()
   * @param array $trunkData Trunk Data
   * @param array $pData
   * @param string $ctName
   * @param string $ctComment
   */
   function renderTrunk($trunkData, $pData, $ctName, $ctComment = false) {
     ?>
     <div class="title nRound3 round3" style="overflow: hidden;"><img src="<?php echo ROOT?>/images/icons/script_code.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
       <span style="float: left;">Trunk posted on <?php echo $trunkData['timeString']?> by <u><?php echo $trunkData['Name']?></u>
         <?php if($pData !== false) { ?><span style="font-size: 12px; color: #5f6f84;">(A revised version of a trunk posted by <a class="link2" href="<?php echo $pData['Url']?>"><?php echo $pData['Name']?></a>)</span><?php } ?></span>
       <span style="float: right;">Language: <i><?php echo Codetrunk::getInstance()->Syntax->getLanguage($trunkData['Syntax'])?></i></span>
       <div class="clearfix">&nbsp;</div>
       <div style="font-size: 12px; margin-top: 6px; color: #145582;">&rsaquo;
         <a class="link1" target="_blank" href="<?php echo $trunkData['Url']?>.txt">view plain</a> |
         <a class="link1" href="<?php echo $trunkData['Url']?>/reportAbuse">report abuse</a> |
         <a class="link1" href="#Comments">comments</a>
         <?php if(isset($_COOKIE['ctToken']) && $trunkData['Token'] == $_COOKIE['ctToken']) { ?> | <a class="link1" onclick="return confirm('Are you sure you want to delete this trunk?\r\nThis action cannot be undone!')" href="<?php echo $trunkData['Url']?>/delete">delete</a><?php } ?> |
         <a class="link1" href="<?php echo $trunkData['Url']?>/download">download</a> |
         <a class="link1" href="<?php echo $trunkData['Url']?>/correction">submit a correction</a>
         <?php if($trunkData['followUps']) { ?> | <a class="link1" href="<?php echo $trunkData['Url']?>/revisions"><?php echo count($trunkData['followUps'])?> revisions</a><?php } ?>
       </div>
     </div>
     <div class="title nRound3 round3 left" style="margin-bottom: 4px; padding: 4px;"><a class="link" href="<?php echo $trunkData['Url']?>/correction"><img src="<?php echo ROOT?>/images/icons/script_lightning.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
       Click here to submit a correction
     </a></div>
     <div class="right" style="padding: 6px;">
       <span class="left">
         <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pub=LosNir"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
       </span>
       <span class="left" style="margin-left: 14px;">
         <script type="text/javascript">tweetmeme_style = 'compact';</script>
         <script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
       </span>
       <span class="left">
         <a name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php">Share</a>
         <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
       </span>              
     </div>
     <div class="clearfix">&nbsp;</div>
            
     <div class="nRound3 round3" style="border: 1px solid #90a9c8;  background: #ffffff;">
       <pre class="brush: <?php echo $trunkData['Syntax']?>"><?php echo htmlspecialchars($trunkData['Code']);?></pre>
     </div>
            
     <a name="Comments"></a>
     <div class="title nRound3 round3" style="overflow: hidden; margin-top: 16px;"><img src="<?php echo ROOT?>/images/icons/comments.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
       What pepole have to say about this trunk...
     </div>
     <?php
     $trunkComments = Codetrunk::getInstance()->File->getComments($trunkData['Key'], Codetrunk::getInstance()->Domain);
     if($trunkComments !== false) foreach($trunkComments AS $Comment) {
     ?>
     <div class="nRound3 round3" style="overflow: hidden; background: #c6dfed; color: #4b7b96; padding: 6px; border: 1px solid #6e93ca; font: bold 12px Arial; margin-top: 6px;"><img src="<?php echo ROOT?>/images/icons/comment.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
       <span style="float: left;"><u><?php echo $Comment['Name']?></u> Says:</span>
       <span style="float: right;"><?php echo $Comment['timeString']?></span>
       <div class="clearfix">&nbsp;</div>
       <div style="border-top: 1px solid #4b7b96; margin-top: 6px; padding: 8px 0 2px 0; color: #4d7890;">
         <?php
         echo Codetrunk::getInstance()->nl2br_pre(preg_replace(array_keys(Codetrunk::getInstance()->Syntax->bbCodeRegex), array_values(Codetrunk::getInstance()->Syntax->bbCodeRegex), htmlspecialchars($Comment['Content'])));
         ?>
       </div>
     </div>
     <?php
     }
     ?>
     <a name="newComment"></a>
     <div style="margin-top: 12px;">
       <div style="border-bottom: 1px solid #90a9c8; color: #90a9c8; margin: 12px 0 6px 0; padding-bottom: 2px;">Write a comment...
         <span style="color: #365376; font-size: 11px;">Wrap code with <i>[php][/php]</i>, where <i>php</i> is your preferred language, to get it highlighted. <a target="_blank" class="link2" href="<?php echo ROOT?>/languages">(supported languages)</a></span>
       </div>
       <form action="<?php echo ROOT?>/submitComment" method="post">
         <input type="hidden" name="Conroller" value="submitComment" />
         <input type="hidden" name="ctKey" value="<?php echo $trunkData['Key']?>" />
         <div class="nRound3 round3" style="padding: 1px; border: 1px solid #90a9c8; background: #ffffff; width: 50%;">
            <textarea name="ctComment" rows="5" cols="1" style="border: 0; width: 100%; background: #ffffff; color: #1e4b92; font: bold 12px Arial;"><?php echo $ctComment?></textarea>
         </div>
         <div style="margin-top: 12px;">
            <label for="ctName" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Your name:</label>
              <input type="text" name="ctName" value="<?php echo $ctName?>" id="ctName" class="nRound3 nBtn" style="background: #ffffff; margin: 0; padding: 4px; width: 162px; float: left;" />
              <input type="submit" value="Submit Comment" class="nRound3 nBtn" style="width: 112px; padding: 3px; margin-left: 6px; height: 25px; float: left;" />
              <div class="clearfix">&nbsp;</div>
         </div>
         <div style="margin-top: 12px;">
            <label for="ctCaptcha" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Verification:</label>
              <input type="text" name="ctCaptcha" maxlength="7" id="ctCaptcha" class="nRound3 nBtn" style="background: #ffffff; margin-right: 6px; padding: 4px; width: 52px; float: left;" />
              <img src="<?php echo ROOT?>/getCaptcha" alt="Refresh the page to see the captcha" title="Please enter this combination of words and numbers into the text box to your left" />
              <div class="clearfix">&nbsp;</div>
         </div>
       </form>
     </div>
     <?php
   }
   
   /**
   * Initializes SyntaxHighlighter by calling the required <script> tags
   * 
   * trunksController::initializeSyntaxHighlighter()
   */
   function initializeSyntaxHighlighter() {
     Codetrunk::getInstance()->wRenderer->appendScript('<script type="text/javascript" src="'.ROOT.'/syntaxhighlighter/src/shCore.js"></script>');
       foreach(Codetrunk::getInstance()->Syntax->allowedLanguages AS $langValue)
     Codetrunk::getInstance()->wRenderer->appendScript('<script type="text/javascript" src="'.ROOT.'/syntaxhighlighter/scripts/'.$langValue[1].'"></script>'.PHP_EOL);
     Codetrunk::getInstance()->wRenderer->appendScript('<script type="text/javascript">
       SyntaxHighlighter.config.clipboardSwf = \''.ROOT.'/syntaxhighlighter/scripts/clipboard.swf\';
       SyntaxHighlighter.all();
       </script>');
     Codetrunk::getInstance()->wRenderer->appendScript('<script type="text/javascript">
       var addthis_config = {
         data_ga_tracker: "pageTracker"
       };
       </script>
       <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pub=LosNir"></script>');
   }
   
   /**
   * Render's all of the supported SynatxHighlighter languages
   * 
   * trunksController::renderSupportedLanguages()
   */
   function renderSupportedLanguages() {
     ?>
     <div class="title nRound3 round3" style="overflow: hidden;"><img src="<?php echo ROOT?>/images/icons/script_code.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
       Supported languages
     </div>
     <?php
     foreach(Codetrunk::getInstance()->Syntax->allowedLanguages AS $langKey => $langValue) {
     ?>
     <div class="nBtn nRound3 round3" style="border: 1px solid #90a9c8; margin-top: 6px;">
       <img src="<?php echo ROOT?>/images/icons/script.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
       <span style="width: 180px; float: left;"><?php echo $langValue[0]?></span><span style="color: #828282; margin-left: 50px;"><i>[<?php echo $langKey?>]......[/<?php echo $langKey?>]</i></span>
     </div>
     <?php
     }
   }
}
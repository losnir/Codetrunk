<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: trunks.controller.php
 * $ Changed: 07/12/2009 21:50:19
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
 * @filesource trunks.controller.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.0 Initial Release
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO General Public License v3
 */

if(!defined("_CT")) exit;

/**
 * Controller to handle all trunk related actions
 * 
 * trunksController
 * @package Codetrunk
 * @access public
 */
class trunksController extends Controller
{
   /**
    * Hnadles a submit trunk action through POST
    * 
    * trunksController::submitTrunk()
    * @return bool
    */
   function submitTrunk() {
      if(isset($_POST['Conroller']) && $_POST['Conroller'] == "submitTrunk") {
         $ctName   = (isset($_POST['ctName']) ? $this->getName($_POST['ctName']) : false);
         $ctTrunk  = (isset($_POST['ctTrunk']) ? $_POST['ctTrunk'] : false);
         $ctSyntax = (isset($_POST['ctSyntaxLanguage']) && $this->getSyntax($_POST['ctSyntaxLanguage']) ? $_POST['ctSyntaxLanguage'] : Codetrunk::getInstance()->Syntax->defLanguage);
         $ctExpiry = (isset($_POST['ctExpiry']) ? $this->getExpiry($_POST['ctExpiry']) : false);
         $ctPKey   = (isset($_POST['ctPKey']) ? $this->getTrunkKey($_POST['ctPKey']) : 0);
         if(!$ctTrunk) {
            Codetrunk::getInstance()->Router->followRoute(null, false, array($ctName));
            Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyError("Please enter some code.", "margin-top: 12px;"); return true;});
         } else {
            $ctToken = 0;
            if(isset($_POST['ctRemember'])) {
               $ctToken     = isset($_COOKIE['ctToken']) ? $this->getToken($_COOKIE['ctToken']) : md5(uniqid(rand(), true));
               $cookieValue = implode('#', array($ctName, $ctExpiry));
               if(!isset($_COOKIE['ctRemember']) || ($cookieValue != $_COOKIE['ctRemember'])) setcookie("ctRemember", $cookieValue, time()+3600*24*365);  
               if(!isset($_COOKIE['ctToken'])) setcookie("ctToken", $ctToken, time()+3600*24*365);  
            } else {
               if(isset($_COOKIE['ctRemember'])) setcookie("ctRemember", null, 0);
            }
            $ctName   = (strlen($ctName) ? $ctName : "Anonymous");
            $trunkKey = Codetrunk::getInstance()->File->addTrunk($ctName, Codetrunk::getInstance()->Domain, $ctSyntax, $ctTrunk, $ctPKey, $ctExpiry, $ctToken);
            header("Location: ".$this->getTrunkUrl($trunkKey));
         }
      } else Codetrunk::getInstance()->Router->followRoute(null, false);
      return true;
   }
   
   /**
    * Handles a delete trunk through GET
    * 
    * trunksController::deleteTrunk()
    * @param string $trunkKey Trunk Key
    * @return bool
    */
   function deleteTrunk($trunkKey) {
      $trunkKey = $this->getTrunkKey($trunkKey);
      $getTrunk = Codetrunk::getInstance()->File->getTrunk($trunkKey, Codetrunk::getInstance()->Domain);
      if(isset($_COOKIE['ctToken']) && $getTrunk['Token'] == $_COOKIE['ctToken'] && $getTrunk !== false) {
         Codetrunk::getInstance()->File->deleteTrunk($trunkKey, Codetrunk::getInstance()->Domain);
         Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyConfirm("Your trunk has been successfully deleted!", "margin-bottom: 12px;"); return true;});
         Codetrunk::getInstance()->Router->followRoute(null, false);
      } else {
         if($getTrunk !== false) Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyError("You don't have the permission to delete this trunk!", "margin-bottom: 12px;"); return true;});
         Codetrunk::getInstance()->Router->followRoute(null, array(null, "trunks", "showTrunk", array($trunkKey)));
      }
      return true;
   }
   
   /**
    * Handles a trunk show revisions through GET
    * 
    * trunksController::showTrunkRevisions()
    * @param string $trunkKey Trunk Key
    * @return bool
    */
   function showTrunkRevisions($trunkKey) {
      $trunkData = Codetrunk::getInstance()->File->getTrunk($this->getTrunkKey($trunkKey), Codetrunk::getInstance()->Domain);
      if($trunkData === false) {
         Codetrunk::getInstance()->wRenderer->appendContentHook(function(){
            Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;"); return true;
         });
         Codetrunk::getInstance()->Router->followRoute(null, false);
      } else {
         Codetrunk::getInstance()->wRenderer->appendContentHook(function($trunkData){
            ?>
               <div class="title nRound3 round3" style="overflow: hidden;"><img src="<?=ROOT?>/images/icons/script_lightning.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
                  Showing <?=count($trunkData['followUps'])?> revisions for a trunk posted by <u><a href="<?=$trunkData['url']?>"><?=$trunkData['Name']?></a></u>
               </div>
            <?php
               $i = 0;
               foreach($trunkData['followUps'] AS $followUp) {
                  $fData = Codetrunk::getInstance()->File->getTrunk($followUp['Key'], Codetrunk::getInstance()->Domain);
                  if(!$fData) continue;
                  ?>
               <div class="nBtn nRound3 round3" style="border: 1px solid #90a9c8; margin-top: 6px;">
                  <img src="<?=ROOT?>/images/icons/script_go.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
                  Trunk posted on <?=$fData['timeString']?> by <u><?=$fData['Name']?></u> ... <a href="<?=$fData['url']?>">Click here to view</a>
               </div>
                  <?php
                  $i++;
               }
            ?>
               <div class="nBtn nRound3 round3" style="border: 1px solid #90a9c8; background: #bed4ef; margin-top: 16px;">
                  <img src="<?=ROOT?>/images/icons/script_delete.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
                  Excluding <u><?=count($trunkData['followUps'])-$i?></u> expired or deleted trunks.
               </div>
            <?php
         }, array($trunkData));
      }
      return true;
   }
   
   /**
    * Handles a submit comment through POST
    * 
    * trunksController::submitComment()
    * @return bool
    */
   function submitComment() {
      if(isset($_POST['Conroller']) && $_POST['Conroller'] == "submitComment") {
         session_start();
         $ctKey      = (isset($_POST['ctKey']) ? $this->getTrunkKey($_POST['ctKey']) : false);
         $ctName     = (isset($_POST['ctName']) ? $this->getName($_POST['ctName']) : false);
         $ctComment  = (isset($_POST['ctComment']) ? $_POST['ctComment'] : false);
         $ctCaptcha  = (isset($_POST['ctCaptcha']) ? $_POST['ctCaptcha'] : false);
         $sesCaptcha = (isset($_SESSION['CodetrunkCaptcha']) ? $_SESSION['CodetrunkCaptcha'] : false);
         unset($_SESSION['CodetrunkCaptcha']);
         if(!$ctKey) Codetrunk::getInstance()->Router->followRoute(null, false);
         elseif(strtolower($sesCaptcha) != strtolower($ctCaptcha)) {
            Codetrunk::getInstance()->Router->followRoute(null, array(null, "trunks", "showTrunk", array($ctKey, false, $ctName, $ctComment)));
            Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyError("Wrong verification code entered. Please try again!", "margin-top: 12px;"); return true;});
         } elseif(!$ctComment) {
            Codetrunk::getInstance()->Router->followRoute(null, array(null, "trunks", "showTrunk", array($ctKey, false, $ctName, $ctComment)));
            Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyError("Please enter a comment.", "margin-top: 12px;"); return true;});
         } elseif(strlen($ctComment) > 2048) {
            Codetrunk::getInstance()->Router->followRoute(null, array(null, "trunks", "showTrunk", array($ctKey, false, $ctName, $ctComment)));
            Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyError("Your comment exceeded 2048 characters, please write less!", "margin-top: 12px;"); return true;});
         } else {
            $ctName   = (strlen($ctName) ? $ctName : "Anonymous");
            Codetrunk::getInstance()->File->addComment($ctKey, $ctComment, $ctName);
            header("Location: ".$this->getTrunkUrl($ctKey));
         }
      } else Codetrunk::getInstance()->Router->followRoute(null, false);
      Codetrunk::getInstance()->wRenderer->appendContentHook(function(){echo '<script type="text/javascript">window.location.hash="newComment";</script>'; return true;});
      return true;
   }
   
   /**
    * Handles an abusive trunk report through GET
    * 
    * trunksController::abusiveTrunk()
    * @param string $trunkKey Trunk Key
    * @return bool
    */
   function abusiveTrunk($trunkKey) {
      $trunkKey = $this->getTrunkKey($trunkKey);
      $getTrunk = Codetrunk::getInstance()->File->getTrunk($trunkKey, Codetrunk::getInstance()->Domain);
      if($getTrunk !== false) {
         $logSprintf = sprintf("Abusive trunk: %s - %s", $trunkKey, $getTrunk['Domain']);
         $logFile    = Codetrunk::getInstance()->Config['Logging']['abusivePath'];
         if($abusiveLog = @fopen($logFile, 'a+')) {
            if(strpos(file_get_contents($logFile), $logSprintf) === false) error_log("[".date("d/m/Y H:i:s")."] ".$logSprintf.PHP_EOL, 3, _CP.DS.$logFile);
            fclose($abusiveLog);
         }
         Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyConfirm("The trunk was sucessfully flagged as abusive!", "margin-bottom: 12px;"); return true;});
         Codetrunk::getInstance()->Router->followRoute(null, array(null, "trunks", "showTrunk", array($trunkKey, false, false, false)));
      } else {
         if($getTrunk !== false) Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;"); return true;});
         Codetrunk::getInstance()->Router->followRoute(null, array(null, "trunks", "showTrunk", array($trunkKey)));
      }
      return true;
   }
   
   /**
    * Handles a trunk show through GET
    * 
    * trunksController::showTrunk()
    * @param string $trunkKey Trunk Key
    * @param bool $showPlain
    * @param string $ctName Comment Name
    * @param string $ctComment Comment
    * @return bool
    */
   function showTrunk($trunkKey, $showPlain = false, $ctName = "", $ctComment = "") {
      $trunkData = Codetrunk::getInstance()->File->getTrunk($this->getTrunkKey($trunkKey), Codetrunk::getInstance()->Domain);
      if(strlen($trunkData['pKey'])) $pData = Codetrunk::getInstance()->File->getTrunk($this->getTrunkKey($trunkData['pKey']), Codetrunk::getInstance()->Domain); else $pData = false;
      if($trunkData !== false) {
         if($showPlain !== false) {
            header('Content-type: text/html; charset=utf-8');
            echo nl2br(htmlspecialchars($trunkData['Code']));
            return false;
         }
         Codetrunk::getInstance()->wRenderer->setTitle(str_replace("CT_LANGUAGE", Codetrunk::getInstance()->Syntax->getLanguage($trunkData['Syntax']), Codetrunk::getInstance()->Config['Codetrunk']['tShow']));
         Codetrunk::getInstance()->wRenderer->appendContentHook(function($trunkData, $pData, $ctName, $ctComment = false) {
            ?>
               <script type="text/javascript" src="<?=ROOT?>/syntaxhighlighter/src/shCore.js"></script>
               <?php
                  foreach(Codetrunk::getInstance()->Syntax->allowedLanguages AS $langValue) {?>
               <script type="text/javascript" src="<?=ROOT?>/syntaxhighlighter/scripts/<?=$langValue[1]?>"></script>
                  <?php
                  }
               ?>
               <script type="text/javascript">
                  SyntaxHighlighter.config.clipboardSwf = '<?=ROOT?>/syntaxhighlighter/scripts/clipboard.swf';
                  SyntaxHighlighter.all();
               </script>
            
               <div class="title nRound3 round3" style="overflow: hidden;"><img src="<?=ROOT?>/images/icons/script_code.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
                  <span style="float: left;">Trunk posted on <?=$trunkData['timeString']?> by <u><?=$trunkData['Name']?></u>
                     <? if($pData !== false) { ?><span style="font-size: 12px; color: #5f6f84;">(A revised version of a trunk posted by <a class="link2" href="<?=$pData['url']?>"><?=$pData['Name']?></a>)</span><? } ?></span>
                  <span style="float: right;">Language: <i><?=Codetrunk::getInstance()->Syntax->getLanguage($trunkData['Syntax'])?></i></span>
                  <div class="clearfix">&nbsp;</div>
                  <div style="font-size: 12px; margin-top: 6px; color: #145582;">&rsaquo;
                     <a class="link1" target="_blank" href="<?=$trunkData['url']?>.txt">view plain</a> |
                     <a class="link1" onclick="return confirm('Are you sure you want to report this trunk as abusive?')" href="<?=$trunkData['url']?>/abusive">report abuse</a> |
                     <a class="link1" href="#Comments">comments</a>
                     <? if(isset($_COOKIE['ctToken']) && $trunkData['Token'] == $_COOKIE['ctToken']) { ?> | <a class="link1" onclick="return confirm('Are you sure you want to delete this trunk?\r\nThis action cannot be undone!')" href="<?=$trunkData['url']?>/delete">delete</a><? } ?> |
                     <a class="link1" href="<?=$trunkData['url']?>/download">download</a> |
                     <a class="link1" href="<?=$trunkData['url']?>/correction">submit a correction</a>
                     <? if($trunkData['followUps']) { ?> | <a class="link1" href="<?=$trunkData['url']?>/revisions"><?=count($trunkData['followUps'])?> revisions</a><? } ?>
                  </div>
               </div>

               <div class="nRound3 round3" style="border: 1px solid #90a9c8;  background: #ffffff;">
                  <pre class="brush: <?=$trunkData['Syntax']?>"><?=htmlspecialchars($trunkData['Code']);?></pre>
               </div>
               
               <div class="title nRound3 round3" style="overflow: hidden; margin-top: 8px; background: #bed4ef;"><a class="link2" href="<?=$trunkData['url']?>/correction"><img src="<?=ROOT?>/images/icons/script_lightning.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
                  Click here to submit a correction
               </a></div>
               
               <a name="Comments"></a>
               <div class="title nRound3 round3" style="overflow: hidden; margin-top: 16px;"><img src="<?=ROOT?>/images/icons/comments.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
                  What pepole have to say about this trunk...
               </div>
               
               <?php
                  $trunkComments = Codetrunk::getInstance()->File->getComments($trunkData['Key']);
                  if($trunkComments !== false) foreach($trunkComments AS $Comment) {
                     ?>
               <div class="nRound3 round3" style="overflow: hidden; background: #c6dfed; color: #4b7b96; padding: 6px; border: 1px solid #6e93ca; font: bold 12px Arial; margin-top: 6px;"><img src="<?=ROOT?>/images/icons/comment.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
                  <span style="float: left;"><u><?=$Comment['Name']?></u> Says:</span>
                  <span style="float: right;"><?=$Comment['timeString']?></span>
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
                  <span style="color: #365376; font-size: 11px;">Wrap code with <i>[php][/php]</i>, where <i>php</i> is your preferred language, to get it highlighted. <a target="_blank" class="link2" href="<?=ROOT?>/languages">(supported languages)</a></span></div>
                  <form action="<?=ROOT?>/submitComment" method="post">
                     <input type="hidden" name="Conroller" value="submitComment" />
                     <input type="hidden" name="ctKey" value="<?=$trunkData['Key']?>" />
                     <div class="nRound3 round3" style="padding: 1px; border: 1px solid #90a9c8; background: #ffffff; width: 50%;">
                        <textarea name="ctComment" rows="5" cols="1" style="border: 0; width: 100%; background: #ffffff; color: #1e4b92; font: bold 12px Arial;"><?=$ctComment?></textarea>
                     </div>
                     <div style="margin-top: 12px;">
                        <label for="ctName" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Your name:</label>
                           <input type="text" name="ctName" value="<?=$ctName?>" id="ctName" class="nRound3 nBtn" style="background: #ffffff; margin: 0; padding: 4px; width: 162px; float: left;" />
                           <input type="submit" value="Submit Comment" class="nRound3 nBtn" style="width: 112px; padding: 3px; margin-left: 6px; height: 25px; float: left;" />
                           <div class="clearfix">&nbsp;</div>
                     </div>
                     <div style="margin-top: 12px;">
                        <label for="ctCaptcha" style="font-size: 13px; margin: 4px 8px 0 0; width: 80px;" class="left">Verification:</label>
                           <input type="text" name="ctCaptcha" maxlength="7" id="ctCaptcha" class="nRound3 nBtn" style="background: #ffffff; margin-right: 6px; padding: 4px; width: 52px; float: left;" />
                           <img src="<?=ROOT?>/getCaptcha" alt="Refresh the page to see the captcha" title="Please enter this combination of words and numbers into the text box to the left" />
                           <div class="clearfix">&nbsp;</div>
                     </div>
                  </form>
               </div>
            <?php
         }, array($trunkData, $pData, $ctName, $ctComment));
      } else {
         Codetrunk::getInstance()->wRenderer->appendContentHook(function(){
            Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;"); return true;
         });
         Codetrunk::getInstance()->Router->followRoute(null, false);
      }
      return true;
   }
   
   /**
    * Handles a show languages through GET
    * 
    * trunksController::showLanguages()
    * @return bool
    */
   function showLanguages() {
      Codetrunk::getInstance()->wRenderer->appendContentHook(function(){
      ?>
         <div class="title nRound3 round3" style="overflow: hidden;"><img src="<?=ROOT?>/images/icons/script_code.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
            Supported languages
         </div>
         <?php
            foreach(Codetrunk::getInstance()->Syntax->allowedLanguages AS $langKey => $langValue) {
               ?>
                  <div class="nBtn nRound3 round3" style="border: 1px solid #90a9c8; margin-top: 6px;">
                     <img src="<?=ROOT?>/images/icons/script.png" width="16" height="16" alt="" style="margin-right: 8px;" class="icon pngfix" />
                     <span style="width: 180px; float: left;"><?=$langValue[0]?></span><span style="color: #828282; margin-left: 50px;"><i>[<?=$langKey?>]......[/<?=$langKey?>]</i></span>
                  </div>
               <?php
            }
         ?>
      <?php
      });
      return true;
   }
   
   /**
    * Handles a download trunk through GET
    * 
    * trunksController::downloadTrunk()
    * @param string $trunkKey Trunk Key
    * @return bool
    */
   function downloadTrunk($trunkKey) {
      $trunkKey = $this->getTrunkKey($trunkKey);
      $getTrunk = Codetrunk::getInstance()->File->getTrunk($trunkKey, Codetrunk::getInstance()->Domain);
      if($getTrunk !== false) {
         $dExt = $getTrunk['Syntax'];
         switch($getTrunk['Syntax']) {
            case "bash":
               $dExt = "sh";
               break;
            case "as3":
               $dExt = "as";
               break;
            case "perl":
               $dExt = "pl";
               break;
            case "text":
               $dExt = "txt";
               break;
         }
         header('Content-type: application/'.$dExt);
         header('Content-Disposition: attachment; filename="Codetrunk.com_'.$trunkKey.'.'.$dExt.'"');
         echo $getTrunk['Code'];
      }
      else {
         header("HTTP/1.0 404 Not Found");
      }
   }
   
   /**
    * Handles a correct trunk through POST
    * 
    * trunksController::correctTrunk()
    * @param string $trunkKey Trunk Key
    * @return bool
    */
   function correctTrunk($trunkKey) {
      $trunkData = Codetrunk::getInstance()->File->getTrunk($this->getTrunkKey($trunkKey), Codetrunk::getInstance()->Domain);
      if($trunkData === false) {
         Codetrunk::getInstance()->wRenderer->appendContentHook(function(){
            Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;"); return true;
         });
         Codetrunk::getInstance()->Router->followRoute(null, false);
      } else {
         Codetrunk::getInstance()->Router->followRoute(null, false, array(null, $trunkData['Code'], array('Submit a correction to a trunk by <a href="'.$this->getTrunkUrl($trunkKey).'"><u>'.$trunkData['Name'].'</u></a>', $trunkKey, $trunkData['Syntax'])));
      }
      return true;
   }
   
   /**
    * Handles a dig trunk request through POST
    * 
    * trunksController::digTrunk()
    * @return bool
    */
   function digTrunk() {
      if(isset($_POST['Conroller']) && $_POST['Conroller'] == "digTrunk") {
         $ctTrunkKey   = (isset($_POST['ctTrunkKey']) ? $this->getTrunkKey($_POST['ctTrunkKey']) : false);
         if($ctTrunkKey) header("Location: ".$this->getTrunkUrl($ctTrunkKey));
         else Codetrunk::getInstance()->wRenderer->appendContentHook(function(){Codetrunk::getInstance()->wRenderer->prettyError("Trunk key is invalid.", "margin-bottom: 12px;"); return true;});
      } Codetrunk::getInstance()->Router->followRoute(null, false);
      return true;
   }
   
   /**
    * Fetches the web url for a specified trunk
    * 
    * trunksController::getTrunkUrl()
    * @param string $trunkKey Trunk Key
    * @return string
    */
   function getTrunkUrl($trunkKey) {
      return implode('/', array(Codetrunk::getInstance()->webUrl, $trunkKey));
   }
   
   /**
    * Validates a trunk key
    * 
    * trunksController::getTrunkKey()
    * @param string $trunkKey Trunk Key
    * @return string|false
    */
   function getTrunkKey($trunkKey) {
      if(preg_match("/^[dmf][a-f0-9]{7,8}$/", $trunkKey)) return $trunkKey;
      else return false;
   }
   
   /**
    * Fetches a remember cookie
    * 
    * trunksController::getRememberCookie()
    * @return array
    */
   function getRememberCookie() {
      $returnData = false;
      if(isset($_COOKIE["ctRemember"])) {
         $returnData = array();
         list($ctName, $ctExpiry) = explode('#', $_COOKIE["ctRemember"]);
         $returnData['ctName']   = $this->getName($ctName);
         $returnData['ctExpiry'] = $this->getExpiry($ctExpiry);
         $returnData['ctToken']  = 0;
         if(isset($_COOKIE['ctToken']))
            $returnData['ctToken'] = $this->getToken($_COOKIE['ctToken']);
      }
      return $returnData;
   }
   
   /**
    * Fetches recent trunks
    * 
    * trunksController::getRecentTrunks()
    * @param int $Count Maximum trunks to fetch
    * @return array
    */
   function getRecentTrunks($Count) {
      $recentTrunks = Codetrunk::getInstance()->File->getRecentTrunks($Count, Codetrunk::getInstance()->Domain);
      foreach($recentTrunks as $trunkId => $trunkValue) {
         $trunkAgeYears    = floor($trunkValue['Age']/(3600*24*30*12));
         $trunkAgeMonths    = floor($trunkValue['Age']/(3600*24*30));
         $trunkAgeDays    = floor($trunkValue['Age']/(3600*24));
         $trunkAgeHours   = floor($trunkValue['Age']/3600);
         $trunkAgeMinutes = floor($trunkValue['Age']/60);
         $trunkAgeSeconds = $trunkValue['Age'];

         if($trunkAgeYears > 0) $trunkAge = sprintf("%d year%s", $trunkAgeYears, $trunkAgeYears > 1 ? 's' : '');
         elseif($trunkAgeMonths > 0) $trunkAge = sprintf("%d month%s", $trunkAgeMonths, $trunkAgeMonths > 1 ? 's' : '');
         elseif($trunkAgeDays > 0) $trunkAge = sprintf("%d day%s", $trunkAgeDays, $trunkAgeDays > 1 ? 's' : '');
         elseif($trunkAgeHours > 0) $trunkAge = sprintf("%d hour%s", $trunkAgeHours, $trunkAgeHours > 1 ? 's' : '');
         elseif($trunkAgeMinutes > 0) $trunkAge = sprintf("%d min%s", $trunkAgeMinutes, $trunkAgeMinutes > 1 ? 's' : '');
         else $trunkAge = sprintf("%d sec%s", $trunkAgeSeconds, $trunkAgeSeconds > 1 ? 's' : '');

         $recentTrunks[$trunkId]['ageFmt'] = $trunkAge;
         $recentTrunks[$trunkId]['Url']    = $this->getTrunkUrl($trunkValue['Key']);
         $recentTrunks[$trunkId]['Name']   = $this->getName($trunkValue['Name']);
      }
      return $recentTrunks;      
   }
   
   /**
    * Has a 5% probability of cleaning old posts from the disk
    * 
    * trunksController::doGarbageCollection()
    */
   function doGarbageCollection() {
      if(rand()%100 < 1) $db->gc();
   }
   
   /**
    * Validates a name
    * 
    * trunksController::getName()
    * @param string $ctName Name
    * @return string
    */
   function getName($ctName) {
      return trim(substr(preg_replace("/[^A-Za-z0-9_ \-]/", "", $ctName), 0, 16));   
   }
   
   /**
    * Validates a token
    * 
    * trunksController::getToken()
    * @param string $trunkToken Token
    * @return string
    */
   function getToken($trunkToken) {
      return trim(substr(preg_replace("/[^a-z0-9]/", "", $trunkToken), 0, 32));   
   }
   
   /**
    * Validates a syntax language key
    * 
    * trunksController::getSyntax()
    * @param string $ctSyntax Syntax
    * @return string
    */
   function getSyntax($ctSyntax) {
      return Codetrunk::getInstance()->Syntax->getLanguage($ctSyntax);
   }
   
   /**
    * Validates an expiry flag
    * 
    * trunksController::getExpiry()
    * @param string $ctExpiry Expiry Flag
    * @return string
    */
   function getExpiry($ctExpiry) {
      if(preg_match("/^[dmf]$/", $ctExpiry)) return $ctExpiry;
      else return Codetrunk::getInstance()->Config['Codetrunk']['expiry'];
   }
}
?>

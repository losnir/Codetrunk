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
 * @version 2009 1.08 Alpha Release to Public
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
    * Initializes Trunks View
    * 
    * trunksController::__construct()
    */
    function __construct() {
      Codetrunk::getInstance()->addView("Trunks", "trunks");
    }
    
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
         $ctPKey   = (isset($_POST['ctPKey']) ? $this->getTrunkKey($_POST['ctPKey']) : "");
         if(strlen($ctPKey)) {
            $ctParent = Codetrunk::getInstance()->File->getTrunk($ctPKey, Codetrunk::getInstance()->Domain);
            if($ctParent === false) {
               Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;");
               Codetrunk::getInstance()->Router->followRoute(null, false, array($ctName, $ctTrunk)); return true;
            }                                                
            $errorParams = array($ctName, $ctParent['Code'], array('Submit a correction to a trunk by <a href="'.$ctParent['Url'].'"><u>'.$ctParent['Name'].'</u></a>', $ctPKey, $ctParent['Syntax']));
         } else $errorParams = array($ctName, $ctTrunk);
         if(!strlen($ctTrunk)) {
            Codetrunk::getInstance()->Router->followRoute(null, false, $errorParams);
            Codetrunk::getInstance()->wRenderer->prettyError("Please enter some code.", "margin-top: 12px;");
         } elseif(isset($ctParent) && $ctParent['Code'] == $ctTrunk) {
            Codetrunk::getInstance()->Router->followRoute(null, false, $errorParams);
            Codetrunk::getInstance()->wRenderer->prettyError("The two codes were identical.", "margin-top: 12px;");
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
         Codetrunk::getInstance()->wRenderer->prettyConfirm("Your trunk has been successfully deleted!", "margin-bottom: 12px;");
         Codetrunk::getInstance()->Router->followRoute(null, false);
      } else {
         if($getTrunk !== false) Codetrunk::getInstance()->wRenderer->prettyError("You don't have the permission to delete this trunk!", "margin-bottom: 12px;");
         Codetrunk::getInstance()->Router->followRoute(null, array(null, "Trunks", "showTrunk", array($trunkKey)));
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
         Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;");
         Codetrunk::getInstance()->Router->followRoute(null, false);
      } else Codetrunk::getInstance()->wRenderer->appendContentHook(array(Codetrunk::getInstance()->getView("Trunks"), "renderTrunkRevisions"), array($trunkData));
      return true;
   }

   /**
    * Handles a report abuse through POST and GET
    * 
    * trunksController::reportAbuse()
    * @param string @trunkKey Trunk Key    
    * @return bool
    */
   function reportAbuse($trunkKey) {
      if(isset($_POST['Conroller']) && $_POST['Conroller'] == "reportAbuse") {
         session_start();
         $ctAbuseReason = (isset($_POST['ctAbuseReason']) ? $_POST['ctAbuseReason'] : false);
         $ctComment     = (isset($_POST['ctComment']) ? $_POST['ctComment'] : false);
         $ctEmail       = (isset($_POST['ctEmail']) ? $_POST['ctEmail'] : false);
         $ctCaptcha  = (isset($_POST['ctCaptcha']) ? $_POST['ctCaptcha'] : false);      
         $sesCaptcha = (isset($_SESSION['CodetrunkCaptcha']) ? $_SESSION['CodetrunkCaptcha'] : false);
         unset($_SESSION['CodetrunkCaptcha']);
         if(strtolower($sesCaptcha) != strtolower($ctCaptcha)) {
            unset($_POST['Conroller']);
            Codetrunk::getInstance()->Router->followRoute(null, array(null, "Trunks", "reportAbuse", array($trunkKey)));
            Codetrunk::getInstance()->wRenderer->prettyError("Wrong verification code entered. Please try again!", "margin-top: 12px;");
         } else {
            switch($ctAbuseReason) {
               case "spam": $ctAbuseReasonS = "Spam / Advertising / Junk"; break;
               case "copy": $ctAbuseReasonS = "Copyrighted Code"; break;
               case "personal": $ctAbuseReasonS = "Sensitive Personal Information"; break;
               case "inappropriate": $ctAbuseReasonS = "Inappropriate (Nudity, Violence, Racism)"; break;
               case "other":
               default:
                  $ctAbuseReasonS = "Other";
                  break;
            }
            $ctMailMessage = '<html><head><title>Codetrunk.com - Abuse Report</title></head><body>
               <h2>Codetrunk.com - Abuse Report!</h2>
               The following trunk was reported as abusive: <a href="'.$this->getTrunkUrl($trunkKey).'">'.$this->getTrunkUrl($trunkKey).'</a><br /><br /><br />
               <b>Reason:</b><span style="margin-left: 16px;">'.$ctAbuseReasonS.'</span><br /><br />
               <b>Additional Comments:</b><textarea cols="75" rows="10" style="margin-left: 16px;">'.htmlspecialchars($ctComment).'</textarea><br /><br />
               <b>Email:</b><span style="margin-left: 16px;">'.htmlspecialchars($ctEmail).'</span><br />
            </body></html>';
            $ctMailMessageHeaders  = 'MIME-Version: 1.0'.PHP_EOL;
            $ctMailMessageHeaders .= 'Content-type: text/html; charset=iso-8859-1'.PHP_EOL;
            $ctMailMessageHeaders .= 'From: Codetrunk <nirazuelos@gmail.com>'.PHP_EOL;
            Codetrunk::getInstance()->Router->followRoute(null, false);
            if(mail("Nir Azuelos <nirazuelos@gmail.com>", "Codetrunk.com - Abuse Report", $ctMailMessage, $ctMailMessageHeaders))
               Codetrunk::getInstance()->wRenderer->prettyConfirm("Thank you for sending this abuse report! I will investigate this report as soon as possible! Your help is much appreciated.", "margin-top: 12px;");
            else Codetrunk::getInstance()->wRenderer->prettyError("There was an error while sending this abuse report, sorry!! Please try at some other time ;)", "margin-top: 12px;");
         }
      } else {
         $ctTrunkKey = $this->getTrunkKey($trunkKey);            
         $ctTrunk = Codetrunk::getInstance()->File->getTrunk($ctTrunkKey, Codetrunk::getInstance()->Domain);
         if($ctTrunk === false) {
            Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;");
            Codetrunk::getInstance()->Router->followRoute(null, false); return true;
         }
         Codetrunk::getInstance()->wRenderer->setTitlePage("Report Abuse");
         Codetrunk::getInstance()->wRenderer->appendContentHook(array(Codetrunk::getInstance()->getView("Trunks"), "renderReportAbuse"), array($ctTrunk));
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
            Codetrunk::getInstance()->Router->followRoute(null, array(null, "Trunks", "showTrunk", array($ctKey, false, $ctName, $ctComment)));
            Codetrunk::getInstance()->wRenderer->prettyError("Wrong verification code entered. Please try again!", "margin-top: 12px;");
         } elseif(!strlen($ctComment)) {
            Codetrunk::getInstance()->Router->followRoute(null, array(null, "Trunks", "showTrunk", array($ctKey, false, $ctName, $ctComment)));
            Codetrunk::getInstance()->wRenderer->prettyError("Please enter a comment.", "margin-top: 12px;");
         } elseif(strlen($ctComment) > 2048) {
            Codetrunk::getInstance()->Router->followRoute(null, array(null, "Trunks", "showTrunk", array($ctKey, false, $ctName, $ctComment)));
            Codetrunk::getInstance()->wRenderer->prettyError("Your comment exceeded 2048 characters, please write less!", "margin-top: 12px;");
         } else {
            $ctName   = (strlen($ctName) ? $ctName : "Anonymous");
            Codetrunk::getInstance()->File->addComment($ctKey, $ctComment, $ctName);
            header("Location: ".$this->getTrunkUrl($ctKey));
         }
      } else Codetrunk::getInstance()->Router->followRoute(null, false);
      Codetrunk::getInstance()->wRenderer->appendScript('<script type="text/javascript">window.location.hash="newComment";</script>');
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
         if(!strlen(Codetrunk::getInstance()->Domain)) 
            Codetrunk::getInstance()->wRenderer->setTitleTrunk(Codetrunk::getInstance()->Syntax->getLanguage($trunkData['Syntax'])); 
            Codetrunk::getInstance()->wRenderer->appendScript('<script type="text/javascript" src="'.ROOT.'/syntaxhighlighter/src/shCore.js"></script>');
            Codetrunk::getInstance()->wRenderer->appendScript('<script type="text/javascript">
               SyntaxHighlighter.config.clipboardSwf = \''.ROOT.'/syntaxhighlighter/scripts/clipboard.swf\';
               SyntaxHighlighter.all();
               </script>');
            Codetrunk::getInstance()->wRenderer->appendContentHook(array(Codetrunk::getInstance()->getView("Trunks"), "renderTrunk"), array($trunkData, $pData, $ctName, $ctComment));
      } else {
         Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;");
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
      Codetrunk::getInstance()->wRenderer->appendContentHook(array(Codetrunk::getInstance()->getView("Trunks"), "renderSupportedLanguages"));
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
         Codetrunk::getInstance()->wRenderer->prettyError("The requested trunk was not found. It may have been deleted or has expired.", "margin-bottom: 12px;");
         Codetrunk::getInstance()->Router->followRoute(null, false);
      } else {
         Codetrunk::getInstance()->Router->followRoute(null, false, array(null, $trunkData['Code'], array('Submit a correction to a trunk by <a href="'.$trunkData['Url'].'"><u>'.$trunkData['Name'].'</u></a>', $trunkKey, $trunkData['Syntax'])));
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
         else Codetrunk::getInstance()->wRenderer->prettyError("Trunk key is invalid.", "margin-bottom: 12px;");
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
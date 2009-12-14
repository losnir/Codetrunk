<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: file.class.php
 * $ Changed: 07/12/2009 21:46:19
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
 * @filesource file.class.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.05 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

/**
 * Takes part as the "Model" in "MVC",
 * This class will handle all the file writing / reading.
 * 
 * File
 * @package Codetrunk
 * @access public
 */
class File
{
   /**
    * The constructor takes one parameter which is the storage dir
    * 
    * File::__construct()
    * @param string $storageDir Storage Full Path
    */
   function __construct($storageDir) {
      $this->storageDir = $storageDir;
      if(!is_writable($this->storageDir)) { trigger_error("The directory '{$this->storageDir}' is not writable !!!",  E_USER_ERROR); exit; }
   }
   
   /**
    * Fetches the trunk full path for the specified trunk key
    * 
    * File::getTrunkPath()
    * @param string $trunkKey Trunk Key
    * @return string
    */
   function getTrunkPath($trunkKey) {
      $trunkPath = implode("/", array($this->storageDir, substr($trunkKey, 0, 1), substr($trunkKey, 1, 2), substr($trunkKey, 3, 2), substr($trunkKey, 5, 2), $trunkKey));
      return (is_dir(dirname($trunkPath)) ? $trunkPath : (mkdir(dirname($trunkPath), 0777, true) ? $trunkPath : false));
   }
   
   /**
    * Fetches the MRU (Most Recent Updated) for the specified domain
    * 
    * File::getMru()
    * @param string $Domain
    * @return string
    */
   function getMru($Domain) {
      if(!$l = strlen($Domain)) {
         $mruPath = implode("/", array($this->storageDir, "mru", "default.mru"));
         return (is_dir(dirname($mruPath)) ? $mruPath : (mkdir(dirname($mruPath), 0777, true) ? $mruPath : false));
      } else {
         $mruPath = implode("/", array($this->storageDir, "mru"));
         for($p = 0; $p < min(3, $l); $p++) $mruPath = implode("/", array($mruPath, substr($Domain, $p, 1)));
         $mruPath = implode("/", array($mruPath, "{$Domain}.mru"));
         return (is_dir(dirname($mruPath)) ? $mruPath : (mkdir(dirname($mruPath), 0777, true) ? $mruPath : false));
      }
   }
   
   /**
    * Updates the MRU File
    * If $updateMru is an array then it will be shifted (prepend) to the MRU,
    * Else it should be string containing a trunk key to be deleted from the MRU.
    * 
    * File::updateMru()
    * @param string $Domain
    * @param mixed $updateMru See the description above
    */
   function updateMru($Domain, $updateMru) {
      $mruFile = $this->getMru($Domain);
      $mruOpen = fopen($mruFile, "a+");
      if($mruOpen) {
         if(flock($mruOpen, LOCK_EX)) {
            $mruData = false;
            while(!feof($mruOpen)) { $mruData .= fgets($mruOpen, 4096); }
            if(strlen($mruData)) { $newMru = unserialize($mruData); if(count($newMru) > 15) array_pop($newMru); } else $newMru = array();
            if(is_array($updateMru)) array_unshift($newMru, $updateMru);
            else if(Codetrunk::getInstance()->getController("Trunks")->getTrunkKey($updateMru))
               foreach($newMru as $mruId => $mruValue) if($mruValue['Key'] == $updateMru) { unset($newMru[$mruId]); break; }
            ftruncate($mruOpen, 0);
            fwrite($mruOpen, serialize($newMru));
            flock($mruOpen, LOCK_UN);
            fclose($mruOpen);   
         }
      } else trigger_error("Could not open Most Recent Updated file '{$mruFile}' !!!", E_WARNING);
   }
   
   /**
    * Fetches a time string (formatted) using strftime
    * 
    * File::getTimeString()
    * @param int $unixTimestamp Unix Timestamp returned by time()
    * @param string $timeFormat String Formatting
    * @return string
    */
   function getTimeString($unixTimestamp, $timeFormat = "%A %d/%m/%Y %H:%M:%S") {
      return strftime($timeFormat, $unixTimestamp);
   }
   
   /**
    * Adds a trunk
    * 
    * File::addTrunk()
    * @param string $Name Trunk User Name
    * @param string $Domain Domain
    * @param string $Syntax Syntax (language key)
    * @param string $Code Code
    * @param string $pKey Parent Trunk Keu. Use "" for a fresh trunk.
    * @param string $expiryFlag Expiry flag. Could be one of d / m / f
    * @param string $Token Unique token for ownership
    * @return string
    */
   function addTrunk($Name, $Domain, $Syntax, $Code, $pKey, $expiryFlag, $Token) {
      $Time = time();
      $timeString = $this->getTimeString($Time);
      if(!in_array($expiryFlag, array("d", "m", "f"))) $expiryFlag = Codetrunk::getInstance()->Config['Expiry'];
      switch($expiryFlag) {
         case "d": $expireTime = $Time + 86400; break;
         case "m": $expireTime = $Time + 2592000; break;
         case "f": $expireTime = 0; break;
      }
      do {
         $trunkKey        = $expiryFlag.sprintf("%07x", mt_rand(0, 0xFFFFFFF));
         $fileOpen        = fopen($this->getTrunkPath($trunkKey), "x");
      } while($fileOpen === false);
      if($fileOpen) {
         $newData = func_get_args(); array_push($newData, array(), $Time, $timeString, $expireTime, $trunkKey);
         fwrite($fileOpen, serialize(array_combine(array("Name", "Domain", "Syntax", "Code", "pKey", "expiryFlag", "Token", "followUps", "Time", "timeString", "expireTime", "Key"), $newData)));
         fclose($fileOpen);
         if(strlen($pKey)) {
            $followUp['Key']        = $trunkKey;
            $followUp['Name']       = $Name;
            $followUp['postFormat'] = $timeString;
            $parentFile = $this->getTrunkPath($pKey);
            $parentOpen = fopen($parentFile, "a+");
            if($parentOpen) {
               if(flock($parentOpen, LOCK_EX)) {
                  $parentData = false;
                  while(!feof($parentOpen)) { $parentData .= fread($parentOpen, 4096); }
                  if(strlen($parentData)) $newParent = unserialize($parentData); else trigger_error("Invalid parent trunk at '{$parentFile}' !!!", E_ERROR);
                  $newParent['followUps'][] = $followUp;
                  ftruncate($parentOpen, 0);
                  fwrite($parentOpen, serialize($newParent));
                  flock($parentOpen, LOCK_UN);
                  fclose($parentOpen);
               }
            } else trigger_error("Could not open parent trunk at '{$parentFile}' !!!", E_WARNING);
         }
      }
      $updateMru['Key']        = $trunkKey;
      $updateMru['Name']       = $Name;
      $updateMru['Time']       = $Time;
      $updateMru['postFormat'] = $timeString;
      $this->updateMru($Domain, $updateMru);
      return $trunkKey;
    }

   /**
    * Fetches a trunk
    * 
    * File::getTrunk()
    * @param string $trunkKey Trunk Key
    * @param string $Domain Domain
    * @return array|bool
    */
   function getTrunk($trunkKey, $Domain) {
      $trunkFile = $this->getTrunkPath($trunkKey);
      if(file_exists($trunkFile)) {
         $trunkOpen = fopen($trunkFile, "r");
         if($trunkOpen) {
            $trunkData = false;
            while(!feof($trunkOpen)) { $trunkData .= fread($trunkOpen, 4096); }
            if(strlen($trunkData)) $newTrunk = unserialize($trunkData); else trigger_error("Invalid trunk at '{$trunkFile}' !!!", E_ERROR);
            fclose($trunkOpen);   
         } else trigger_error("Could not open trunk at '{$trunkFile}' !!!", E_ERROR);
         $newTrunk['Url'] = Codetrunk::getInstance()->getController("Trunks")->getTrunkUrl($newTrunk['Key']);
         if($newTrunk['Domain'] != $Domain) return false;
         elseif((time() > $newTrunk['expireTime']) && $newTrunk['expireTime'] != 0) return false;
         else return $newTrunk;
      } else return false;
   }
    
   /**
    * Fetches recent trunks from MRU
    * 
    * File::getRecentTrunks()
    * @param int $Count Maximum Trunks to fetch
    * @param string $Domain Domain
    * @return array
    */
   function getRecentTrunks($Count, $Domain) {
      $mruFile = $this->getMru($Domain);
       if(file_exists($mruFile)) {
         $mruOpen = fopen($mruFile, "a+");
         if($mruOpen) {
            $mruData = false;
            while(!feof($mruOpen)) { $mruData .= fgets($mruOpen, 4096); }
            if(strlen($mruData)) { $newMru = unserialize($mruData); while(count($newMru) > $Count) array_pop($newMru); } else trigger_error("Invalid MRU at '{$mruFile}' !!!", E_WARNING);
            fclose($mruOpen);   
         } else trigget_error("Could not open Most Recent Updated file '{$mruFile}' !!!", E_WARNING);
          foreach($newMru as $mruId => $mruValue) $newMru[$mruId]['Age'] = time() - $mruValue['Time'];;
       } else $newMru = array();   
       return $newMru;
   }
   
   /**
    * Deletes a trunk
    * 
    * File::deleteTrunk()
    * @param string $trunkKey Trunk Key
    * @param string @Domain Domain
    * @return bool
    */
   function deleteTrunk($trunkKey, $Domain) {
      $trunkData = $this->getTrunk($trunkKey, $Domain);
      if($trunkData) {
         $this->updateMru($Domain, $trunkKey);
         unlink($this->getTrunkPath($trunkKey));
       }
       return true;
   }
   
   /**
    * Adds a comment
    * 
    * File::addComment()
    * @param string $trunkKey Trunk Key
    * @param string @Domain Domain
    * @param string $Comment Comment
    * @param string $Name Name
    */
   function addComment($trunkKey, $Domain, $Comment, $Name) {
      $commentFile = $this->getTrunkPath($trunkKey).".c";
      $commentOpen = fopen($commentFile, "a+");
      if($commentOpen) {
         $updateComment['Content']    = $Comment;
         $updateComment['Name']       = $Name;
         $updateComment['Time']       = time();
         $updateComment['Domain']     = $Domain;
         $updateComment['timeString'] = $this->getTimeString(time());
         if(flock($commentOpen, LOCK_EX)) {
            $commentData = false;
            while(!feof($commentOpen)) { $commentData .= fread($commentOpen, 4096); }
            if(strlen($commentData)) $newComment = unserialize($commentData); else $newComment = array();
            $newComment[] = $updateComment;
            ftruncate($commentOpen, 0);
            fwrite($commentOpen, serialize($newComment));
            flock($commentOpen, LOCK_UN);
            fclose($commentOpen);
         }
      } else trigger_error("Could not open comment trunk at '{$commentFile}' !!!", E_WARNING);
   }
   
   /**
    * Fetches all comments for a trunk
    * 
    * File::getComments()
    * @param string $trunkKey Trunk Key
    * @param string @Domain Domain
    * @return array|bool
    */
   function getComments($trunkKey, $Domain) {
      $commentsFile = $this->getTrunkPath($trunkKey).".c";
      if(file_exists($commentsFile)) {
         $commentOpen = fopen($commentsFile, "r");
         if($commentOpen) {
            $commentsData = false;
            while(!feof($commentOpen)) { $commentsData .= fread($commentOpen, 4096); }
            if(strlen($commentsData)) $newComments = unserialize($commentsData); else trigger_error("Invalid trunk comments at '{$commentsFile}' !!!", E_ERROR);
            fclose($commentOpen);   
         } else trigger_error("Could not open trunk at '{$commentsFile}' !!!", E_ERROR);
         if($newComments['Domain'] != $Domain) return false;
         else return $newComments;
      } else return false;
   }
}
?>
<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: default.style.php
 * $ Changed: 07/12/2009 21:47:07
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
 * @filesource default.style.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.0 Initial Release
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

if(!defined("_CT")) exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <title><?=Codetrunk::getInstance()->Config['Codetrunk']['title']?></title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <meta http-equiv="X-UA-Compatible" content="IE=7" />
      <link rel="shortcut icon" type="image/x-icon" href="<?=ROOT?>/favicon.ico" />
      
      <style type="text/css">

      * { margin: 0; padding: 0; }

      body {
         font: 12px Arial;
         text-align: left;
         background-color: #b9d4ff;
         color: #4189b0;
      }
      
      #wrapper {
         width: 90%;
         margin: 0 auto;
      }
      
      #header {
         font: bold 16px Tahoma;
         color: #ffffff;
         margin: 16px 0 8px 0;
      }

      #body {
         text-align: center;
         border: 1px solid #6f92c9;
         padding: 12px;
         background-color: #e8f4f3;
         overflow: auto;
      }
      
      #body #left-sidebar {
         float: left;
         padding-right: 6px;
         width: 19%;
      }

      #body #left-sidebar .block .title {
         background-color: #90a9c8;
         padding: 2px;
         font: bold 14px Arial;
         color: #ffffff;
      }
      
      #body #left-sidebar .block .content {
         padding: 4px;
         margin-top: 8px;
         text-align: left;
         color: #4170b0;
      }
      
      #body #left-sidebar .block .content .list {
         font-size: 14px;
         list-style-type: none;
         text-align: left;
      }
      
      #body #left-sidebar .block .content .list li {
         font: bold 12px Arial;
         overflow: auto;
      }
      
      #body #left-sidebar .block .content .list li:hover {
         background: #e0eaf4;
      }
      
      #body #left-sidebar .block .content .list li .timeAgo {
         font: 12px Arial;
         float: right;
      }
      
      #body #content {
         float: right;
         width: 80%;
         text-align: left;
         color: #355d8e;
         font: bold 13px Arial;
      }
      
      #body #content .title {
         background-color: #d5e9fc;
         padding: 2px;
         font: bold 14px Arial;
         color: #5b89b8;
         margin-bottom: 14px;
         padding: 6px;
         border: 1px solid #90a9c8; 
      }
      
      #footer {
         margin: 4px 0 8px 0;
         text-align: center;
         border: 1px solid #6f92c9;
         padding: 4px;
         background-color: #e8f4f3;
         color: #547496;
         font: bold 12px Arial;
      }
      
      #footer a {
         color: #547496;
         text-decoration: underline;
      }
      
      #footer a:hover, a:active {
         color: #265381;
      }
      
      a {
         color: #4189b0;
         text-decoration: none;
      }

      a:hover, a:active {
         color: #193b64;
         text-decoration: underline;
      }
      
      a.link1 {
         color: #145582;
      }
      
      a.link2 {
         color: #47586d;
      }
      
      .right {
         float: right;
      }
      
      .left {
         float: left;
      }
      
      label {
         margin-right: 5px;
         font: bold 18px Arial;
         color: #6288b6;
      }
      
      select {
         padding: 4px;
         color: #4e679c;
         font: bold 12px Arial;
      }
      
      option {
         color: #4e679c;
         padding: 0 4px 0 4px;
         font: bold 12px Arial;
         text-decoration: none;
      }
      
      optgroup {
         color: #122751;
      }
      
      .icon {
         float: left;
         margin-right: 3px;
      }
      
      .hl {
         background-color: #faf9db;
      }
      
      .clearfix {
         clear: both;
         text-indent: -9999em;
         height: 0;
         width: 0;
         font-size: 0;
      }
      
      .nBtn {
         background: #e2edff;
         border: 1px solid #90a9c8;
         margin: 0;
         padding: 4px;
         color: #42638c;
         font: bold 12px Arial;
      }
      
      .thumbnail {
         margin: 5px 0px 10px 10px;
         background: #fafafa;
         border: 1px solid #e4e4e4;
         padding: 8px;
      }
      
      img { border: 0; }
      
      .pngfix { behavior: url('<?=ROOT?>/js/iepngfix.htc'); }

      .nRound3 { border-radius: 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px; }
      .nRound6 { border-radius: 6px; -moz-border-radius: 6px; -webkit-border-radius: 6px; }
      
      </style>

      <script type="text/javascript" src="<?=ROOT?>/js/DD_roundies_0.0.2a-min.js"></script>
      <script type="text/javascript">
      //<![CDATA[
         DD_roundies.addRule(".round6", "6px", true);
         DD_roundies.addRule(".round3", "3px", true);
      //]]>
      </script>
      <link href="<?=ROOT?>/syntaxhighlighter/styles/shCore.css" rel="stylesheet" type="text/css" />
      <link href="<?=ROOT?>/syntaxhighlighter/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
      <link href="<?=ROOT?>/css/lightbox.css" rel="stylesheet" type="text/css" media="screen" />
      
   </head>

   <body>
   
      <div id="wrapper">
      
         <div id="header">
            <a href="<?=ROOT?>"><img src="<?=ROOT?>/images/codetrunk.png" class="pngfix" width="275" height="49" alt="Codetrunk" /></a>
            <span style="margin: 8px 0 0 8px;">Snippet sharing and debugging tool</span>
         </div>
         
         <div id="body" class="round6">
            <div id="left-sidebar">
               <div class="block">
                  <div class="title nRound3 round3" style="border: 1px solid #fff;">Recent Trunks</div>
                  <div class="content">
                     <ul class="list">
                        <?php
                           foreach(Codetrunk::getInstance()->Controllers['trunks']->getRecentTrunks(10) AS $i => $recentTrunk) {
                              if($i > 0) echo "                        ";
                              echo '<li'.($recentTrunk['Age']==0?' class="hl"':'').'><a class="left" href="'.$recentTrunk['Url'].'">'.$recentTrunk['Name'].'</a>'.
                                   '<span class="timeAgo">'.$recentTrunk['ageFmt'].' ago</span></li>'.PHP_EOL;
                           }
                        ?>
                     </ul>
                  </div>
               </div>
               
               <div class="block" style="margin-top: 24px;">
                  <div class="title nRound3 round3" style="border: 1px solid #fff;">Digging in the Trunk</div>
                  <div class="content">
                     <form action="<?=ROOT?>/digTrunk" method="post">
                        <input type="hidden" name="Conroller" value="digTrunk" />
                        <label for="ctTrunkKey" style="font-size: 13px;">Trunk Key</label>
                        <div style="margin-top: 8px;">
                           <input type="text" name="ctTrunkKey" id="ctTrunkKey" maxlength="8" class="nRound3 nBtn" style="background: #ffffff; margin: 0; padding: 4px; width: 30%; float: left;" />
                           <input type="submit" value="Dig" class="nRound3 nBtn" style="float: left; width: 58%; padding: 3px; margin-left: 2%; height: 25px;" />
                           <div class="clearfix">&nbsp;</div>
                        </div>
                     </form>
                  </div>
               </div>
               
               <div class="block" style="margin-top: 24px;">
                  <div class="title nRound3 round3" style="border: 1px solid #fff;">About</div>
                  <div class="content">
                     <b>Codetrunk</b> allows you to share pieces of code with your friends, irc, or co-workers...<a href="<?=ROOT?>/about"><b>Click here for more</b></a>.
                  </div>
               </div>
            </div>
            
            <div id="content">
               <?php
                  foreach($this->contentHooks AS $rendererCallback) {
                     $hookResult = call_user_func_array($rendererCallback[0], $rendererCallback[1]);
                     if($hookResult === false) break;
                  }
               ?>
            </div>
         </div>
         
         <div id="footer" class="round6">
            <span class="left"><a href="http://www.losnir.com/" title="Site by LosNir" target="_blank"><img src="<?=ROOT?>/images/losnir16px.gif" style="float: left; margin-right: 6px;" alt="LosNir" /></a>
            © 2009 Codetrunk, by Nir Azuelos.</span>
               <a href="<?=ROOT?>/about">About</a>
               <a style="margin-left: 24px;" href="mailto:nirazuelos@gmail.com"><b>Contact</b></a>
         </div>
      </div>
      
      <script type="text/javascript">
         var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
         document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
      </script>
      <script type="text/javascript">
         try {
            var pageTracker = _gat._getTracker("UA-1190348-3");
            pageTracker._setDomainName(".codetrunk.com");
            pageTracker._trackPageview();
         } catch(err) {}
      </script>

   </body>
</html>
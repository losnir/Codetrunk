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
 * @version 2010 1.09 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

if(!defined("_CT")) exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <title><?php echo Codetrunk::getInstance()->wRenderer->getTitle() ?></title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <meta http-equiv="X-UA-Compatible" content="IE=7" />
      <meta name="description" content="Snippet Sharing &amp; Debugging Collaborative Tool. Share pieces of code (also called snippet) with your friends, irc, or co-workers." />
      <meta name="keywords" content="Codetrunk, Snippet, Sharing, Debugging, Collaborative, Tool, Code, Coding, Snippets, Syntax, Highlighter" /> 
      <meta name="robots" content="noarchive" />
      <link rel="shortcut icon" type="image/x-icon" href="<?php echo ROOT?>/favicon.ico" />
      
      <link href="<?php echo ROOT?>/css/codetrunk.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo ROOT?>/syntaxhighlighter/styles/shCore.css" rel="stylesheet" type="text/css" />
      <link href="<?php echo ROOT?>/syntaxhighlighter/styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
      
      <script type="text/javascript" src="<?php echo ROOT?>/js/DD_roundies_0.0.2a-min.js"></script>
      <script type="text/javascript">
      //<![CDATA[
         DD_roundies.addRule(".round6", "6px", true);
         DD_roundies.addRule(".round3", "3px", true);
      //]]>
      </script>
      <script type="text/javascript">
         var _gaq = _gaq || [];
         _gaq.push(['_setAccount', 'UA-1190348-3']);
         _gaq.push(['_setDomainName', '.codetrunk.com']);
         _gaq.push(['_trackPageview']);
         (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
         })();
      </script>   
   </head>

   <body>

      <div id="wrapper">
      
         <div id="header">
            <a href="<?php echo ROOT?>/"><img src="<?php echo ROOT?>/images/codetrunk.png" class="pngfix" width="275" height="49" alt="Codetrunk" /></a>
            <span style="margin: 8px 0 0 8px;">Snippet Sharing &amp; Debugging Collaborative Tool<?php echo (strlen(Codetrunk::getInstance()->Domain) ? " - Private ".substr(Codetrunk::getInstance()->Domain, 0, 24) : null)?></span>
         </div>
         
         <div id="body" class="round6">
            <div id="left-sidebar">
               <div class="block">
                  <div class="title nRound3 round3" style="border: 1px solid #fff;">Recent Trunks</div>
                  <div class="content">
                     <ul class="list">
                        <?php
                           foreach(Codetrunk::getInstance()->getController("Trunks")->getRecentTrunks(10) AS $i => $recentTrunk) {
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
                     <form action="<?php echo ROOT?>/digTrunk" method="post">
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
                     <b>Codetrunk</b> allows you to share pieces of code (also called snippet) with your friends, irc, or co-workers...<a href="<?php echo ROOT?>/about"><b>Click here for more</b></a>.                
                  </div>
               </div>
               
               <div class="block" style="margin-top: 24px;">
                  <div class="title nRound3 round3" style="border: 1px solid #fff;">Subdomains</div>
                  <div class="content">
                     <ul style="margin-left: 14px;">
                        <li style="padding: 0 0 4px;">Getting a subdomain is easy. Just type it and it will be created for you automatically!</li>
                        <li style="padding: 4px 0 4px;">If a subdomain matches the supported languages, that language will be preselected for you.</li>
                     </ul><br />
                     <b>Exmaples:</b>
                     <ul style="margin: 8px 0 0 14px;">
                        <li style="padding: 0 0 4px;"><i><b><a href="http://nirazuelos.codetrunk.com/">http://nirazuelos.codetrunk.com/</a></b></i></li>
                        <li style="padding: 4px 0 4px;"><i><b><a href="http://php.codetrunk.com/">http://php.codetrunk.com/</a></b></i></li>
                     </ul>
                  </div>
               </div>
            </div>
            
            <div id="content">
               <?php $this->processContentHooks() ?>
            </div>
         </div>
         
         <div id="footer" class="round6">
            <span class="left"><a href="http://www.losnir.com/" title="Site by LosNir" target="_blank"><img src="<?php echo ROOT?>/images/losnir16px.gif" style="float: left; margin-right: 6px;" alt="LosNir" /></a>
               © 2010 Codetrunk, by Nir Azuelos.</span>
            <span class="right">
               <a style="margin-right: 24px;" href="<?php echo ROOT?>/about">About</a>
               <a style="margin-right: 24px;" href="mailto:nirazuelos@gmail.com"><b>Contact</b></a>
            </span>
            <div class="clearfix">&nbsp;</div>
         </div>
      </div>
      <?php $this->processScripts() ?>
   </body>
</html>
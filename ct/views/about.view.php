<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: about.view.php
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
 * @filesource about.view.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.05 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO General Public License v3
 */

if(!defined("_CT")) exit;

/**
 * View for 'about' page
 * 
 * aboutView
 * @package Codetrunk
 * @access public
 */
class aboutView extends View
{
   /**
    * Render's About
    * 
    * aboutController::renderAbout()
    * @return bool
    */
   function renderAbout() {
      ?>
               <div class="title nRound3 round3"><img src="<?php echo ROOT?>/images/icons/information.png" width="16" height="16" alt="" style="margin-right: 4px;" class="icon pngfix" /> About Codetrunk and me</div>
               <div style="font: 15px Arial;"><div class="clearfix">&nbsp;</div>
                  <p style="border-bottom: 1px solid #90a9c8; color: #90a9c8; margin-bottom: 6px; padding-bottom: 2px;">About Codetrunk</p>
                     <b>Codetrunk.com</b> is a platform for sharing code snippets with friends, co-workers, pepole on irc channels and, in fact, with everyone on the Internet.
                     Codetrunk is my little project for making a better <a target="_blank" href="http://www.pastebin.com/">pastebin</a>, with the abbility of commenting on trunks (or bins), revising and so on.
                     The main idea was to implement a better syntax highlighter (<a target="_blank" href="http://alexgorbatchev.com/wiki/SyntaxHighlighter">SyntaxHighlighter</a>) with a prettier (but heavier) interface,
                     something which pastebin was lacking. Codetrunk is also an effort of mine to improve my php coding skills, therefore, the software behind Codetrunk implemnts the following:
                     <ul style="margin: 16px 0 16px 16px;">
                        <li><a target="_blank" href="http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller"><b>Model View Controller</b></a> Design Pattern</li>
                        <li><a target="_blank" href="http://en.wikipedia.org/wiki/Singleton_pattern"><b>Singleton</b></a> Design Pattern</li>
                        <li><b>Database-less</b> Design to improve performance &amp; reliability - while maintaining efficiency.</li>
                     </ul>
                  <p style="border-bottom: 1px solid #90a9c8; color: #90a9c8; margin: 4px 0 10px 0; padding-bottom: 2px;">About Me</p>
                     <a href="http://www.losnir.com/" target="_blank" class="thumbnail right nRound3 round3" title="Nir Azuelos"><img alt="Nir Azuelos" src="<?php echo ROOT?>/images/nirazuelos.png" /></a>
                     I'm a 16 years old web developer from Israel with 3 years of experience of developing php applications, including JavaScript (using jQuery) and CSS.
                     I also have an experience with the following languages / technologies:
                     <ul style="margin: 16px 0 16px 16px;">
                        <li><b>Microsoft Visual C#.NET</b> - Only a few projects so far, one of them is <i>Windows 7 Customizer</i> that I'll publish when I'll have some spare time.</li>
                        <li><b>Python</b> - The biggest application I've done so far in Python is a plugin for EventScripts (CS:S) called <a target="_blank" href="http://addons.eventscripts.com/addons/view/pickups"><i>Pickups</i></a>.</li>
                        <li><b>Firefox Extensions</b> - The single and only extension I've done is a simple extension developed especially for <a target="_blank" href="http://www.gilberger.com/">Gil Berger</a>, called <a target="_blank" href="http://get.dotelp.com/">Dotelp</a> (Including a C# BHO for IE)</li>
                     </ul>
                     <div style="margin-top: 24px;">- Nir Azuelos</div>
                  <p style="border-bottom: 1px solid #90a9c8; color: #90a9c8; margin: 24px 0 10px 0; padding-bottom: 2px;">Contact Me</p>
                     <!-- Facebook Badge START -->
                        <a href="http://www.facebook.com/losnir" class="thumbnail right nRound3 round3" title="Nir Azuelos" target="_TOP"><img src="http://badge.facebook.com/badge/731683374.2980.42519294.png" width="120" height="101" style="border: 0px;" alt="" /></a>
                     <!-- Facebook Badge END -->
                     Any suggestions or comments are welcome! If you want to get in touch with me, please send me an email to the address below, or contact me through Facebook using the badge to the right.
                     <div style="margin-top: 16px;">Email: <a href="mailto:nirazuelos@gmail.com"><b>nirazuelos@gmail.com</b></a></div><div class="clearfix">&nbsp;</div>
                  <p style="border-bottom: 1px solid #90a9c8; color: #90a9c8; margin: 4px 0 10px 0; padding-bottom: 2px;">Codetrunk Software</p>
                     <a href="http://www.opensource.org/licenses/agpl-v3.html" class="thumbnail right nRound3 round3" title="AGPL v3" target="_blank"><img src="<?php echo ROOT?>/images/agplv3-155x51.png" width="155" height="51" alt="AGPL v3" /></a>
                     The software behind <b>Codetrunk</b> is open sourced and published under the <a target="_blank" href="http://www.opensource.org/licenses/agpl-v3.html">GNU Affero General Public License v3</a>.
                     This license allows you to make any modifcation to the original source code, with the requirement that you pass your modified source code further on with the same license. Please note that <b>Codetrunk</b>
                     is offered to you <b>WITHOUT ANY WARRANTY</b> and therefore I'm not required to provide any support for it. However, I may still offer help, within the reasonable range ;)
                     <ul style="margin: 16px 0 16px 16px;">
                        <li>You can obtain the source code <a href="http://code.google.com/p/codetrunk/">here</a>.</li>
                        <li>A <a target="_blank" href="<?php echo ROOT?>/doc">documentation</a> generated with phpDoc is also available.</li>
                     </ul>
                     Additionally, the Codetrunk software has some parts and concepts based on the original pastebin source code, so big thanks goes to <b>Paul Dixon</b>, the creator of pastebin. 
                     Also, I'd like to thank <b>Kfir Gollan</b> for allowing me to host Codetrunk on his Private Server!
               </div>
      <?php
      return true;
   }
}
?>
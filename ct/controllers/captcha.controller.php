<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: captcha.controller.php
 * $ Changed: 07/12/2009 21:48:32
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
 * @filesource captcha.controller.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2010 1.09 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

if(!defined("_CT")) exit;

/**
 * Controller for generating a captcha
 * 
 * captchaController
 * @package Codetrunk
 * @access public
 */
class captchaController extends Controller
{
   /**
    * Generates a captcha. Returns false so page will never load.
    * 
    * captchaController::showCaptcha()
    * @return bool
    */
   function showCaptcha() {
      session_start();
      $cWidth  = 95;
      $cHeight = 27;
      $cGSpace = 4;
      $cString = sprintf("%07X", mt_rand(0, 0xFFFFFFF));;
      $cImage  = imagecreate($cWidth, $cHeight);
      $cBColor = imagecolorallocate($cImage, 232, 244, 243);
      $cGColor = imagecolorallocate($cImage, 194, 219, 233);
      $cTColor = imagecolorallocate($cImage, 31, 68, 102);
      imageline($cImage, 0, 0, $cWidth, 0, $cGColor);
      for ($i=0; $i<= $cWidth; $i += $cGSpace) imageline($cImage, $i, 0, $i, $cHeight, $cGColor);
      for ($i=0; $i<= $cHeight;  $i += $cGSpace) imageline($cImage, 0, $i, $cWidth, $i, $cGColor);
      imagettftext($cImage, 22, 5, 5, 24, $cTColor, _CP."/ct/fonts/monofont.ttf", $cString);
      $_SESSION['CodetrunkCaptcha'] = $cString;
      header('Content-type: image/png');
      imagepng($cImage);
      imageDestroy($cImage);
      return false;
   }
}
?>
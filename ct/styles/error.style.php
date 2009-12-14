<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: error.style.php
 * $ Changed: 07/12/2009 21:47:54
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
 * @filesource error.style.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.05 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

if(!defined("_CT")) exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <title>Codetrunk.com</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
      
      <style type="text/css">

      * { margin: 0; padding: 0; }

      body {
         font: 12px Arial;
         text-align: left;
         background-color: #c1e0f1;
         color: #4189b0;
      }
      
      .Box {
         text-align: center;
         border: 1px solid #4189b0;
         padding: 12px;
         background-color: #e8f4f3;
      }
      
      a {
         color: #4189b0;
      }

      a:hover, .insultion a:active {
         color: #004cad;
      }
      
      </style>

   </head>

   <body>
   
      <div style="width: 500px; margin: 32px auto 0;">
         <div class="Box">
            <img src="<?php echo ROOT?>/images/codetrunk.png" alt="Codetrunk" />
            <p style="font: bold 13px Arial; margin-top: 14px;">Hey! <i>Codetrunk</i> encountered an error and had to stop! The error has been logged and should be fixed soon.</p>
            <p style="font: bold 13px Arial; margin-top: 14px;">In the meantime, you may return to the <a href="<?php echo ROOT?>">home page</a>. Hopefully this won't happen again there!</p>
         </div>

      <div style="margin-top: 4px;">© 2009 Nir Azuelos. <a href="mailto:nirazuelos@gmail.com">Contact</a>.</div>
      </div>
   </body>

</html>
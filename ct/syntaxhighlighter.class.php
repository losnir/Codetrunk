<?php
/**
 * This file is part of Codetrunk (c).
 * $ Filename: syntaxhighlighter.class.php
 * $ Changed: 07/12/2009 21:45:39
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
 * @filesource syntaxhighlighter.class.php
 * @author Nir Azuelos <nirazuelos@gmail.com>
 * @copyright Copyright (c) 2009, Nir Azuelos (a.k.a. LosNir); All rights reserved;
 * @version 2009 1.03 Alpha Release to Public
 * @license http://opensource.org/licenses/agpl-v3.html GNU AFFERO GENERAL PUBLIC LICENSE v3
 */

/**
 * Manages all the supported SyntaxHighlighter languages
 * 
 * SyntaxHighlighter
 * @package Codetrunk
 * @access public
 */
class SyntaxHighlighter
{
   /**
    * The constructor takes one paramater which is default language to be selected
    * 
    * SyntaxHighlighter::__construct()
    * @param string $defLanguage Default language
    */
   function __construct($defLanguage) {
      $this->defLanguage = $defLanguage;
      $this->allowedLanguages = array(
         "as3"    => array("ActionScript3", "shBrushAS3.js "),
         "bash"   => array("Bash / Shell", "shBrushBash.js "),
         "coldf"  => array("ColdFusion", "shBrushColdFusion.js"),
         "cpp"    => array("C++ / C", "shBrushCpp.js "),
         "csharp" => array("C#", "shBrushCSharp.js "),
         "css"    => array("CSS", "shBrushCss.js "),
         "delphi" => array("Delphi / Pascal", "shBrushDelphi.js "),
         "diff"   => array("Diff / Patch", "shBrushDiff.js "),
         "erlang" => array("Erlang", "shBrushErlang.js"),
         "groovy" => array("Groovy", "shBrushGroovy.js "),
         "java"   => array("Java", "shBrushJava.js "),
         "jfx"    => array("JavaFX", "shBrushJavaFX.js "),
         "js"     => array("JavaScript", "shBrushJScript.js "),
         "lua"    => array("Lua", "shBrushLua.js"),
         "perl"   => array("Perl", "shBrushPerl.js "),
         "php"    => array("PHP", "shBrushPhp.js "),
         "html"   => array("HTML", "shBrushXml.js "),
         "text"   => array("Plain / Text", "shBrushPlain.js"),
         "ps"     => array("PowerShell", "shBrushPowerShell.js "),
         "py"     => array("Python", "shBrushPython.js "),
         "ruby"   => array("Ruby on Rails", "shBrushRuby.js "),
         "scala"  => array("Scala", "shBrushScala.js "),
         "sql"    => array("SQL", "shBrushSql.js "),
         "vb"     => array("Visual Basic / VB.net", "shBrushVb.js "),
         "xml"    => array("XML", "shBrushXml.js "),

      );
      $this->popularLanguages = array("js", "html", "xml", "php", "cpp", "csharp", "py", "java");
      foreach($this->allowedLanguages AS $langKey => $langValue)
         $this->bbCodeRegex['/\['.$langKey.'\](.*?)\[\/'.$langKey.'\]/is'] = '<pre class="brush: '.$langKey.'">$1</pre>';
   }
   
   /**
    * Gets the language name for the specified language key.
    * Exmaple: Supplying 'py' will return 'python'.
    * 
    * SyntaxHighlighter::getLanguage()
    * @param string $langKey Language key
    * @return string|bool Returns string if the language key was found, else returns false
    */
   function getLanguage($langKey) {
      if(array_key_exists($langKey, $this->allowedLanguages)) return $this->allowedLanguages[$langKey][0];
      else return false;
   }
   
   /**
    * Gets the brush file name for the specified language key
    * 
    * SyntaxHighlighter::getBrushFile()
    * @param string $langKey Language key
    * @return string|bool Returns string if the language key was found, else returns false
    */
   function getBrushFile($langKey) {
      if(array_key_exists($langKey, $this->allowedLanguages)) return $this->allowedLanguages[$langKey][1];
      else return false;
   }
}
?>
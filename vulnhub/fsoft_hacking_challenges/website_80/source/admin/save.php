<?php
  /*
Copyright (c) 2004 AXIA Studio (html://www.axiastudio.it). 

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Library General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
  */

session_start();
include('check.php');
include('functions.php');
$value = $_POST['value'];
$path = $_GET['path'];

if( $path )
  $dirpath = '../pages/'.$path;
else
  $dirpath = '../pages';
$p = $_GET['p'];

$what = $_GET['what'];

switch($what){

 case 'name':
   // aggiorna il nome del file (httpresponse)
   if( $_GET['title'] ) update_title($p, $dirpath, $value);
   elseif( $_GET['name'] ){
     $a = split('.', $value);
     if( $a[-1] != 'html' ) $value .= '.html';
     update_name($p, $dirpath, $value);
   }
   echo $value;
   break;

 case 'title':
   // aggiorna il titolo del file (httpresponse)
   update_title($p, $dirpath, $value);
   echo $value;
   break;

 case 'page':
   // aggiorna il contenuto della pagina
   $content = $_POST['content'];
   if( $content ){
     $content = stripslashes($content);
     update($p, $dirpath, $content);
   }
   header('location: index.php?path='.$path);
   break;
   
 }



?>
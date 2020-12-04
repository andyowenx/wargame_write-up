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

$items = $_POST['items'];
$subpath = $_GET['path'];
if( $subpath )
  $dirpath = '../pages/'.$subpath;
else
  $dirpath = '../pages';
$dirlist = $dirpath.'/dir.list';

$rows = file($dirlist);
$n = count($rows);
$newrows = array();

foreach($items as $pos => $id){
  $newrows[$pos] = $rows[$id];
  }
$fh = fopen($dirlist, 'w');
fwrite($fh, implode('', $newrows));
fclose($fh);

echo 'Done';

?>
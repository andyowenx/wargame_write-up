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

if( !is_dir('../pages') ) header("location: ../admin/login.php");

$path = $_GET['path'];
$p = $_GET['p'];
$msg = $_GET['msg'];
$pext = strrchr($p, '.');
if( $path )
  $dirpath = '../pages/'.$path;
else
  $dirpath = '../pages';


// verifico l'operazione richiesta
$op = $_GET['op'];
if( $op ){
  switch( $op ){
  case 'guestbook':
    include('others/guestbook/functions.php');
    $title = $_POST['title'];
    $message = $_POST['message'];
    sign_guestbook($dirpath, $p, $title, $message);
    break;
  }
  $msg = 'Message sent';
  header('location: index.php?path='.$path.'&p='.$p.'&msg='.$msg);
 }

// invio mail?
/*
$formpage = $_POST['formpage'];
if( $formpage ){
  $message = '\n';
  foreach(Array('name', 'surname') as $k){
    $v = $_POST[$k];
    if( $v ) $message .= '\n'.$k.' : '.$v.'\n';
  }
  $message .= $_POST['message'];
  include('others/formpage/functions.php');
  $res = send_mail($_POST['email'], 'tiziano.lattisi@gmail.com', $_POST['subject'], $_POST['message']);
  die($res);
  header('location: index.php?path='.$path.'&p='.$p);
 }
*/

// template
$templatename = '../template/template.html';
$fh = fopen($templatename, 'r');
$page = fread($fh, filesize($templatename));
fclose($fh);


// page content
if( $pext == '.html' ){
  $filename = $dirpath.'/'.$p;
  $fh = fopen($filename, 'r');
  $data = fread($fh, filesize($filename));
  fclose($fh);
 }
elseif( $pext == '.guestbook' ){
  include('others/guestbook/functions.php');
  $filename = $dirpath.'/cms.guestbook/index.html';
  $fh = fopen($filename, 'r');
  $data = fread($fh, filesize($filename));
  fclose($fh);
  $data .= guestbook_form($path, $p);
  $data .= guestbook_signs($dirpath.'/cms.guestbook');
}
elseif( $pext == '.formpage' ){
  include('others/formpage/functions.php');
  $filename = $dirpath.'/cms.formpage/index.html';
  $fh = fopen($filename, 'r');
  $data = fread($fh, filesize($filename));
  fclose($fh);
  $data .= formpage_form($path, $p);
}

// menu
$rows = file($dirpath.'/dir.list');
$n = count($rows);
//$menu = '<ul id="menu">';
$menu = '';
for( $i=0; $i<$n; $i++ ){
  $a = split("\|", $rows[$i]);
  $file = $a[0];
  $title = $a[1];
  $status = $a[2];

  if( $status != '0' ){
    if( sizeof($a) > 2 ) $extra = $a[3];
    else $extra = null;
    $ext = strrchr($file, '.');
    if( $ext == '.html' or $ext == '.guestbook' or $ext == '.formpage'){
      if( $file == $p ) $menu .= '<li id="menuselected">';
      else $menu .= '<li>';
      $menu .= '<a href="?path='.$path.'&p='.$file.'" >';
      $menu .= $title;
      $menu.= '</a>';
      $menu .= '</li>';
    }
    elseif( $ext == '.link' ){
      $menu .= '<li>';
      $menu .= '<a href="'.$extra.'">'.$title.'</a>';
      $menu .= '</li>';
    }
  }
 }

//$menu .= '</ul>';
$page = str_replace('[[menu]]', $menu, $page);

// message
$page = str_replace('[[message]]', $msg, $page);

// page content
$page = str_replace('[[content]]', $data, $page);

echo $page;



/*
 *  Restituisce un array con la configurazioned degli extra
 */
function get_extra($dirlist, $name){
  $rows = file($dirlist);
  $nt = count($rows);
  for( $i=0; $i<$nt; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    $extras = $a[2];
    if( $file == $name ){
      $extra = split("&", $extras);
      $ne = count($extra);
      $earray = Array();
      for( $i=0; $i<$ne; $i++ ){
	$e = split("=", $extra[$i]);
	$key = $e[0];
	$value = $e[1];
	$earray[$key] = $value;
      }
      return $earray;
    }
  }
  return false;

}


/*
 *  Crea il codice html per un campo di form
 */
function field_maker($type, $id, $title, $help){
  $data = '<div class="field">';
  $data .= '<label>'.$title.'</label>';
  $data .= '<div class="formHelp">'.$help.'</div>';
  switch($type){
  case 'text':
    $data .= '<input type="text" id="'.$id.'" name="'.$id.'" />';
    break;
  case 'textarea':
    $data .= '<textarea id="'.$id.'" name="'.$id.'"></textarea>';
    break;
  }
  $data .= '</div>';
  return $data;
}

?>

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


/*
 *  Restituisce un form da utilizzare per il guestbook
 */
function guestbook_form($path, $p){
  $data = '<form action="?op=guestbook&path='.$path.'&p='.$p.'" method="POST" enctype="multipart/form-data">';
  $data .= '<fieldset>';
  $data .= '<legend>Guestbook</legend>';
  $data .= '<div class="field">';
  $data .= '<label>title</label>';
  $data .= '<div class="formHelp">post here your message...</div>';
  $data .= '<input type="text" id="title" name="title" />';
  $data .= '</div>';
  $data .= '<label>message</label>';
  $data .= '<div class="field">';
  $data .= '<div class="formHelp">post here your message...</div>';
  $data .= '<textarea id="message" name="message"></textarea>';
  $data .= '</div>';
  $data .= '<input type="submit" name="newmessage" value="post" />';
  $data .= '</fieldset>';
  $data .= '</form>';
  return $data;
  }


/*
 *  Restituisce le firme dei visitatori al sito
 */
function guestbook_signs($path){
  $data = '<h2>Signs:</h2>';
  $rows = file($path.'/dir.list');
  $n = sizeof($rows);
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    $title = $a[1];
    $status = $a[2];
    //$extra = split('&', $a[2]);
    $ext = strrchr($file, '.');
    if( $ext == '.sign' && $status != '0' ){
      $filename = $path.'/'.$file;
      $fh = fopen($filename, 'r');
      $sign = fread($fh, filesize($filename));
      fclose($fh);
      $data .= '<strong>'.$title.'</strong>';
      $data .= '<p>'.$sign.'</p>';
      $data .= '<br />';
    }
  }
  return $data;
  }


/*
 *  Firma il guestbook
 */
function sign_guestbook($dirpath, $p, $title, $message){
  include('../admin/functions.php');
  $name = time().'.sign';
  $file = $dirpath.'/'.$p.'/'.$name;
  $fh = fopen($file, 'w');
  fwrite($fh, $message);
  fclose($fh);
  $new_row = $name.'|'.$title.'|0|';
  $dirlist = $dirpath.'/'.$p.'/dir.list';
  in_dirlist($dirlist, $new_row);
}
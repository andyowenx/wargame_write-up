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
 *  Aggiunge un nuovo folder guestbook
 */
function new_guestbook($dirpath){
  if( !check_item('cms.guestbook', $dirpath) ){
    // creazione della cartella
    $folder = $dirpath.'/cms.guestbook';
    mkdir($folder, 0700);
    $new_row = 'cms.guestbook|Guestbook|0|&directonweb:bool=0&maxsign:int=15&alert:bool=0&alertemail:string=';
    $dirlist = $dirpath.'/dir.list';
    in_dirlist($dirlist, $new_row);
    $newdirlist = $folder.'/dir.list';
    create_dirlist($newdirlist);
    // creazione della pagina indice
    new_page('index', $folder, 'Guestbook message');
  }
}


/*
 *  Restituisce un form da utilizzare per il guestbook
 */
function guestbook_form($path, $p){
  $data = '<form action="guestbook.php?path='.$path.'&p='.$p.'" method="POST" enctype="multipart/form-data">';
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
function guestbook_sign($path){
  $data = '<h2>Signs:</h2>';
  $rows = file($path.'/dir.list');
  $n = sizeof($rows);
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    $title = $a[1];
    #$extra = split('&', $a[2]);
    $ext = strrchr($file, '.');
    if( $ext == '.sign' ){
      $filename = $path.'/'.$file;
      $fh = fopen($filename, 'r');
      $sign = fread($fh, filesize($filename));
      fclose($fh);
      $data .= '<strong>'.$title.'</strong>';
      $data .= '<p>'.$sign.'</p>';
    }
  }
  return $data;
}

?>

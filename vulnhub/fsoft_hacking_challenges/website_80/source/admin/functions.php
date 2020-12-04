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
 *  Inizializza la cartella pages
 */
function init_pages(){
  mkdir('../pages', 0770);
  mkdir('../pages/trash', 0770);
  create_dirlist('../pages/dir.list');
  create_dirlist('../pages/trash/dir.list');
  new_page('index', '../pages', 'home page');
  }


/*
 *  Dato un nome di file, restituisce l'icona adeguata
 */
function get_icon($file){
  $ext = strrchr($file, '.');
  switch($ext){
  case '.html':
    $ico = 'document_icon.gif';
    break;
  case '.png':
    $ico = 'image_icon.gif';
    break;
  case '.gif':
    $ico = 'image_icon.gif';
    break;
  case '.jpg':
    $ico = 'image_icon.gif';
    break;
  case '.link':
    $ico = 'link_icon.gif';
    break;
  case '.guestbook':
    $ico = 'book_icon.gif';
    break;
  case '.formpage':
    $ico = 'form_icon.gif';
    break;
  case '.sign':
    $ico = 'sign_icon.gif';
    break;
  default: $ico = 'folder_icon.gif';
    break;
  }
  return $ico;
  }


/*
 *  Aggiunge il chr(10) 
 */
function addchr10($v){
  if( ord(substr($v, -1, 1)) != 10 ) $v .= chr(10);
  return $v;
}


/*
 *   Verifica se un determinato item e' gia' presente nel folder
 */
function check_item($name, $dirpath){
  $dirlist = $dirpath.'/dir.list';
  $rows = file($dirlist);
  $n = count($rows);
  $newrows = array();
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    if( $file == $name ) return true;
  }
  return false;
}


/*
 *  Crea un dirlist
 */
function create_dirlist($dirlist){
  $fh = fopen($dirlist, 'w');
  fwrite($fh, '');
  fclose($fh);
}


/*
 *  Estrae una riga da un dirlist
 */
function get_row($dirlist, $name){
  $rows = file($dirlist);
  $nt = count($rows);
  for( $i=0; $i<$nt; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    if( $file == $name ) return $rows[$i];
  }
  return false;
}


/*
 *  Aggiunge la riga $new_row nella dir.list
 */
function in_dirlist($dirlist, $new_row){
  $rows = file($dirlist);
  $nt = count($rows);
  $newrows = array();
  for( $j=0; $j<$nt; $j++ ){
    array_push($newrows, $rows[$j]);
  }
  // aggiungere il carattere di a capo
  array_push($newrows, addchr10($new_row));
  $fh = fopen($dirlist, 'w');
  fwrite($fh, implode('', $newrows));
  fclose($fh);
}


/*
 *  Elimina dalla dir.list la riga relativa al file $name
 */
function out_dirlist($dirlist, $name){
  $rows = file($dirlist);
  $n = count($rows);
  $newrows = array();
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    if( $file != $name ){
      array_push($newrows, $rows[$i]);
    } else {
      $oldrow = $rows[$i];
    }
    $fh = fopen($dirlist, 'w');
    fwrite($fh, implode('', $newrows));
    fclose($fh);
  }
  return $oldrow;
}


/*
 *  Copia in cache un elemento
 */
function copy_item($name, $dirpath){
  $_SESSION['cmsmini_copycache'] = array($name, $dirpath);
}


/*
 *  Incolla un ellemento dalla cache
 */
function paste_item($dirpath){
  $dirlist = $dirpath.'/dir.list';
  $itemname = $_SESSION['cmsmini_copycache'][0];
  $frompath = $_SESSION['cmsmini_copycache'][1];
  $from = $frompath.'/'.$itemname;
  $to = $dirpath.'/'.$itemname;
  if( $dirpath != $frompath ){
    if( is_file($from) || is_dir($from) ){
      copy($from, $to);
    }
  in_dirlist($dirlist, $itemname.'|copied item|0|');
  $_SESSION['cmsmini_copycache'] = null;
  }
}

/*
 *  Cancella un file spostandolo nel cestino, se gia' nel cestino lo elimina
 */
function del_file($name, $dirpath){
  $dirlist = $dirpath.'/dir.list';
  $del_row = out_dirlist($dirlist, $name);
  $a = split("/", $dirpath);
  if( $a[count($a)-1] == 'trash'){
    // cancello direttamente del file (e' nel cestino)
    $from = '../pages/trash/'.$name;
    if( is_file($from) ) unlink($from);
    elseif( is_dir($from) ){ 
      rmdir_recursive($from); }
    return true;
  }
  else {
    // sposto il file nel cestino
    $trash_dirlist = '../pages/trash/dir.list';
    in_dirlist($trash_dirlist, $del_row);
    $from = $dirpath.'/'.$name;
    /*
    if( check_item($name, '../pages/trash' ) ){
      return false;
    }
    */
    if( is_file($from) || is_dir($from) ){
      $to = '../pages/trash/'.$name;
      rename($from, $to);
    }
  }
  return true;
}


/*
 *  Cancella ricorsivamente una cartella
 */
function rmdir_recursive($dir) {
  $dh = opendir($dir);
  
  if ($dh) {
    while (false != ($fname = readdir($dh))) {
      if (is_dir( "{$dir}/{$fname}" )) {
	if (($fname != '.') && ($fname != '..')) {
	  rmdir_recursive("$dir/$fname");
	}
      } else {
	unlink($dir.'/'.$fname);
      }
    }
    closedir($dh);
  }
  rmdir($dir);
}


/*
 *  Rispistina il file dal cestino
 */
function restore_file($name, $dirpath){
  $trash_dirlist = '../pages/trash/dir.list';
  $del_row = out_dirlist($trash_dirlist, $name);
  $dirlist = $dirpath.'/dir.list';
  in_dirlist($dirlist, $del_row);
  $from = '../pages/trash/'.$name;
  if( is_dir($from) || is_file($from) ){
    $to = $dirpath.'/'.$name;
    rename($from, $to);
  }
  /*
  copy($from, $to);
  unlink($from);
  */
}


/*
 *  Modifica l'ordinamento di un file
 */
function reorder($name, $dirpath, $direction){
  $dirlist = $dirpath.'/dir.list';
  $rows = file($dirlist);
  $n = count($rows);
  $newrows = array();
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    if( $file == $name ){
      if($direction == 'down' ){
	$newrows = array_slice($rows, 0, $i);
	array_push($newrows, $rows[$i+1]);
	array_push($newrows, $rows[$i]);
	$newrows = array_merge($newrows, array_slice($rows, $i+2, $n));
      } elseif($direction == 'up'){
	$newrows = array_slice($rows, 0, $i-1);
	array_push($newrows, $rows[$i]);
	array_push($newrows, $rows[$i-1]);
	$newrows = array_merge($newrows, array_slice($rows, $i+1, $n));	
      }
    }
  }
  $fh = fopen($dirlist, 'w');
  fwrite($fh, implode('', $newrows));
  fclose($fh);
  }


/*
 *  Modifica lo stato di un item
 */
function change_status($name, $dirpath, $newstatus){
  $dirlist = $dirpath.'/dir.list';
  $rows = file($dirlist);
  $n = count($rows);
  $newrows = array();
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    $title = $a[1];
    $extra = $a[3];
    if( $file == $name ){
      array_push($newrows, addchr10($file.'|'.$title.'|'.$newstatus.'|'.$extra));
    } else {
      array_push($newrows, $rows[$i]);
    }
  }
  $fh = fopen($dirlist, 'w');
  fwrite($fh, implode('', $newrows));
  fclose($fh);
}


/*
 *  Aggiorna il contenuto di una pagina
 */
function update($name, $dirpath, $content){
  $file = $dirpath.'/'.$name;
  $fh = fopen($file, 'w');
  fwrite($fh, $content);
  fclose($fh);
}


/*
 *  Aggiorna il titolo di una pagina
 */
function update_title($name, $dirpath, $newtitle){
  $dirlist = $dirpath.'/dir.list';
  $rows = file($dirlist);
  $n = count($rows);
  $newrows = array();
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    $status = $a[2];
    $extra = $a[3];
    if( $file == $name ){
      array_push($newrows, addchr10($a[0].'|'.$newtitle.'|'.$status.'|'.$extra));
    } else {
      array_push($newrows, $rows[$i]);
    }
  }
  $fh = fopen($dirlist, 'w');
  fwrite($fh, implode('', $newrows));
  fclose($fh);
}


/*
 *  Aggiorna gli extra di un item
 */
function update_extra($name, $dirpath, $extra){
  $dirlist = $dirpath.'/dir.list';
  $rows = file($dirlist);
  $n = count($rows);
  $newrows = array();
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    $title = $a[1];
    $status = $a[2];
    if( $file == $name ){
      array_push($newrows, addchr10($file.'|'.$title.'|'.$status.'|'.$extra));
    } else {
      array_push($newrows, $rows[$i]);
    }
  }
  $fh = fopen($dirlist, 'w');
  fwrite($fh, implode('', $newrows));
  fclose($fh);
}


/*
 *  Aggiorna il nome di una pagina
 */
function update_name($name, $dirpath, $newname){
  $dirlist = $dirpath.'/dir.list';
  $rows = file($dirlist);
  $n = count($rows);
  $newrows = array();
  for( $i=0; $i<$n; $i++ ){
    $a = split("\|", $rows[$i]);
    $file = $a[0];
    $title = $a[1];
    $status = $a[2];
    $extra = $a[3];
    if( $file == $name ){
      array_push($newrows, addchr10($newname.'|'.$title.'|'.$status.'|'.$extra));
    } else {
      array_push($newrows, $rows[$i]);
    }
  }
  $fh = fopen($dirlist, 'w');
  fwrite($fh, implode('', $newrows));
  fclose($fh);
  $from = $dirpath.'/'.$name;
  $to = $dirpath.'/'.$newname;
  rename($from, $to);
}




/*
 *  Crea una nuova pagina nella cartella
 */
function new_page($newpagename, $dirpath, $title='title'){
  $a = split(".", $newpagename);
  if( $a[-1] != 'html' ) $newpagename .= '.html';
  $file = $dirpath.'/'.$newpagename;
  $fh = fopen($file, 'w');
  fwrite($fh, 'Bene, ecco tutto.');
  fclose($fh);
  $new_row = $newpagename.'|'.$title.'|0|';
  $dirlist = $dirpath.'/dir.list';
  in_dirlist($dirlist, $new_row);
}


/*
 *  Crea un nuovo link
 */
function new_link($newlinkhref, $dirpath){
  $newlinkname = time().'.link';
  //$a = split(".", $newlinkname);
  //if( $a[-1] != '.link' ) $newlinkname .= '.link';
  /*
  $file = $dirpath.'/'.$newlinkname;
  $fh = fopen($file, 'w');
  fwrite($fh, $newlinkhref);
  fclose($fh);
  */
  $new_row = $newlinkname.'|link title|0|'.$newlinkhref;
  $dirlist = $dirpath.'/dir.list';
  in_dirlist($dirlist, $new_row);
}


/*
 *  Crea una nuova sottocartella
 */
function new_folder($newfoldername, $dirpath){
  $folder = $dirpath.'/'.$newfoldername;
  mkdir($folder, 0700);
  $new_row = $newfoldername.'|new folder|0|';
  $dirlist = $dirpath.'/dir.list';
  in_dirlist($dirlist, $new_row);
  $newdirlist = $folder.'/dir.list';
  create_dirlist($newdirlist);
}


/*
 *  Aggiunge una nuova immagina (da file)
 */
function new_image($imagefile, $dirpath){
  $name = $imagefile['name'];
  $filename = $dirpath.'/'.$name;
  $dirlist = $dirpath.'/dir.list';
  $new_row = $name.'|'.$name.'|0|';
  move_uploaded_file($imagefile['tmp_name'], $filename);
  in_dirlist($dirlist, $new_row);
  }


?>

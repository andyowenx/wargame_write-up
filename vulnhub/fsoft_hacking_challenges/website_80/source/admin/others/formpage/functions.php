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
 *  Aggiunge un nuovo folder form
 */
function new_formpage($dirpath){
  if( !check_item('cms.formpage', $dirpath) ){
    // creazione della cartella
    $folder = $dirpath.'/cms.formpage';
    mkdir($folder, 0700);
    $new_row = 'cms.formpage|Form page|&mailto:string=&name:bool=1&surname:bool=1&telephone:bool=1&email:bool=1&subject:bool=1&message:bool=1';
    $dirlist = $dirpath.'/dir.list';
    in_dirlist($dirlist, $new_row);
    $newdirlist = $folder.'/dir.list';
    create_dirlist($newdirlist);
    // creazione della pagina indice
    new_page('index', $folder, 'Form page message');
  }
}


?>
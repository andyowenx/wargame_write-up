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

$path = $_GET['path'];
if( $path )
  $dirpath = '../pages/'.$path;
else
  $dirpath = '../pages';
$dirlist = $dirpath.'/dir.list';
$name = $_GET['name'];

$keys = array_keys($_POST);
if( count($keys) > 0 ){
  // e' un post, modifico la configurazione
  //die($_POST['title:bool']);
  $newget = '';
  foreach($keys as $k){
    $newget .= '&'.$k.'='.$_POST[$k];
  }
  //die($newget);
  update_extra($name, $dirpath, $newget);
  header('location: index.php?path='.$path);
 }

$row = get_row($dirlist, $name);
$a = split("\|", $row);
$extras = trim($a[3]);

?>
<html>
<head>
<title>CMSmini - configuration page</title>
<link rel="stylesheet" type="text/css" media="all" href="admin_style.css" />

<body>
  <fieldset>
  <legend>Edit configuration <?php echo $name; ?></legend>

  <form action="?path=<?php echo $path; ?>&name=<?php echo $name; ?>" method="post">
  
<?php
$extra = split("&", $extras);
$ne = count($extra);
for( $i=0; $i<$ne; $i++ ){
  $e = split("=", $extra[$i]);
  $key = $e[0];
  $value = $e[1];
  if( $key ){
    echo '<div class="field">';
    //echo '<label>'.$key.'</label>';
    echo '<div class="formHelp">'.$key.'</div>';
    $kt = split(':', $key);
    switch( $kt[1] ){
    case 'bool':

      foreach(Array('0', '1') as $v){
	echo '<input type="radio" id="'.$key.'" name="'.$key.'" value="'.$v.'" ';
	if( $value == $v ) echo 'checked="checked"';
	if( $v == '1' ) echo ' /> true';
	else echo ' /> false';
      }
      /*
      if( $value == '1' ) $checked = 'checked="checked"';
      else $checked = '';
      echo '<input type="checkbox" name="'.$key.'" id="'.$key.'" '.$checked.'/>';
      */
      break;
    case 'string' or 'int':
      echo '<input type="text" name="'.$key.'" id="'.$key.'" value="'.$value.'"/>';
      break;
    }
    echo '</div>';
  }
 }

?>


<input type="submit" value="Save" />
</form>

</fieldset>
</body>
</html>

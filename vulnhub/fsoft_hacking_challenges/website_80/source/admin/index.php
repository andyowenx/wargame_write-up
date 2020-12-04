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

if( !is_dir('../pages') ){
  init_pages();
 }

$subpath = $_GET['path'];
if( $subpath )
  $dirpath = '../pages/'.$subpath;
else
  $dirpath = '../pages';
$dirlist = $dirpath.'/dir.list';
$trash_dirlist = '../pages/trash/dir.list';
$trash_dirpath = '../pages/trash';
$name = $_GET['name'];
$msg = $_GET['msg'];


// verifico l'operazione richiesta
$op = $_GET['op'];
if( $op ){
  switch( $op ){

  case 'reorder':
    // reorder item in folder
    $direction = $_GET['direction'];
    reorder($name, $dirpath, $direction);
    break;

  case 'del':
    // delete item
    $res = del_file($name, $dirpath);
    if( $res ) $msg = 'Ok';
    else $msg = 'Unable to move item in trash';
    break;

  case 'destroy':
    // delete from trash
    $res = del_file($name, '../pages/trash');
    if( $res ) $msg = 'Ok';
    else $msg = 'Unable to remove item from trash';
    break;
    
  case 'cp':
    // copy item
    copy_item($name, $dirpath);
    break;

  case 'paste':
    // paste item
    paste_item($dirpath);
    break;

  case 'restore':
    // restore from trash
    restore_file($name, $dirpath);
    break;

  case 'status':
    // change item status
    $newstatus = $_GET['newstatus'];
    change_status($name, $dirpath, $newstatus);
    break;

  case 'newitem':
    // create a 'base' item (page, folder, image, link)
    foreach(Array('newpage', 'newfolder', 'newimage', 'newlink') as $v){
      $isitem = $_POST[$v];
      if( $isitem ){
	$newname = $_POST[$v.'name'];
	switch( $v ){

	case 'newpage':
	  new_page($newname, $dirpath);
	  break;

	case 'newfolder':
	  new_folder($newname, $dirpath);
	  break;

	case 'newimage':
	  $imagefile = $_FILES['imagefile'];
	  if( is_uploaded_file($imagefile['tmp_name']) ) new_image($imagefile, $dirpath);
	  else die('image upload failed...');
	  break;

	case 'newlink':
	  $newlinkhref = $_POST['newlinkhref'];
	  new_link($newlinkhref, $dirpath);
	  break;

	}
      }
    }
    break;

  case 'newother':
    // create a 'other' item
    $othername = $_POST['newothername'];
    switch($othername){

    case 'guestbook':
      include('others/guestbook/functions.php');
      new_guestbook($dirpath);
      break;

    case 'formpage':
      include('others/formpage/functions.php');      
      new_formpage($dirpath);
      break;

    }
    break;

  }
  header('location: index.php?path='.$subpath);
 }

?>


<html>
<head>
<title>CMSmini - administration page</title>
<link rel="stylesheet" type="text/css" media="all" href="admin_style.css" />
<script src="jscripts/prototype.js" type="text/javascript"></script>
<script src="jscripts/scriptaculous.js" type="text/javascript"></script>
</head>
<body>

  <!-- h2><?php echo $msg; ?></h2 -->

<table>
  <tr>
  <td>

  <!-- file browser -->
  <fieldset>
    <legend>Items in folder</legend>

  <img src="images/folder_midi.gif" border="0" /><?php echo $dirpath; ?>

    <table class="listing" summary="File listing" cellpadding="0" cellspacing="0">
      <thead>

        <tr>
	  <th>page</th>
	  <th>title</th>
	  <th>status</th>
	  <th>order</th>
	  <th>action</th>
	</tr> 
      </thead>
      <tbody id="list_dir">

<?php

if( !is_file($dirlist) ) create_dirlist($dirlist);
$rows = file($dirlist);

// parent link
if( $subpath ){
  $pathtkns = split('/', $subpath);
  $parentpath = implode('/', array_slice($pathtkns, 0, sizeof($pathtnks)-1));
  echo '<tr class="odd" id="item_0">';
  echo '<td>'; // name
  echo '<a href="?path='.$parentpath.'">';
  echo '<img src="images/folder_icon.gif" border="0" alt="ico" />';
  echo '</a>..';
  echo '</td>';
  echo '<td>parent folder</td>'; // title
  echo '<td>-</td>'; // status
  echo '<td>&nbsp;</td>'; // order
  echo '<td>&nbsp;</td>'; // action
  echo '</tr>';
 }

$even = true;
$first = true;
$last = false;
$n = count($rows);
for( $i=0; $i<$n; $i++ ){
  $a = split("\|", $rows[$i]);
  $file = $a[0];
  $title = $a[1];
  $status = $a[2];
  $isfolder = false;
  $toedit = false;

  $a2 = split("\.", $file);
  $ext = $a2[1];

  if( $i == $n-1 ) $last = true;

  if( $file == 'trash' ) $ico = 'trash_icon.gif';
  elseif( is_dir("$dirpath/$file") ){
    $ico = 'folder_icon.gif'; 
    $isfolder = true;
  }
  $ico = get_icon($file);
  if( $ext == 'html' || $ext =='sign' ) $toedit = true;

  if( $file != '.' and $file != '..' ){

    /*
    if( $even ) echo '<tr class="even">';
    else echo '<tr class="odd" id="'.$file.'">';
    */
    echo '<tr class="odd" id="item_'.$i.'">';

    // page
    echo '<td nowrap="nowrap">';
    if( $isfolder ){
      if( $subpath )  echo '<a href="?path='.$subpath.'/'.$file.'">';
      else  echo '<a href="?path='.$file.'">';
    }
    elseif( $toedit ) echo '<a href="../view/?path='.$subpath.'&p='.$file.'">';
    else echo '<a href="'.$dirpath.'/'.$file.'">';
    echo '<img src="images/'.$ico.'" border="0" alt="ico" />';
    echo '</a> <span id="name_'.$file.'" style="cursor: pointer;">'.$file;
    echo '</span>';
    echo '</td>';

    // title
    echo '<td>';
    if( $toedit or $isfolder ){
      echo '<span id="title_'.$file.'">';
      echo $title.'</span>';
      echo '<script type="text/javascript">new Ajax.InPlaceEditor("title_'.$file.'", "save.php?what=title&title=1&path='.$dirpath.'&p='.$file.'", {okButton:false, cancelText:"[x]"});</script>';
    }
    else echo $title;
    echo '</td>';

    // status
    echo '<td>';
    if( $status == '1' ){
      echo '<a href="?path='.$subpath.'&op=status&name='.$file.'&newstatus=0"><img src="images/show_icon.gif" border="0" /></a>';
    } else {
      echo '<a href="?path='.$subpath.'&op=status&name='.$file.'&newstatus=1"><img src="images/hide_icon.gif" border="0" /></a>';
    }
    echo '</td>';

    // order
    echo '<td valign="middle">';
    if( !$first ){
      echo '<a href="?path='.$subpath.'&op=reorder&name='.$file.'&direction=up"><img src="images/up_icon.gif" border="0" /></a>';
    }
    echo '&nbsp;&nbsp;';
    if( !$last ){
      echo '<a href="?path='.$subpath.'&op=reorder&name='.$file.'&direction=down"><img src="images/down_icon.gif" border="0" /></a>';
    }
    echo '</td>';
    

    // action
    echo '<td nowrap="nowrap">';
    if( $toedit ) echo '<a href="edit.php?path='.$subpath.'&name='.$file.'"><img src="images/edit_icon.gif" border="0" /></a> ';
    if( !$isfolder ) echo '<a href="?path='.$subpath.'&op=cp&name='.$file.'"><img src="images/copy_icon.gif" border="0" /></a> ';
    else echo '<a href="configure.php?path='.$subpath.'&name='.$file.'"><img src="images/tool_icon.gif" border="0" /></a> ';
    echo '<a href="?path='.$subpath.'&op=del&name='.$file.'"><img src="images/trash_icon.gif" border="0" /></a>';
    echo '</td>';

    echo '</tr>';

    if( $first) $first = false;
    $even = !$even;

  }
 }
?>

      </tbody>
    </table>

<script type="text/javascript">
  function updateOrder()
{
  var options = {
  method : 'post',
  parameters : Sortable.serialize('list_dir', {name:'items'}),
  
  };
  //new Ajax.Updater('out', 'reorder.php?path=<?php echo $_GET['path']; ?>', options);
  new Ajax.Request('reorder.php?path=<?php echo $_GET['path']; ?>', options);
}
Sortable.create('list_dir', {tag : 'tr', onUpdate : updateOrder });
</script>

    <div id="out"></div>

      <?php
      if( $_SESSION['cmsmini_copycache'] != null ){
	echo '<div style="text-align: right;"><a href="?path='.$subpath.'&op=paste"><img src="images/paste_icon.gif" border="0" /></a></div>';
      }
      ?>
    
    <table>
  <tr><td align="center"><img src="images/show_icon.gif" border="0" /> <img src="images/hide_icon.gif" border="0" /></td>
          <td><div class="formHelp"> modify the visibility of the item</div></td></tr>
  <tr><td align="center"><img src="images/edit_icon.gif" border="0" /></td>
          <td><div class="formHelp"> edit content of the page</div></td></tr>
  <tr><td align="center"><img src="images/copy_icon.gif" border="0" /></td>
          <td><div class="formHelp"> copy item</div></td></tr>
      <tr><td align="center"><img src="images/trash_icon.gif" border="0" /></td>
          <td><div class="formHelp"> move item to the trash bin</div></td></tr>
  <tr><td align="center"><img src="images/up_icon.gif" border="0" /> <img src="images/down_icon.gif" border="0" /></td>
          <td><div class="formHelp"> modify the order of items in folder</div></td></tr>
  <tr><td align="center">&nbsp;</td>
  <td><div class="formHelp"> (you can also drang&drop to modify the order of items)</div></td></tr>
    </table>

  </fieldset>

  </td><td>

  <!-- trash folder -->
  <fieldset>
    <legend>Items in trash folder</legend>

    <img src="images/trash_midi.gif" border="0" />

    <table class="listing" summary="Item in trash" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
	  <th>page</th>
	  <th>title</th>
	  <th>size</th>
	  <th>action</th>
	</tr> 
      </thead>
      <tbody>

<?php

$rows = file($trash_dirlist);
$even = true;
$n = count($rows);
for( $i=0; $i<$n; $i++ ){
  $a = split("\|", $rows[$i]);
  $file = $a[0];
  $title = $a[1];
  if( $file == 'trash' ) $ico = 'trash_icon.gif';
  elseif( is_dir($trash_dirpath.'/'.$file) ){
    $ico = 'folder_icon.gif'; 
    $isfolder = true;
  }
  else $ico = get_icon($file);

  if( $file != '.' and $file != '..' ){
    
    if( $even ) echo '<tr class="even">';
    else echo '<tr class="odd">';

    // page
    echo '<td>';
    echo '<img src="images/'.$ico.'" border="0" alt="ico" />';
    echo $file;
    echo '</td>';

    // title
    echo '<td>'.$title.'</td>';

    // size
    echo '<td>-</td>';

    // action
    echo '<td>';
    echo '<a href="?path='.$subpath.'&op=destroy&name='.$file.'"><img src="images/del_icon.gif" border="0" /></a>';
    echo '<a href="?path='.$subpath.'&op=restore&name='.$file.'"><img src="images/restore_icon.gif" border="0" /></a>';
    echo '</td>';

    echo '</tr>';
    $even = !$even;
  }
 }
?>

      </tbody>
    </table>

    <table>
      <tr><td align="center"><img src="images/restore_icon.gif" border="0" /></td>

          <td><div class="formHelp"> restore item from trash to folder</div></td></tr>
      <tr><td align="center"><img src="images/del_icon.gif" border="0" /></td>
          <td><div class="formHelp"> delete item (no furter restoring is possible!)</div></td></tr>
      <tr><td></td><td><div class="formHelp"> note: you can use <img src="images/trash_icon.gif" border="0" /> and 
              <img src="images/restore_icon.gif" border="0" /> icons to perform cut/paste action</div></td></tr>

    </table>

  </fieldset>

  <!-- admin options -->
  <!-- fieldset>
    <legend>Admin options</legend>

    <table>
      <tr><td align="center"><img src="images/edit_icon.gif" border="0" /></td>
          <td><div class="formHelp"> <a href="css.php">
	  edit css style file (style.css)</a></div></td></tr>

      <tr><td align="center"><img src="images/edit_icon.gif" border="0" /></td>
          <td><div class="formHelp"> <a href="template.php">
	  edit template file (page)</a></div></td></tr>
    </table>

  </fieldset -->


  </td>
  </tr>
</table>


<table>
  <tr>
  <td>

  <form action="<?php echo '?path='.$subpath; ?>&op=newitem" method="post" enctype="multipart/form-data">

  <!-- add item -->
  <fieldset>
    <legend>New base element</legend>

      <div>
        <!-- CMS page -->
	<div class="field">
          <label>Page <img src="images/document_icon.gif" border="0" /></label>

  <div class="formHelp">Add a new page in this folder (insert page short name without spaces).</div>
	    <input type="text" name="newpagename" id="newpagename" size="32" />
	    <input type="submit" name="newpage" id="newpage" value="create new page" />
        </div>
	<!-- CMS folder -->
        <div class="field">
	  <label>Folder <img src="images/folder_icon.gif" border="0" /></label>

  <div class="formHelp">Add a new folder in this folder (insert folder shortname without spaces).</div>
	    <input type="text" name="newfoldername" id="newfoldername" size="32" />
	    <input type="submit" name="newfolder" value="create new folder" />
        </div>
	<!-- image -->
        <div class="field">
	  <label>Image <img src="images/image_icon.gif" border="0" /></label>
	    <div class="formHelp">Add a new image (from file) in this folder</div>
	    <input type="file" name="imagefile" id="imagefile" size="23" />
            <input type="submit" name="newimage" value="insert new image" />
        </div>
	<!-- link -->
        <div class="field">
	  <label>Link <img src="images/folder_icon.gif" border="0" /></label>

            <div class="formHelp">Add a new link in this folder (in http://www.foo.bar form),</div>
	    <input type="text" name="newlinkhref" id="newlinkhref" size="32" />
	    <input type="submit" name="newlink" value="create new link" />
        </div>
	<!-- file -->
        <div class="field">
	  <label>File <img src="images/pdf_icon.gif" border="0" /></label>

	    <div class="formHelp">Add a new file in this folder (pdf, rtf)</div>
	    <input type="file" name="uploadfile" id="uploadfile" size="23" />
	    <input type="submit" name="newfile" value="insert new file" />
        </div>
      </div>

  </fieldset>
  </form>

  </td><td>

  <!-- add item -->
  <form action="<?php echo '?path='.$subpath; ?>&op=newother" method="post" enctype="multipart/form-data">
  <fieldset>
    <legend>New extra element</legend>

      <div>
        <!-- CMS guestbook folder -->
	<div class="field">
	  <label>Others (guestbook, etc...) <img src="images/folder_icon.gif" border="0" /></label>

        <div class="formHelp">Add a new 'others' in this folder.</div>
	    <select name="newothername" id="newothername" >
	       <!--option value="formpage">Form</option-->			 
	       <!--option value="spacer">Menu spacer</option-->
	       <option value="guestbook">Guestbook</option>			 
	    </select>
	    <input type="submit" name="newother" value="create new" />
        </div>
      </div>      

  </fieldset>
  </form>
  </td></tr>
</table>

  
</body>
</html>

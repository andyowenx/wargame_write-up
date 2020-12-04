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

?>
<html>
<head>
<title>CMSmini - edit page</title>
<link rel="stylesheet" type="text/css" media="all" href="admin_style.css" />

<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
  tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	theme_advanced_buttons1 : "formatselect,removeformat,bold,italic,underline,separator,forecolor,backcolor,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink,image,code",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],span[class|align|style]",
	content_css : "../view/style.css"
});
</script>
<script language="javascript" type="text/javascript">
function insert_image(file){
  tinyMCE.themes['advanced']._insertImage(file);
}
</script>
</head>
<body>

<table>
  <tr>
    <td>
      <fieldset>
        <legend>Edit page</legend>

	  <div class="formHelp">Page body</div>

<form action="save.php?what=page&path=<?php echo $path; ?>&p=<?php echo $name; ?>" method="post">
<textarea name="content" id="content" cols="80" rows="25">
<?php

$filename = $dirpath.'/'.$name;
$fh = fopen($filename, 'r');
$data = fread($fh, filesize($filename));
fclose($fh);
echo $data;

?>
</textarea>
<input type="submit" value="Save" />
</form>

</fieldset>
    </td>

    <td>
      <fieldset>
        <legend>Insert item in page</legend>
	
	<table class="listing" summary="File listing" cellpadding="0" cellspacing="0">
	  <thead>

            <tr>
	      <th>item</th>
	      <th>title</th>
	      <th>action</th>
	    </tr> 
	  </thead>
	  <tbody>

<?php

$rows = file($dirlist);

$even = true;
$first = true;
$last = false;
$n = count($rows);
for( $i=0; $i<$n; $i++ ){
  $a = split("\|", $rows[$i]);
  $file = $a[0];
  $title = $a[1];
  $isfolder = false;
  $isimage = false;

  if( $i == $n-1 ) $last = true;

  if( $file == 'trash' ) $ico = 'trash_icon.gif';
  elseif( is_dir("$dirpath/$file") ){
    $ico = 'folder_icon.gif'; 
    $isfolder = true;
    $toedit = false;
  }
  else $ico = get_icon($file);
  if( $ico == 'document_icon.gif' ) $toedit = true;
  if( $ico == 'image_icon.gif' ) $isimage = true;

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

    // action
    echo '<td>';
    /*if( $isimage ) echo '<a href="#" onClick="javascript:insert_image();"><img src="images/link_icon.gif" border="0" /></a> ';*/
    if( $isimage ) echo "<a href=\"#\" onClick=\"javascript:insert_image('".$dirpath."/".$file."');\"><img src=\"images/insert_image_icon.gif\" border=\"0\" /></a>";
    echo '</td>';

    echo '</tr>';

    if( $first) $first = false;
    $even = !$even;

  }
 }

?>

	  </tbody></table>

          <table>
            <tr><td align="center"><img src="images/link_icon.gif" border="0" /></td>
                <td><div class="formHelp"> selected text become a link to this item</div></td></tr>
<tr><td align="center"><img src="images/insert_image_icon.gif" border="0" /></td>
                <td><div class="formHelp"> insert image in page (at cursor position)</div></td></tr>

<tr><td align="center"></td>
                <td><div class="formHelp">(save page before changing folder...)</div></td></tr>
          </table>

      </fieldset>
    </td>

  </tr>
</table>

</body>
</html>
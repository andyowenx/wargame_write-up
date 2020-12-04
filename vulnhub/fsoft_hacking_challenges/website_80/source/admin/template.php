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

die('disabled');
session_start();
include('check.php');
include('functions.php');

$content = $_POST['content'];
if( $content ){
  $content = stripslashes($content);
  update('template.html', '../template', $content);
  header('location: index.php?path='.$path);
}

?>
<html>
<head>
<title>CMSmini - edit template</title>
<link rel="stylesheet" type="text/css" media="all" href="admin_style.css" />

</head>
<body>

<table>
  <tr>
    <td>
      <fieldset>
        <legend>Edit CSS file</legend>

        <div class="formHelp">note: mceContentBody is the main content class</div>

<form action="?path=<?php echo $path; ?>&name=<?php echo $name; ?>" method="post">
<textarea name="content" id="content" cols="160" rows="25">
<?php

$filename = '../template/template.html';
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
  </tr>
</table>

</body>
</html>
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
include('config.php');

$login = $_POST['login'];
$password = $_POST['password'];

if( $login and $password ){
  if( $login == $admin_login and $password == $admin_pass ){
    $_SESSION['cmsmini_login'] = 1;
    $_SESSION['cmsmini_copycache'] = null;
    header('location: index.php?path=');
  } else {
    die('login error.');
  }
 }


?>
<html>
<head>
<title>CMSmini - administration page</title>
<link rel="stylesheet" type="text/css" media="all" href="admin_style.css" />
</head>
<body style="text-align: center;">

<div style="width: 200px">
<form action="login.php" method="post">

   <fieldset>
     <legend>Login</legend>
   
       <div class="field">
         <label>Login</label>
         <input type="input" id="login" name="login" />
       </div>
       <div class="field">
         <label>Password</label>
         <input type="password" id="password" name="password" />
       </div>
       <div class="field">
         <input type="submit" value="login" />
       </div>
   </fieldset>


</form>
</div>

</body>
</html>

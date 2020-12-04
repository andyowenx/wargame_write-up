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
 *  Rstituisce un form da utilizzare per il formpage
 */
function formpage_form($path, $p){
  $config = get_extra('../pages/'.$path.'/dir.list', $p);
  $data = '<form action="?path='.$path.'&p='.$p.'" method="POST" enctype="multipart/form-data">';
  $data .= '<fieldset>';
  $data .= '<legend>Form</legend>';

  if( $config['name:bool'] ) $data .= field_maker('text', 'name', 'name', 'insert your name');
  if( $config['surname:bool'] ) $data .= field_maker('text', 'surname', 'surname', 'insert your surname');
  if( $config['telephone:bool'] ) $data .= field_maker('text', 'telephone', 'telephone', 'insert your telephone');
  if( $config['email:bool'] ) $data .= field_maker('text', 'email', 'email', 'insert your email');
  if( $config['subject:bool'] ) $data .= field_maker('text', 'subject', 'subject', 'insert the subject');
  if( $config['message:bool'] ) $data .= field_maker('textarea', 'message', 'message', 'insert the message');

  $data .= '<input type="submit" name="formpage" value="post" />';
  $data .= '</fieldset>';
  $data .= '</form>';
  return $data;
}


/*
 *  Invia una email
 */
function send_mail($from, $to, $subject, $message){
  
  $headers = "From: $email<$from>\r\n";
  $headers .= "Reply-To: <$from>\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
  $headers .= "X-Mailer: PHP/".phpversion();
  
  $res = mail($to, $subject, '\n'.$message, $headers);

  return $res;
}


?>
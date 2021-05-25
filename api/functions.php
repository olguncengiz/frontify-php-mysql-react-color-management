<?php
// HTTP status codes finding function
function HttpStatus($code) {
  $status = array(
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    400 => 'Bad Request',
    405 => 'Method Not Allowed',
    500 => 'Internal Server Error');

  // If anything else, return 500
  return $status[$code] ? $status[$code] : $status[500];
}

// Header setting function
function SetHeader($code){
  header("HTTP/1.1 ".$code." ".HttpStatus($code));
  header("Content-Type: application/json; charset=utf-8");
}

?>
